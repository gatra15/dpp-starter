<?php

namespace App\Http\Controllers;

use App\Models\AuditTrail;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index()
    {
        return response()->json(AuditTrail::orderBy('datetime', 'desc')->get());
    }

    public function show($id)
    {
        $log = AuditTrail::findOrFail($id);
        return response()->json($log);
    }
}
