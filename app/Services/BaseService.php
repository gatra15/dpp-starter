<?php

namespace App\Services;

use App\Actions\LogAction;
use ErrorException;

class BaseService
{
    protected $module;
    protected $getAction;
    protected $detailAction;
    protected $createAction;
    protected $updateAction;
    protected $deleteAction;
    protected $user_id;
    
    public function __construct($module, $getAction, $detailAction, $createAction, $updateAction, $deleteAction)
    {
        $this->module = $module;
        $this->getAction = $getAction;
        $this->detailAction = $detailAction;
        $this->createAction = $createAction;
        $this->updateAction = $updateAction;
        $this->deleteAction = $deleteAction;
        $this->user_id = auth()->user()->id;
    }

    public function getAll($request)
    {
        $data = $this->getAction->execute($request);
        return [
            'status' => true,
            'message' => 'Successful',
            'data' => $data,
        ];
    }
    
    public function getDetail($id)
    {
        $data = $this->detailAction->execute($id);
        if (empty($data)) {
            throw new ErrorException("Data not found");
        }

        return [
            'status' => true,
            'message' => 'Successful',
            'data' => $data,
        ];
    }

    public function create($request)
    {
        $data = $this->createAction->execute($request);
        
        $this->log('create', $this->user_id, $data->id);

        return [
            'status' => true,
            'message' => 'Data successfully created',
            'data' => $data,

        ];

    }
    
    public function update($id, $request)
    {
        $data = $this->updateAction->execute($id, $request);
        $this->log('update', $this->user_id, $id);

        return [
            'status' => true,
            'message' => 'Data successfully updated',
            'data' => $data,
        ];
    }

    public function delete($id)
    {
        $data = $this->deleteAction->execute($id);
        
        $this->log('delete', $this->user_id, $id);

        return [
            'status' => true,
            'message' => 'Data successfully deleted',
        ];
    }

    public function log($action, $user_id, $model_id)
    {
        $data = [
            'model' => $this->module,
            'model_id' => $model_id,
            'action' => $action,
            'user_id' => $user_id,
        ];

        $this(LogAction::class)->execute($data);
    }
}
