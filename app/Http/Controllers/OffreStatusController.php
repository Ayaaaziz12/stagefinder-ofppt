<?php

namespace App\Http\Controllers;

use App\Models\Offrestatus;
use Illuminate\Http\Request;

class OffreStatusController extends Controller
{
    public function index()
    {
        $statuses = Offrestatus::select('id', 'Libelle')->get();
        return response()->json($statuses);
    }
}
