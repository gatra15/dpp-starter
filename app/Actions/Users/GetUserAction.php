<?php

namespace App\Actions\Users;

use App\Repositories\UserRepository;
use App\Actions\Helper\QueryBuilderHelper;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class GetUserAction
{
    protected array $filterableColumns = ['department_id', 'urusan_id', 'name', 'username', 'email'];
    protected array $searchableColumns = ['name', 'username', 'email'];
    protected array $allowedSortColumns = ['id', 'name', 'email', 'username', 'department_id', 'urusan_id', 'created_at', 'updated_at'];

    public function __construct(
        protected UserRepository $userRepository,
        protected QueryBuilderHelper $queryBuilderHelper
    ) {
        //
    }

    public function execute($request)
    {
        $query = $this->userRepository->getAll($request);

        $query = $this->queryBuilderHelper->applyFilters(
            $request,
            $query,
            $this->filterableColumns,
            $this->searchableColumns
        );

        $query = $this->queryBuilderHelper->applySorting(
            $request,
            $query,
            $this->allowedSortColumns
        );

        return $this->queryBuilderHelper->applyPagination($request, $query);
    }
}
