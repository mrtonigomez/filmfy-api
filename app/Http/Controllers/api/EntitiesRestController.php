<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Entities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntitiesRestController extends Controller
{
    public function index() {
        return DB::table("entities")
            ->get();
    }
}
