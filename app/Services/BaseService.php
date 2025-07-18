<?php

namespace App\Services;

use App\Actions\LogAction;
use ErrorException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth; // <-- PASTIKAN INI DI-IMPORT

class BaseService
{
    protected $module;
    protected $getAction;
    protected $detailAction;
    protected $optionAction;
    protected $createAction;
    protected $updateAction;
    protected $deleteAction;

    public function __construct(
        $module,
        $getAction = null,
        $detailAction = null,
        $optionAction = null,
        $createAction = null,
        $updateAction = null,
        $deleteAction = null
    ) {
        $this->module = $module;
        $this->getAction = $getAction;
        $this->detailAction = $detailAction;
        $this->optionAction = $optionAction;
        $this->createAction = $createAction;
        $this->updateAction = $updateAction;
        $this->deleteAction = $deleteAction;
    }

    public function getAll(Request $request)
    {
        if (!$this->getAction) {
            throw new \BadMethodCallException("Aksi daftar tidak diatur untuk layanan {$this->module}.");
        }
        $data = $this->getAction->execute($request);
        return [
            'status' => true,
            'message' => 'Berhasil',
            'data' => $data,
        ];
    }

    public function getDetail(int $id)
    {
        if (!$this->detailAction) {
            throw new \BadMethodCallException("Aksi detail tidak diatur untuk layanan {$this->module}.");
        }
        $data = $this->detailAction->execute($id);
        if (empty($data)) {
            throw new ErrorException("Data tidak ditemukan");
        }

        return [
            'status' => true,
            'message' => 'Berhasil',
            'data' => $data,
        ];
    }

    public function getOptions(): Collection
    {
        if (!$this->optionAction) {
            throw new \BadMethodCallException("Aksi opsi tidak diatur untuk layanan {$this->module}.");
        }
        return $this->optionAction->execute();
    }

    public function create(Request $request)
    {
        if (!$this->createAction) {
            throw new \BadMethodCallException("Aksi buat tidak diatur untuk layanan {$this->module}.");
        }
        $data = $this->createAction->execute($request);

        $user_id = Auth::id();
        if ($user_id) {
            $this->log('create', $user_id, $data->id);
        }

        return [
            'status' => true,
            'message' => 'Data berhasil dibuat',
            'data' => $data,
        ];
    }

    public function update(int $id, Request $request)
    {
        if (!$this->updateAction) {
            throw new \BadMethodCallException("Aksi perbarui tidak diatur untuk layanan {$this->module}.");
        }
        $data = $this->updateAction->execute($id, $request);

        $user_id = Auth::id();
        if ($user_id) {
            $this->log('update', $user_id, $id);
        }

        return [
            'status' => true,
            'message' => 'Data berhasil diperbarui',
            'data' => $data,
        ];
    }

    public function delete(int $id)
    {
        if (!$this->deleteAction) {
            throw new \BadMethodCallException("Aksi hapus tidak diatur untuk layanan {$this->module}.");
        }
        $data = $this->deleteAction->execute($id);

        $user_id = Auth::id();
        if ($user_id) {
            $this->log('delete', $user_id, $id);
        }

        return [
            'status' => true,
            'message' => 'Data berhasil dihapus',
        ];
    }

    public function log(string $action, int $user_id, int $model_id)
    {
        $data = [
            'model' => $this->module,
            'model_id' => $model_id,
            'action' => $action,
            'user_id' => $user_id,
        ];

        (new LogAction)->execute($data);
    }
}
