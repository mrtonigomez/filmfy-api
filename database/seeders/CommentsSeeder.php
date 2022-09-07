<?php

namespace Database\Seeders;

use App\Models\Comments;
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
        $comments = [
            [
                'users_id' => 1,
                'title' => 'Una película increíble',
                'body' => 'El suspense de esta historía te pone la piel de gallina. El reparto es inmejorable',
                'rating' => 8,
                'likes' => 51
            ],
            [
                'users_id' => 3,
                'title' => 'Una película guapísima',
                'body' => 'Me lo paso muy bien cada vez que la veo. De mis preferidas',
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
                'title' => 'Esperaba más',
                'body' => 'La fórmula de Disney repetida una vez más sin aportar frescura o novedades a un producto que tienen muy explotado',
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
            ->insert($comments);

        foreach (Comments::all() as $comment_loop) {
            $comment_loop->commentable()->associate(Movies::find(rand(1, 390)))->save();
        }

    }
}
