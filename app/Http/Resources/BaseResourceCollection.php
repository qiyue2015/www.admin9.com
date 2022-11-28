<?php

namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\AbstractPaginator;

class BaseResourceCollection extends ResourceCollection
{

    use CommonResourceTrait;

    /**
     * The additional data that should be added to the top-level resource array.
     *
     * @var array
     */
    public $with = [
        'code' => 0,
        'message' => 'success',
    ];

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  Request  $request
     *
     * @return JsonResponse
     */
    public function toResponse($request): JsonResponse
    {
        $response = $this->resource instanceof AbstractPaginator
            ? (new CustomPaginatedResourceResponse($this))->toResponse($request)
            : parent::toResponse($request);

        $encodingOptions = config('app.debug') ? JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES + JSON_PRETTY_PRINT : JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES;

        return $response->setEncodingOptions($encodingOptions);
    }
}
