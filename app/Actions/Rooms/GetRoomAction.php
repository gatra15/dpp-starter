<?php

namespace App\Actions\Rooms;

use Illuminate\Http\Request;
use App\Actions\Helper\QueryBuilderHelper;
use App\Repositories\RoomRepository;

class GetRoomAction
{
    protected array $filterableColumns = ['name'];
    protected array $searchableColumns = ['name'];
    protected array $allowedSortColumns = ['name'];

    public function __construct(
        protected RoomRepository $roomRepository,
        protected QueryBuilderHelper $queryBuilderHelper
    ) {
        //
    }

    public function execute(Request $request)
    {
        $query = $this->roomRepository->getAll($request);

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
