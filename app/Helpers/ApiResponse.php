<?php

namespace App\Helpers;

use App\Exceptions\BusinessException;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

trait ApiResponse
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
     * @param $customInfo string 自定义信息
     * @return JsonResponse
     */
    private function jsonResponse(string $status, array $codeResponse, mixed $data, string $customInfo): JsonResponse
    {
        [$code, $message] = $codeResponse;
        return response()->json([
            'status' => $status,
            'code' => $code,
            'message' => $customInfo ?: $message,
            'data' => $data ?: null,
        ]);
    }


    /**
     * 成功分页返回
     * @param $page
     * @return JsonResponse
     */
    protected function successPaginate($page): JsonResponse
    {
        return $this->success($this->paginate($page));
    }

    /**
     * 返回分页数据
     * @param $page
     * @return mixed
     */
    protected function getPaginate($page): mixed
    {
        return $this->paginate($page);
    }

    /**
     * 分页处理
     * @param $page
     * @return mixed
     */
    private function paginate($page): mixed
    {
        if ($page instanceof LengthAwarePaginator) {
            return [
                'list' => $page->items(), // 数据列表
                'total_count' => $page->total(), // 数据总量
                'page' => $page->currentPage(), // 当前页数
                'page_size' => $page->perPage(), // 当前条数
                'total_page' => $page->lastPage() // 总页数
            ];
        }
        if ($page instanceof Collection) {
            $page = $page->toArray();
        }
        if (!is_array($page)) {
            return $page;
        }
        $total = count($page);
        return [
            'total' => $total,
            'page' => 1,
            'limit' => $total,
            'pages' => 1,
            'list' => $page,
        ];
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
