<?php

namespace App\Services;

use App\Services\BaseService;
use App\Actions\Facilities\GetOptionAction;
use App\Actions\Facilities\GetFacilityAction;
use App\Actions\Facilities\CreateFacilityAction;
use App\Actions\Facilities\DeleteFacilityAction;
use App\Actions\Facilities\UpdateFacilityAction;
use App\Actions\Facilities\GetDetailFacilityAction;

class FacilityService extends BaseService
{
    protected GetOptionAction $getOptionAction;

    public function __construct(
        GetFacilityAction $getFacilityAction,
        CreateFacilityAction $createAction,
        UpdateFacilityAction $updateAction,
        DeleteFacilityAction $deleteAction,
        GetOptionAction $getOptionAction,
        GetDetailFacilityAction $detailAction
    ) {
        parent::__construct('facilities', $getFacilityAction, $detailAction, $getOptionAction, $createAction, $updateAction, $deleteAction);
    }
}
