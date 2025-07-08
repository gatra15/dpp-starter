<?php

namespace App\Services;

use App\Services\BaseService;
use App\Actions\Facilities\GetFacilityAction;
use App\Actions\Facilities\CreateFacilityAction;
use App\Actions\Facilities\DeleteFacilityAction;
use App\Actions\Facilities\UpdateFacilityAction;

class FacilityService extends BaseService
{
    public function __construct(
        GetFacilityAction $getFacilityAction,
        CreateFacilityAction $createAction,
        UpdateFacilityAction $updateAction,
        DeleteFacilityAction $deleteAction
    ) {
        parent::__construct('facilities', $getFacilityAction, null, $createAction, $updateAction, $deleteAction);
    }
}
