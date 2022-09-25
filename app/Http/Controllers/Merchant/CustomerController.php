<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\MainController;
use App\Models\BadCustomer;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Log;
use Qcloud\Cos\Client;
use TencentCloud\Common\Credential;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Iai\V20200303\IaiClient;
use TencentCloud\Iai\V20200303\Models\CreatePersonRequest;
use TencentCloud\Iai\V20200303\Models\GetPersonListRequest;
use TencentCloud\Iai\V20200303\Models\SearchFacesRequest;

class CustomerController extends MainController
{

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $data = $request->validate(['phone' => 'required|string|min:8']);
        $customer = Customer::where('phone', 'like', '%' . $data['phone'] . '%')->first();

        $this->user()->queryLog()->create($data);
        return json_response($customer);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function photo(Request $request): JsonResponse
    {
        $data = $request->validate([
            'base64' => 'required'
        ]);
        try {
            $cred = new Credential(env('TENCENT_CLOUD_SECRET_ID'), env('TENCENT_CLOUD_SECRET_KEY'));
            $httpProfile = new HttpProfile();
            $httpProfile->setEndpoint("iai.tencentcloudapi.com");

            $clientProfile = new ClientProfile();
            $clientProfile->setHttpProfile($httpProfile);
            $client = new IaiClient($cred, "ap-guangzhou", $clientProfile);

            $req = new SearchFacesRequest();

            $params = [
                "GroupIds" => ['db_1'],
                "Image"    => $data['base64']
            ];
            $req->fromJsonString(json_encode($params));

            $resp = $client->SearchFaces($req)->Results[0]->Candidates;

            $facesResult = collect($resp)->map(function ($item) {
                return [
                    'customer_id' => $item->PersonId,
                    'score'       => sprintf("%.2f", $item->Score),
                ];
            });
            $faceIds = $facesResult->pluck('customer_id')->toArray();
            $facesData = BadCustomer::query()->whereIn('customer_id', $faceIds)
                ->orderByRaw(sprintf("FIND_IN_SET(customer_id, '%s')", join(',', $faceIds)))
                ->get()->toArray();
            $items = [];
            $tencentCdn = 'https://customer-1252138383.cos.ap-guangzhou.myqcloud.com/';
            foreach ($facesData as $key => $item) {
                $items[$key] = $item;
                $items[$key]['image'] = $tencentCdn . $item['image'];
                $items[$key]['score'] = $facesResult[$key]['score'];
            }
            return json_response($items);
        } catch (TencentCloudSDKException $e) {
            return json_response($e);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function report(Request $request): JsonResponse
    {
        $data = $request->validate([
            'image' => 'required'
        ]);

        $secretId = env('TENCENT_CLOUD_SECRET_ID'); //替换为用户的 secretId，请登录访问管理控制台进行查看和管理，https://console.cloud.tencent.com/cam/capi
        $secretKey = env('TENCENT_CLOUD_SECRET_KEY'); //替换为用户的 secretKey，请登录访问管理控制台进行查看和管理，https://console.cloud.tencent.com/cam/capi
        $region = "ap-guangzhou"; //替换为用户的 region，已创建桶归属的region可以在控制台查看，https://console.cloud.tencent.com/cos5/bucket
        $cosClient = new Client(
            [
                'region'      => $region,
                'schema'      => 'https',
                'credentials' => [
                    'secretId'  => $secretId,
                    'secretKey' => $secretKey
                ]
            ]);
        try {
            preg_match('/^(data:\s*image\/(\w+);base64,)/', $data['image'], $base);
            $type = $base[2];
            $key = now()->toDateString() . '/' . md5(time()) . '.' . $type;
            $cosClient->putObject(array(
                'Bucket' => 'customer-1252138383',
                'Key'    => $key,
                'Body'   => base64_decode(str_replace($base[1], '', $data['image'])),
            ));
            try {
                $cred = new Credential($secretId, $secretKey);
                $httpProfile = new HttpProfile();
                $httpProfile->setEndpoint("iai.tencentcloudapi.com");
                $clientProfile = new ClientProfile();
                $clientProfile->setHttpProfile($httpProfile);
                $client = new IaiClient($cred, "ap-guangzhou", $clientProfile);
                $req = new GetPersonListRequest();
                $params = array(
                    "GroupId" => 'db_1',
                    "Limit"   => 1
                );
                $req->fromJsonString(json_encode($params));
                $resp = $client->GetPersonList($req);

                $req = new CreatePersonRequest();
                $personNum = (int) $resp->PersonInfos[0]->PersonId + 1;
                $params = array(
                    "GroupId"    => 'db_1',
                    "PersonName" => 'customer_name_' . $personNum,
                    "PersonId"   => (string) $personNum,
                    "Gender"     => 1,
                    "Url"        => 'https://customer-1252138383.cos.ap-guangzhou.myqcloud.com/' . $key
                );
                $req->fromJsonString(json_encode($params));
                $client->CreatePerson($req);
                $this->user()->badCustomer()->create([
                    'customer_id' => $personNum,
                    'image'       => $key,
                ]);
                return json_response(status_code: 201);
            } catch (TencentCloudSDKException $e) {
                Log::error($e->getMessage());
                return json_response([], $e->getMessage(), 403);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return json_response([], $e->getMessage(), 403);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function number(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'   => 'required|string',
            'phone'  => 'required|string|unique:customers,phone',
            'status' => 'required|integer',
        ]);
        $customer = Customer::where('phone', $data['phone'])->first();
        if (!$customer instanceof Customer) {
            Customer::create($data);
        }
        return json_response(status_code: 201);
    }
}
