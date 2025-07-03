<?php

namespace App\Actions\Users;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class GetUserAction
{
    public function __construct(protected UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute($request)
    {
        $query = $this->userRepository->model->query();

        $query = $this->applyFilters($request, $query);

        $query = $this->applySorting($request, $query);

        $perPage = $request->get('per_page', 10);

        if ($perPage === 0 || $request->boolean('no_pagination')) {
            return $query->get();
        }

        return $query->paginate($perPage);
    }

    protected function applyFilters(Request $request, Builder $query): Builder
    {
        if ($request->has('department_id') && $request->input('department_id') !== null) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->has('urusan_id') && $request->input('urusan_id') !== null) {
            $query->where('urusan_id', $request->urusan_id);
        }

        if ($request->has('search') && $request->input('search') !== null) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ILIKE', '%' . $search . '%')
                    ->orWhere('email', 'ILIKE', '%' . $search . '%')
                    ->orWhere('username', 'ILIKE', '%' . $search . '%');
            });
        }

        if ($request->has('name') && $request->input('name') !== null && !$request->has('search')) {
            $query->where('name', 'ILIKE', '%' . $request->input('name') . '%');
        }
        if ($request->has('username') && $request->input('username') !== null && !$request->has('search')) {
            $query->where('username', 'ILIKE', '%' . $request->input('username') . '%');
        }
        if ($request->has('email') && $request->input('email') !== null && !$request->has('search')) {
            $query->where('email', 'ILIKE', '%' . $request->input('email') . '%');
        }

        return $query;
    }

    protected function applySorting(Request $request, Builder $query): Builder
    {
        $sortBy = $request->input('sort_by', 'id');
        $sortOrder = $request->input('sort_order', 'asc');

        $allowedSortColumns = ['id', 'name', 'email', 'username', 'department_id', 'urusan_id', 'created_at', 'updated_at'];
        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'id';
        }

        $query->orderBy($sortBy, $sortOrder);

        return $query;
    }
}
