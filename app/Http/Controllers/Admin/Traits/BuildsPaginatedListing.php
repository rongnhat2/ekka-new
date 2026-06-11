<?php

namespace App\Http\Controllers\Admin\Traits;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use DB;

trait BuildsPaginatedListing
{
    protected function paginateTable(
        Request $request,
        string $table,
        array $searchColumns,
        string $orderBy = 'id DESC'
    ): LengthAwarePaginator {
        $search = trim($request->input('search', ''));
        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, [10, 25, 50, 100], true)) {
            $perPage = 10;
        }

        $where = 'WHERE 1=1';
        $bindings = [];

        if ($search !== '') {
            $conditions = array_map(function ($column) {
                return "{$column} LIKE ?";
            }, $searchColumns);
            $where .= ' AND (' . implode(' OR ', $conditions) . ')';
            $like = '%' . $search . '%';
            $bindings = array_fill(0, count($searchColumns), $like);
        }

        $total = (int) DB::selectOne("SELECT COUNT(*) AS total FROM {$table} {$where}", $bindings)->total;
        $page = max(1, (int) $request->input('page', 1));
        $offset = ($page - 1) * $perPage;

        $items = DB::select(
            "SELECT * FROM {$table} {$where} ORDER BY {$orderBy} LIMIT ? OFFSET ?",
            array_merge($bindings, [$perPage, $offset])
        );

        return new LengthAwarePaginator($items, $total, $perPage, $page, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);
    }
}
