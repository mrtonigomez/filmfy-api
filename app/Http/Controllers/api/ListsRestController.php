<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Lists;
use App\Models\Movies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ListsRestController extends Controller
{

    public function index()
    {
        $lists = Lists::with(["comment", "users", "movies", "likes"])
            ->withCount("movies")
            ->orderBy("updated_at", "DESC")
            ->get();

        $response = [];
        foreach ($lists as $list) {
            $response[] = [
                "l_id" => $list->id,
                "l_title" => $list->title,
                "l_likes" => $list->likes->count(),
                "user" => [
                    "name" => $list->users->name,
                    "profile_image" => $list->users->profile_image
                ],
                "movies_count" => $list->movies_count,
                "movies" => $list->movies,
                "comments" => $list->comment
            ];
        }

        return $response;
    }

    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        $list = Lists::with(["movies.category", "comment", "users"])
            ->withCount("likes")
            ->withCount("movies")
            ->find($id);

        $response = [
            "id" => $list->id,
            "title" => $list->title,
            "description" => $list->description,
            "is_private" => $list->is_private,
            "user" => [
                "name" => $list->users->name,
                "profile_image" => $list->users->profile_image
            ],
            "status" => $list->status,
            "likes" => $list->likes_count,
            "created_at" => $list->created_at,
            "updated_at" => $list->updated_at,
            "movies_count" => $list->movies_count,
            "comments" => $list->comment,
            //TODO:Return an array of categories inse the movies object
            "movies" => $list->movies,
        ];

        return $response;
    }

    public function update(Request $request, $id)
    {
        //
    }

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
        /*$lists = Lists::all();
        $user_lists = [];

        foreach ($lists->toArray() as $key => $list) {

            $moviesList = DB::table("movies as m")
                ->join("lists_movies as lm", "lm.movies_id", "=", "m.id")
                ->where("lists_id", "=", $list["id"])
                ->get();


            if ($list["users_id"] == $idUser && $list["status"] !== 0) {
                $m_count = Lists::moviesInformation($list["id"]);
                $likes = Lists::listLikes($list["id"]);
                $listUser = [
                    "id" => $list["id"],
                    "title" => $list["title"],
                    "list_likes" => $likes->count,
                    "movies_count" => $m_count[1],
                    "movies" => $moviesList,
                ];
                $user_lists[] = $listUser;
            }
        }
        return $user_lists;*/

        $lists = Lists::with("movies", "likes", "users", "comment")
            ->where("users_id", "=", $idUser)
            ->withCount("movies")
            ->withCount("likes")
            ->get();

        $response = [];

        foreach ($lists as $list) {
            $response[] = [
                "id" => $list->id,
                "title" => $list->title,
                "list_likes" => $list->likes_count,
                "movies_count" => $list->movies_count,
                "movies" => $list->movies,
                "comment" => $list->comment
            ];
        }

        return $response;
    }

    public function createList(Request $request)
    {

        $dataList = [
            "users_id" => $request->users_id,
            "title" => $request->title,
            "description" => $request->description,
            "is_private" => 0,
            "status" => 1,
        ];

        DB::table("lists")->insert($dataList);
        $list = DB::table("lists")
            ->select("id")
            ->where("title", $request->title)
            ->get();

        foreach ($request->movies as $movie) {
            $dataMovie = [
                "lists_id" => $list[0]->id,
                "movies_id" => $movie["id"]
            ];
            DB::table("lists_movies")->insert($dataMovie);
        }

    }

    public function updateList(Request $request)
    {
        $dataList = [
            "users_id" => $request->users_id,
            "title" => $request->title,
            "description" => $request->description,
            "is_private" => 0,
            "status" => 1,
        ];

        DB::table("lists")
            ->where("id", "=", $request->id)
            ->update($dataList);

        $list = Lists::find($request->id);

        $moviesList = DB::table("lists_movies")
            ->select("*")
            ->where("lists_id", "=", $request->id)
            ->delete();

        foreach ($request->movies as $movie) {
            $dataMovie = [
                "lists_id" => $list["id"],
                "movies_id" => $movie["id"]
            ];
            DB::table("lists_movies")->insert($dataMovie);
        }
    }

    public function addMoviesToList(Request $request)
    {
        $data = [
            "lists_id" => $request->lists_id,
            "movies_id" => $request->movies_id
        ];

        $moviesInList = Lists::find($request->lists_id)->movies;

        foreach ($moviesInList as $movie) {
            if ($movie->id == $request->movies_id) {
                return "La película ya se ha introducido";
            }
        }

        DB::table("lists_movies")
            ->insert($data);

        return "Insert correcto";
    }

    public function recentLists()
    {
        /*$recentLists = [];

        $listsAll = DB::table("lists as l")
            ->select('l.id as l_id', 'l.title as l_title', 'l.description as l_description', DB::raw('COUNT(ll.id) as l_likes'))
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

        return $recentLists;*/

        $lists = Lists::with(["users", "movies", "likes"])
            ->withCount("movies", "likes")
            ->orderBy("updated_at", "DESC")
            ->limit(10)
            ->get();

        $response = [];

        foreach ($lists as $key => $list) {
            $response[$key] = [
                "l_id" => $list->id,
                "l_title" => $list->title,
                "l_description" => $list->description,
                "l_likes" => $list->likes_count,
                "user" => [
                    "name" => $list->users->name,
                    "profile_image" => $list->users->profile_image
                ],
                "movies_count" => $list->movies_count,
                "movies" => $list->movies,
            ];
        }

        return $response;
    }

    public function mostLikedLists()
    {

        /*$mostLikedLists = [];

        $listsAll = DB::table("lists as l")
            ->select('l.id as l_id', 'l.title as l_title', 'l.description as l_description', DB::raw('COUNT(ll.id) as l_likes'))
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

        return $mostLikedLists;*/

        /*$lists = Lists::with([
            "users",
            "movies",
            "likes"])
            ->limit(10)
            ->get();

        $listsAll = DB::table("lists as l")
            ->select('l.id as l_id', 'l.title as l_title', 'l.description as l_description', DB::raw('COUNT(ll.id) as l_likes'))
            ->leftJoin('lists_likes as ll', 'll.lists_id', '=', 'l.id')
            ->groupBy('l.id', 'l.title', 'l.description')
            ->orderBy("l_likes", "DESC")
            ->limit(10)
            ->get();*/

        /*$lists = Lists::select('lists.*', DB::raw('COUNT(ll.id) as l_likes'))
                ->leftJoin('lists_likes as ll', 'll.lists_id', '=', 'lists.id')
                ->groupBy('lists.id', 'lists.title', 'lists.description', "lists.users_id", "lists.is_private", "lists.status", "lists.created_at", "lists.updated_at")
                ->orderBy("l_likes", "DESC")
                ->limit(10)
                ->get();*/

        $lists = Lists::with(["users", "movies.category", "likes"])
            ->withCount(["likes", "movies"])
            ->orderBy("likes_count", "desc")
        ->get();

        $response = [];

        foreach ($lists as  $list) {
            $response[] = [
                "l_id" => $list->id,
                "l_title" => $list->title,
                "l_description" => $list->description,
                "l_likes" => $list->likes_count,
                "user" => [
                    "name" => $list->users->name,
                    "profile_image" => $list->users->profile_image
                ],
                "movies_count" => $list->movies_count,
                "movies" => $list->movies,
            ];
        }
        return $response;
    }

}
