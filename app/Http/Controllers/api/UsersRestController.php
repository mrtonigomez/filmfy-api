<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersRestController extends Controller
{

    public function index() : Collection
    {
        $users = User::all();
        return $users;
    }

    public function store(Request $request)
    {
        $user = [
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
        ];

        DB::table("users")->insert($user);
        return $user;
    }

    public function show($id)
    {
        $user = User::find($id);
        return $user;
    }

    public function update(Request $request)
    {
        return DB::table('users')
            ->where('id','=', $request->id)
            ->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
    }


    public function destroy($id)
    {
        //
    }
}
