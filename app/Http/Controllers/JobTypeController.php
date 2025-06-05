<?php

namespace App\Http\Controllers;

use App\Models\Jobtype;
use Illuminate\Http\Request;

class JobTypeController extends Controller
{
    public function index()
    {
        $jobTypes = Jobtype::select('id', 'Libelle')->get();
        return response()->json($jobTypes);
    }
}
