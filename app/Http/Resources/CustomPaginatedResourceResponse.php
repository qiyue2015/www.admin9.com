<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\PaginatedResourceResponse;

class CustomPaginatedResourceResponse extends PaginatedResourceResponse
{
    /**
     * Add the pagination information to the response.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    protected function paginationInformation($request)
    {
        $paginator = $this->resource->resource;

        $currentPage = (int) $paginator->currentPage();

        $pageMeta = [
            'prev_page' => $currentPage > 1 ? $currentPage - 1 : null,
            'next_page' => $paginator->hasMorePages() ? $currentPage + 1 : null,
            'page' => $currentPage,
            'pagesize' => (int) $paginator->perPage(),
            'has_more' => $paginator->hasMorePages(),
        ];

        if (is_a($paginator, LengthAwarePaginator::class)) {
            $pageMeta['total'] = (int) $paginator->total();
        }

        return [
            'meta' => $pageMeta,
        ];
    }
}
