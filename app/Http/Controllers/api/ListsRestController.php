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
     * @return array
     */
    public function index()
    {
        $allLists = [];

        $listsAll = DB::table("lists as l")
            ->select('l.id as l_id',  'l.title as l_title', DB::raw('COUNT(ll.id) as l_likes') )
            ->leftJoin('lists_likes as ll', 'll.lists_id', '=', 'l.id')
            ->groupBy('l.id', 'l.title')
            ->orderBy("l.updated_at", "DESC")
            ->get();

        foreach ($listsAll as $key => $list) {

            $movies = Lists::moviesInformation($list->l_id)[0];
            $m_count = Lists::moviesInformation($list->l_id)[1];
            $user = Lists::userInformation($list->l_id);

            $list->user = $user;
            $list->movies_count = $m_count;
            $list->movies = $movies;

            foreach ($list->movies as $movie) {
                $m_categories = Movies::returnExtraInformation($movie->id);
                $movie["categories"] = $m_categories["categories"];
            }

            array_push($allLists, $list);
        }

        return $allLists;
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

        $movies = Lists::moviesInformation($list->id)[0];
        $m_count = Lists::moviesInformation($list->id)[1];
        $user = Lists::userInformation($list->id);


        foreach ($list->movies as $movie) {
            $m_categories = Movies::returnExtraInformation($movie->id);
            $movie["categories"] = $m_categories["categories"];
        }

        $user_lists = [
            "id" => $list["id"],
            "title" => $list["title"],
            "description" => $list["description"],
            "is_private" => $list["is_private"],
            "user" => $user,
            "status" => $list["status"],
            "movies_count" => $m_count,
            "movies" => $movies,
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
        $user_lists = [];

        foreach ($lists->toArray() as $key => $list) {

            $moviesList = DB::table("movies as m")
                ->select("m.*")
                ->join("lists_movies as lm", "lm.movies_id", "=", "m.id")
                ->where("lists_id", "=", $list["id"])
                ->get();

            if ($list["users_id"] == $idUser) {
                $listUser = [
                    "id" => $list["id"],
                    "title" => $list["title"],
                    "movies" => $moviesList
                ];
                array_push($user_lists, $listUser);
            }
        }
        return $user_lists;
    }

    public function createList(Request $request) {

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
            ->select('l.id as l_id',  'l.title as l_title', 'l.description as l_description', DB::raw('COUNT(ll.id) as l_likes') )
            ->leftJoin('lists_likes as ll', 'll.lists_id', '=', 'l.id')
            ->groupBy('l.id', 'l.title', 'l.description')
            ->orderBy("l.updated_at", "DESC")
            ->limit(10)
            ->get();

        foreach ($listsAll as $key => $list) {

            $movies = Lists::moviesInformation($list->l_id)[0];
            $m_count = Lists::moviesInformation($list->l_id)[1];
            $user = Lists::userInformation($list->l_id);

            $list->user = $user;
            $list->movies_count = $m_count;
            $list->movies = $movies;

            foreach ($list->movies as $movie) {
                $m_categories = Movies::returnExtraInformation($movie->id);
                $movie["categories"] = $m_categories["categories"];
            }

            array_push($recentLists, $list);
        }

        return $recentLists;
    }

    public function mostLikedLists() {

        $mostLikedLists = [];

        $listsAll = DB::table("lists as l")
            ->select('l.id as l_id',  'l.title as l_title', 'l.description as l_description', DB::raw('COUNT(ll.id) as l_likes') )
            ->leftJoin('lists_likes as ll', 'll.lists_id', '=', 'l.id')
            ->groupBy('l.id', 'l.title', 'l.description')
            ->orderBy("l_likes", "DESC")
            ->limit(10)
            ->get();

        foreach ($listsAll as $key => $list) {

            $movies = Lists::moviesInformation($list->l_id)[0];
            $m_count = Lists::moviesInformation($list->l_id)[1];
            $user = Lists::userInformation($list->l_id);

            $list->user = $user;
            $list->movies_count = $m_count;
            $list->movies = $movies;

            foreach ($list->movies as $movie) {
                $m_categories = Movies::returnExtraInformation($movie->id);
                $movie["categories"] = $m_categories["categories"];
            }

            array_push($mostLikedLists, $list);
        }

        return $mostLikedLists;
    }

}
