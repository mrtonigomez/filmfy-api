<?php

namespace App\Http\Services;

use App\Models\Entities;
use App\Models\Likes;
use App\Models\Movies;
use Illuminate\Support\Facades\DB;

class MoviesService
{

    public function getAllMoviesData()
    {
        $movies = Movies::with(["comment", "entities", "category"])
            ->withCount("likes")
            ->get();

        return $movies->map(function ($item) {
            return [
                "id" => $item->id,
                "title" => $item->title,
                "description" => $item->description,
                "release_date" => $item->release_date,
                "image" => $item->image,
                "runtime" => $item->runtime,
                "status" => $item->status,
                "trailer" => $item->trailer,
                "categories" => $item->category->pluck("name"),
                "actors" => $item->entities->where("roles_id", 1)->pluck("name"),
                "directors" => $item->entities->where("roles_id", 2)->pluck("name"),
                "writters" => $item->entities->where("roles_id", 3)->pluck("name"),
                "comments" => $item->comment,
                "likes" => $item->likes_count,
            ];
        });
    }

    public function onlyAllMovies()
    {
        return DB::table("movies")
            ->select("*")
            ->get();
    }

    public function findMoviesById($id)
    {
        $movie = Movies::with(["comment", "entities", "category"])
            ->withCount("likes")
            ->find($id);

        return [
            "id" => $movie->id,
            "title" => $movie->title,
            "description" => $movie->description,
            "release_date" => $movie->release_date,
            "image" => $movie->image,
            "runtime" => $movie->runtime,
            "status" => $movie->status,
            "trailer" => $movie->trailer,
            "categories" => $movie->category->pluck("name"),
            "actors" => $movie->entities->where("roles_id", 1)->pluck("name"),
            "directors" => $movie->entities->where("roles_id", 2)->pluck("name"),
            "writters" => $movie->entities->where("roles_id", 3)->pluck("name"),
            "comments" => $movie->comment,
            "likes" => $movie->likes_count,
        ];
    }

    public function moviesActor($id)
    {
        $actor = Entities::with(["movies"])->find($id);

        if ($actor !== null) {
            if (count($actor->movies) !== 0) {
                return [
                    "name" => $actor->name,
                    "movies" => $actor->movies
                ];
            } else {
                return response()->json("No se encontraron peliculas para este actor", 404);
            }
        } else {
            return response()->json("Actor no encontrado", 404);
        }
    }

    public function findLikesMovieById($id)
    {
        $movies_likes = Movies::withCount(["likes"])->find($id);
        return response()->json(["number_likes" => $movies_likes->likes_count]);
    }

    public function storeLikesInMovies($request)
    {
        $recordId = Likes::insertGetId([
            "users_id" => $request->users_id
        ]);

        $like = Likes::find($recordId);

        if ($like !== null) {
            $movie = Movies::find($request->movies_id);
            $like->likeable()->associate($movie)->save();
            return response()->json("Insert correcto", 200);
        } else {
            return response()->json("Sucedió algún error al tratar de insertar", 404);
        }
    }

    public function checkUserLikedMovie($rq)
    {
        $exist = DB::table("likes")
            ->where("likeable_type", "=", "App\Models\Movies")
            ->where("likeable_id", "=", $rq->movies_id)
            ->where("users_id", "=", $rq->users_id)
            ->get();

        return $exist;
    }

    public function bestMovies()
    {
        $movies_likes = DB::table("movies as m")
            ->select("m.id", "m.title", "m.description", "m.image", DB::raw("count(l.id) as likes"))
            ->join("likes as l", "l.likeable_id", "=", "m.id", "left")
            ->where("l.likeable_type", "=", "App\Models\Movies")
            ->orderBy("likes", "DESC")
            ->groupBy('m.id', "m.title", "m.description", "m.image")
            ->limit(15)
            ->get();

        return $movies_likes;
    }

    public function findMoviesByParam(\Illuminate\Http\Request $request)
    {
        $parameterToFind = "%" . $request->parameter . "%";

        $movies = DB::table("movies")
            ->where("title", "like", $parameterToFind)
            ->get();

        if ($movies->isEmpty()) {
            return response()->json("Pelicula no encontrada", 400);
        } else {
            return $movies;
        }
    }

    public function moviesWithCategory($category_id)
    {
        $movies = Movies::whereHas("category", function ($query) use ($category_id) {
            $query->where("categories_id", $category_id);
        })
            ->withCount("likes")
            ->get();

        if ($movies->isEmpty()) {
            return response()->json("No se encontraron peliculas con esta categoría");
        }

        return $movies->map(function ($movie) {
            return [
                "id" => $movie->id,
                "title" => $movie->title,
                "description" => $movie->description,
                "image" => $movie->image,
                "likes" => $movie->likes_count,
            ];
        });
    }

