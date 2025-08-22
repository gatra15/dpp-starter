<?php

namespace App\Actions\Instansi;

use Illuminate\Http\Request;
use App\Repositories\InstansiRepository;
use App\Actions\Helper\QueryBuilderHelper;

class GetInstansiAction
{
    protected array $filterableColumns = ['name'];
    protected array $searchableColumns = ['name'];
    protected array $allowedSortColumns = ['name'];

    public function __construct(
        protected InstansiRepository $instansiRepository,
        protected QueryBuilderHelper $queryBuilderHelper
    ) {
        //
    }

    public function execute(Request $request)
    {
        $query = $this->instansiRepository->getAll($request);

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