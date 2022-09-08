<?php

namespace Database\Seeders;

use App\Models\Comments;
use App\Models\Lists;
use App\Models\Movies;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $commentsMovies = [
            [
                'users_id' => 1,
                'title' => 'Una película increíble',
                'body' => 'El suspense de esta historía te pone la piel de gallina. El reparto es inmejorable',
                'rating' => 8,
                'likes' => 51
            ],
            [
                'users_id' => 3,
                'title' => 'Fantastica lista claro que si',
                'body' => 'Estoy muy de acuerdo con todas las películas icnluidas. La verdad es que contamos con el mismo gusto para el buen cine',
                'rating' => 10,
                'likes' => 2222
            ],
            [
                'users_id' => 1,
                'title' => 'No me canso de verla',
                'body' => '¡Volver al futuro a ver esta película una y otra vez! La película lo inicio todo y envejece perfectamente como un clásico de la comedia y la ciencia ficción, todo gracias a unos efectos especiales chispeantes, un puñado de lineás y diálogos fenomenales',
                'rating' => 9,
                'likes' => 29
            ],
            [
                'users_id' => 2,
                'title' => 'No estoy muy de acuerdo',
                'body' => 'Bajo mi punto de vista y partiendo de que respeto tu lista, hay algunas que me parecen bastante flojas como para ponerlas aquí. Desprenden bastante mediocridad',
                'rating' => 4,
                'likes' => 3
            ],
            [
                'users_id' => 1,
                'title' => 'Es que no puedo...',
                'body' => 'Desde que vi el deporte ese de la peña persiguiendo una pelota en escoba... es que no puedo con ello más. Horrible como siempre el Harry',
                'rating' => 1,
                'likes' => 0
            ]
        ];

        DB::table("comments")
            ->insert($commentsMovies);

        $movies = Movies::all();
        $lists = Lists::all();
        $comments = Comments::all();

        $i = 0;
        foreach ($comments as $comment) {
            ($i % 2 === 0)
                ? $comment->commentable()->associate(Movies::find(rand(1, count($movies))))->save()
                : $comment->commentable()->associate(Lists::find(rand(1, count($lists))))->save();
            $i++;
        }


    }
}
