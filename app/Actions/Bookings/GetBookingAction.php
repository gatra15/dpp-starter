<?php

namespace App\Actions\Bookings;

use App\Repositories\BookingRepository;
use App\Actions\Helper\QueryBuilderHelper;
use Illuminate\Http\Request;

class GetBookingAction
{
    protected array $filterableColumns = ['user_id', 'room_id', 'status_id', 'start_time', 'end_time'];
    protected array $searchableColumns = ['title'];
    protected array $allowedSortColumns = ['id', 'user_id', 'room_id', 'status_id', 'start_time', 'end_time'];

    public function __construct(
        protected BookingRepository $bookingRepository,
        protected QueryBuilderHelper $queryBuilderHelper
    ) {
    }

    public function execute(Request $request)
    {
        $query = $this->bookingRepository->getAll();

        if ($request->boolean('with_relations')) {
            $query->with(['user', 'room', 'status']);
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