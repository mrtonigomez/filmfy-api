<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\DB;

class MoviesService
{

    public function executeStoreMoviesEntities($rq) {
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

    public function deleteEntitiesMovies($movies_id) {
        DB::table("entities_movies")
            ->where("movies_id", "=", $movies_id)
            ->delete();
    }

}
