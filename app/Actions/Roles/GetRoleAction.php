<?php

namespace App\Actions\Roles;

use GuzzleHttp\Psr7\Query;
use Illuminate\Http\Request;
use App\Actions\Helper\QueryBuilderHelper;
use App\Repositories\RoleRepository;

class GetRoleAction
{
    protected array $filterableColumns = ['name'];
    protected array $searchableColumns = ['name'];
    protected array $allowedSortColumns = ['name'];

    public function __construct(
        protected RoleRepository $roleRepository,
        protected QueryBuilderHelper $queryBuilderHelper
    ) {
        //
    }
    public function execute(Request $request)
    {
        $query = $this->roleRepository->getAll($request);

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
