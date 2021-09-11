<?php

use Illuminate\Http\JsonResponse;

/**
 * @param $data
 * @param string|null $message
 * @param int $status_code
 * @return JsonResponse
 */
function json_response($data = null, string $message = null, int $status_code = 200): JsonResponse
{
    $item = [];
    if (!empty($message)) {
        $message = __('messages.' . $message);
        $item['message'] = $message;
    }
    if (!empty($data)) {
        $item['data'] = $data;
    }

    return response()->json($item)->setStatusCode($status_code);
}
