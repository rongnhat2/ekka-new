<?php

namespace App\Http\Controllers\Admin\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

trait BuildsPaginatedListing
{
    /**
     * Phân trang danh sách bằng ORM Eloquent.
     *
     * @param  class-string<Model>  $modelClass
     */
    protected function paginateModel(
        Request $request,
        string $modelClass,
        array $searchColumns,
        string $orderColumn = 'id',
        string $direction = 'desc'
    ): LengthAwarePaginator {
        $search = trim($request->input('search', ''));
        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, [10, 25, 50, 100], true)) {
            $perPage = 10;
        }

        $query = $modelClass::query();

        if ($search !== '') {
            $query->where(function ($builder) use ($searchColumns, $search) {
                foreach ($searchColumns as $column) {
                    $builder->orWhere($column, 'like', '%' . $search . '%');
                }
            });
        }

        return $query
            ->orderBy($orderColumn, $direction)
            ->paginate($perPage)
            ->withQueryString();
    }
}
