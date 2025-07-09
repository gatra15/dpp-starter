<?php

namespace App\Actions\Rooms;

use Illuminate\Http\Request;
use App\Actions\Helper\QueryBuilderHelper;
use App\Repositories\RoomRepository;

class GetRoomAction
{
    protected array $filterableColumns = ['capacity', 'available'];
    protected array $searchableColumns = ['name'];
    protected array $allowedSortColumns = ['id', 'name', 'capacity', 'available'];

    public function __construct(
        protected RoomRepository $roomRepository,
        protected QueryBuilderHelper $queryBuilderHelper
    ) {
        //
    }

    public function execute(Request $request)
    {
        $query = $this->roomRepository->getAll($request);
        
        if ($request->boolean('with_facilities')) {
            $query->with('facilities');
        }

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
