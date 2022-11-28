<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\JsonResponse;

class BaseResource extends JsonResource
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
     * @param  Request  $request
     *
     * @return JsonResponse
     */
    public function toResponse($request): JsonResponse
    {
        $response = parent::toResponse($request);

        $encodingOptions = config('app.debug') ? JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES + JSON_PRETTY_PRINT : JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES;

        return $response->setEncodingOptions($encodingOptions);
    }

    public static function collection($resource)
    {
        $collection = tap(new CustomAnonymousResourceCollection($resource, static::class), static function ($collection) {
            if (property_exists(static::class, 'preserveKeys')) {
                $collection->preserveKeys = true === (new static([]))->preserveKeys;
            }
        });

        $collection->with = (new self([]))->with;

        return $collection;
    }
}
