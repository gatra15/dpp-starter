<?php

namespace App\Actions\Facilities;

use Illuminate\Http\Request;
use App\Repositories\FacilityRepository;
use App\Actions\Helper\QueryBuilderHelper;

class GetFacilityAction
{
    protected array $filterableColumns = ['name'];
    protected array $searchableColumns = ['name'];
    protected array $allowedSortColumns = ['name'];

    public function __construct(
        protected FacilityRepository $facilityRepository,
        protected QueryBuilderHelper $queryBuilderHelper
    ) {
        //
    }

    public function execute(Request $request)
    {
        $query = $this->facilityRepository->getAll($request);

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
