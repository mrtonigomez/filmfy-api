<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Lists;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ListsRestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $list = Lists::find($id);

        $moviesList = DB::table("movies as m")
            ->join("lists_movies as lm", "lm.movies_id", "=", "m.id")
            ->where("lists_id", "=", $list["id"])
            ->get();

        $user_lists = [
            "id" => $list["id"],
            "title" => $list["title"],
            "movies" => $moviesList
        ];

        return $user_lists;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function moviesFromList($idList)
    {
        $lists_users = DB::table("lists_movies as lm")
            ->select("m.*")
            ->join("lists as l", "l.id", "=", "lm.lists_id")
            ->join("movies as m", "m.id", "=", "lm.movies_id")
            ->where("lm.lists_id", "=", $idList)
            ->get();

        return $lists_users;
    }

    public function userLists($idUser)
    {
        $lists = Lists::all();

        foreach ($lists->toArray() as $key => $list) {

            $moviesList = DB::table("movies as m")
                ->join("lists_movies as lm", "lm.movies_id", "=", "m.id")
                ->where("lists_id", "=", $list["id"])
                ->get();

            if ($list["users_id"] == $idUser) {
                $user_lists[$key] = [
                    "id" => $list["id"],
                    "title" => $list["title"],
                    "movies" => $moviesList
                ];
            }
        }
        return $user_lists;
    }
}
