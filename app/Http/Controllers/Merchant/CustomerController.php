<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\MainController;
use App\Models\BadCustomer;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use TencentCloud\Common\Credential;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Iai\V20200303\IaiClient;
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
                    'score' => sprintf("%.2f", $item->Score),
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
}
