<?php

namespace App\Services;

use App\Services\BaseService;
use App\Actions\Facilities\GetOptionAction;
use App\Actions\Facilities\GetFacilityAction;
use App\Actions\Facilities\CreateFacilityAction;
use App\Actions\Facilities\DeleteFacilityAction;
use App\Actions\Facilities\UpdateFacilityAction;

class FacilityService extends BaseService
{
    protected GetOptionAction $getOptionAction;

    public function __construct(
        GetFacilityAction $getFacilityAction,
        CreateFacilityAction $createAction,
        UpdateFacilityAction $updateAction,
        DeleteFacilityAction $deleteAction,
        GetOptionAction $getOptionAction
    ) {
        parent::__construct('facilities', $getFacilityAction, null, $createAction, $updateAction, $deleteAction);

        $this->getOptionAction = $getOptionAction;
    }

    public function getOptions()
    {
        return $this->getOptionAction->execute();
    }
}
