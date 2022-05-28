<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Lists;
use App\Models\Movies;
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
        return Lists::all();
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

        $user_lists = [
            "id" => $list["id"],
            "users_id" => $list["users_id"],
            "title" => $list["title"],
            "description" => $list["description"],
            "is_private" => $list["is_private"],
            "status" => $list["status"],
            "movies" => $list->movies
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
        $list = Lists::find($id);
        $list->status = 0;
        return $list->save();

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

    public function addMoviesToList(Request $request) {

        $data = [
            "lists_id" => $request->lists_id,
            "movies_id" => $request->movies_id
        ];

        $moviesInList = Lists::find($request->lists_id)->movies;

        foreach ($moviesInList as $movie) {
            if ($movie->id == $request->movies_id){
                return "La película ya se ha introducido";
            }
        }

        DB::table("lists_movies")
            ->insert($data);

        return "Insert correcto";

    }

    public function recentLists() {

        $recentLists = [];

        $listsAll = DB::table("lists as l")
            ->select('l.id as l_id',  'l.title as l_title', 'u.name as u_name', DB::raw('COUNT(ll.id) as l_likes') )
            ->leftJoin('lists_movies as lm','lm.lists_id' , '=', 'l.id')
            ->leftJoin('users as u', 'u.id','=', 'l.users_id')
            ->leftJoin('lists_likes as ll', 'll.lists_id', '=', 'l.id')
            ->groupBy('l.id', 'l.title', 'u.name')
            ->orderBy("l.updated_at", "DESC")
            ->limit(5)
            ->get();

        foreach ($listsAll as $key => $list) {

            $m_imgs = Lists::moviesInformation($list->l_id)[0];
            $m_count = Lists::moviesInformation($list->l_id)[1];
            $list->m_imgs = $m_imgs;
            $list->m_count = $m_count;
            array_push($recentLists, $list);
        }

        return $recentLists;
    }

}
