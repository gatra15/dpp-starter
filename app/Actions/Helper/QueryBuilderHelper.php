<?php

namespace App\Actions\Helper;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class QueryBuilderHelper
{
    public function applyFilters(Request $request, Builder $query, array $filterableColumns = [], array $searchableColumns = []): Builder
    {
        foreach ($filterableColumns as $column) {
            if ($request->has($column) && $request->input($column) !== null) {
                $query->where($column, $request->input($column));
            }
        }

        if ($request->has('search') && $request->input('search') !== null) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm, $searchableColumns) {
                foreach ($searchableColumns as $index => $column) {
                    if ($index === 0) {
                        $q->where($column, 'ILIKE', '%' . $searchTerm . '%');
                    } else {
                        $q->orWhere($column, 'ILIKE', '%' . $searchTerm . '%');
                    }
                }
            });
        }

        return $query;
    }

    public function applySorting(Request $request, Builder $query, array $allowedSortColumns = []): Builder
    {
        $sortBy = $request->input('sort_by', 'id');
        $sortOrder = $request->input('sort_order', 'asc');

        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'id';
        }

        $query->orderBy($sortBy, $sortOrder);

        return $query;
    }

    public function applyPagination(Request $request, Builder $query)
    {
        $perPage = $request->input('per_page', 10);

        if ($perPage === 0 || $request->boolean('no_pagination')) {
            return $query->get();
        }

        return $query->paginate($perPage);
    }
}
