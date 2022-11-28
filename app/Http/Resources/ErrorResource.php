<?php

namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class ErrorResource extends JsonResource
{
    use  CommonResourceTrait;

    /**
     * The additional data that should be added to the top-level resource array.
     *
     * @var array
     */
    public $with = [
        'code' => -1,
        'message' => '您的请求服务器处理失败。',
    ];

    /**
     * Create a new resource instance.
     *
     * @param mixed $resource
     *
     * @return void
     */
    public function __construct($resource = [])
    {
        parent::__construct($resource);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function toResponse($request)
    {
        /** @var JsonResponse $response */
        $response = parent::toResponse($request);

        $encodingOptions = config('app.debug') ? JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES + JSON_PRETTY_PRINT : 0;

        $data = $response->getData(true);
        $data['data'] = null;
        $response->setData((array) $data);

        return $response->setEncodingOptions($encodingOptions);
    }
}
