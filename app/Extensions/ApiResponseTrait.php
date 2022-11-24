<?php

namespace App\Extensions;

use App\Exceptions\BusinessException;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use function response;

trait ApiResponseTrait
{
    /**
     * 成功
     * @param  null  $data
     * @param  array  $codeResponse
     * @param  string  $customInfo
     * @return JsonResponse
     */
    public function success($data = null, array $codeResponse = ResponseEnum::HTTP_SUCCESS, string $customInfo = ''): JsonResponse
    {
        if ($data instanceof LengthAwarePaginator) {
            return $this->successPaginate(...func_get_args());
        }
        return $this->jsonResponse('success', $codeResponse, $data, $customInfo);
    }

    /**
     * 失败
     * @param  array  $codeResponse
     * @param  string  $customInfo
     * @param  null  $data
     * @return JsonResponse
     */
    public function fail(string $customInfo = '', array $codeResponse = ResponseEnum::HTTP_ERROR, $data = null): JsonResponse
    {
        return $this->jsonResponse('fail', $codeResponse, $data, $customInfo);
    }

    /**
     * json响应
     * @param $status string 状态
     * @param $codeResponse array 状态码
     * @param $data mixed 数据
     * @param $meta null 自定义信息
     * @return JsonResponse
     */
    private function jsonResponse(string $status, array $codeResponse, mixed $data, $meta = null): JsonResponse
    {
        [$code, $message] = $codeResponse;
        $result = [
            'code' => $code,
            'message' => $message,
        ];

        if ($data) {
            $result['data'] = $data;
        }

        if ($meta) {
            if (is_array($meta)) {
                $result['meta'] = $meta;
            } else {
                $result['message'] = $meta;
            }
        }

        return response()->json($result);
    }


    /**
     * 成功分页返回
     * @param  $paginator
     * @return JsonResponse
     */
    protected function successPaginate($paginator): JsonResponse
    {
        [$code, $message] = ResponseEnum::HTTP_SUCCESS;
        $currentPage = (int) $paginator->currentPage();
        $data = [
            'status' => 'success',
            'code' => $code,
            'message' => $message,
            'data' => $paginator->getCollection(),
            'meta' => [
                'prev_page' => $currentPage > 1 ? $currentPage - 1 : null,
                'next_page' => $paginator->hasMorePages() ? $currentPage + 1 : null,
                'page' => $paginator->currentPage(), // 当前页数
                'pagesize' => $paginator->perPage(), // 当前条数
                'has_more' => $paginator->hasMorePages(),
                //'total_count' => $paginator->total(), // 数据总量
                //'total_page' => $paginator->lastPage(), // 总页数
            ],
        ];

        return response()->json($data);
    }

    /**
     * 业务异常返回
     * @param  array  $codeResponse
     * @param  string  $customInfo
     * @throws BusinessException
     */
    public function throwBusinessException(array $codeResponse = ResponseEnum::HTTP_ERROR, string $customInfo = ''): void
    {
        throw new BusinessException($codeResponse, $customInfo);
    }
}