    public function moviesOnMoreLists()
    {
        /*$response = [];
        $movies = Movies::withCount(["likes", "list"])
            ->orderBy("list_count", "DESC")
            ->limit(10)
            ->get();

        foreach ($movies as $movie) {
            $response[] = [
                "id" => $movie->id,
                "title" => $movie->title,
                "likes" => $movie->likes_count,
                "image" => $movie->image,
                "times_added" => $movie->list_count
            ];
        }

        return $response;*/

        $movies = Movies::withCount(["likes", "list"])
            ->orderBy("list_count", "DESC")
            ->limit(10)
            ->get();

        return $movies->map(function ($movie) {
            return [
                "id" => $movie->id,
                "title" => $movie->title,
                "likes" => $movie->likes_count,
                "image" => $movie->image,
                "times_added" => $movie->list_count
            ];
        });
    }

    public function moviesYear($year)
    {
        /*$response = [];
        for ($i = $year + 1; $i <= $year + 10; $i++) {
            $movies = DB::table("movies as m")
                ->select("m.title", "m.id", "m.description", "m.image", "m.release_date", DB::raw("count(l.id) as likes"))
                ->join("likes as l", "l.likeable_id", "=", "m.id", "left")
                ->where("l.likeable_type", "=", "App\Models\Movies")
                ->where("release_date", "like", "%" . $i . "%")
                ->groupBy('m.id', "m.title", "m.description", "m.image", "m.release_date")
                ->get();

            foreach ($movies as $movie) {
                $response[] = $movie;
            }
        }

        return $response;*/

        $movies = DB::table("movies as m")
            ->select("m.title", "m.id", "m.description", "m.image", "m.release_date", DB::raw("count(l.id) as likes"))
            ->join("likes as l", "l.likeable_id", "=", "m.id", "left")
            ->where("l.likeable_type", "=", "App\Models\Movies")
            ->whereYear("release_date", ">", $year)
            ->whereYear("release_date", "<", $year + 11)
            ->groupBy('m.id', "m.title", "m.description", "m.image", "m.release_date")
            ->orderBy("release_date", "asc")
            ->get();

        if ($movies->isEmpty()) {
            return response()->json("No se encontraron peliculas en esta decada");
        }

        return $movies;
    }

    public function recentMovies()
    {
        return DB::table("movies as m")
            ->select("m.title", "m.id", "m.description", "m.image", "m.release_date", DB::raw("count(l.id) as likes"))
            ->join("likes as l", "l.likeable_id", "=", "m.id", "left")
            ->where("l.likeable_type", "=", "App\Models\Movies")
            ->orderBy("release_date", "DESC")
            ->groupBy('m.id', "m.title", "m.description", "m.image", "m.release_date")
            ->limit(15)
            ->get();
    }

    public function upcommingMovies()
    {
        $today = date("Y-m-d");

        $moviesAll = DB::table("movies as m")
            ->select("m.title", "m.id", "m.release_date", "m.description", "m.image", DB::raw("count(l.id) as likes"))
            ->join("likes as l", "l.likeable_id", "=", "m.id", "left")
            ->where("l.likeable_type", "=", "App\Models\Movies")
            ->where("m.release_date", ">", $today)
            ->orderBy("release_date", "ASC")
            ->groupBy('m.id', "m.title", "m.description", "m.image", "m.release_date")
            ->limit(15)
            ->get();

        if ($moviesAll->isEmpty()) {
            return response()->json("No se encontraron proximos estrenos");
        }

        return $moviesAll;
    }

    //Crud controller
    public function executeStoreMoviesEntities($rq)
    {
        $movieId = DB::table("movies")
            ->select("id")
            ->where("title", $rq->title)
            ->value("id");

        $this->deleteEntitiesMovies($rq->id);
        $this->storeMoviesEntities($rq, $movieId);
    }

    public function storeMoviesEntities($rq, $movies_id)
    {
        if ($rq->entitiesActors !== null) {
            foreach ($rq->entitiesActors as $entity) {
                $data = [
                    "entities_id" => $entity,
                    "movies_id" => $movies_id,
                ];
                DB::table("entities_movies")
                    ->insert($data);
            }
        }

        if ($rq->entitiesWritters !== null) {
            foreach ($rq->entitiesWritters as $entity) {
                $data = [
                    "entities_id" => $entity,
                    "movies_id" => $movies_id,
                ];
                DB::table("entities_movies")
                    ->insert($data);
            }
        }

        if ($rq->entitiesDirectors !== null) {
            foreach ($rq->entitiesDirectors as $entity) {
                $data = [
                    "entities_id" => $entity,
                    "movies_id" => $movies_id,
                ];
                DB::table("entities_movies")
                    ->insert($data);
            }
        }
    }

    public function deleteEntitiesMovies($movies_id)
    {
        DB::table("entities_movies")
            ->where("movies_id", "=", $movies_id)
            ->delete();
    }
}
