<?php

namespace App\Actions\Departments;

use Illuminate\Http\Request;
use App\Actions\Helper\QueryBuilderHelper;
use App\Repositories\DepartmentRepository;

class GetDepartmentAction
{
    protected array $filterableColumns = ['name'];
    protected array $searchableColumns = ['name'];
    protected array $allowedSortColumns = ['name'];

    public function __construct(
        protected DepartmentRepository $departmentRepository,
        protected QueryBuilderHelper $queryBuilderHelper
    ) {
        //
    }

    public function execute(Request $request)
    {
        $query = $this->departmentRepository->getAll($request);

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
