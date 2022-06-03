<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Comments;
use App\Models\Lists;
use App\Models\Movies;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommentsRestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Comments::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $movie_id)
    {

        $new_comment = DB::table('comments')
            ->insert([
                'movies_id' => $movie_id,
                'users_id' => $request->users_id,
                'title' => $request->title,
                'body' => $request->body,
                'rating' => $request->rating,
                'moderated' => 0,
                'status' => 0,
                'likes' => 0

            ]);

        return $new_comment;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function userComments($user_id)
    {
        $comments = Comments::all();

        foreach ($comments->toArray() as $key => $comment) {

            $movie = DB::table("movies as m")
                ->select('*')
                ->where("id", "=", $comment["movies_id"])
                ->get();


            if ($comment["users_id"] == $user_id) {
                $user_comments[$key] = [
                    "id" => $comment["id"],
                    "title" => $comment["title"],
                    "body" => $comment["body"],
                    "rating" => $comment["rating"],
                    "likes" => $comment["likes"],
                    "movie" => $movie,
                    "created_at" => $comment["created_at"],
                    "updated_at" => $comment["updated_at"],
                ];
            }
        }
        return $user_comments;
    }

    public function recentComments()
    {
        $comments = DB::table("comments as c")
            ->select('m.title as m_title','m.release_date as m_release', 'm.image as m_image', 'c.*', 'u.name as u_name', 'u.profile_image as u_image')
            ->leftJoin('movies as m','m.id' , '=', 'c.movies_id')
            ->leftJoin('users as u','u.id' , '=', 'c.users_id')
            ->orderBy("c.updated_at", "DESC")
            ->limit(5)
            ->get();

        return $comments;
    }

    public function movieComments($id)
    {
        $movie_comments = DB::table('comments as c')
            ->select("m.title as movie ", "c.id", "c.title", "c.body", "c.rating", "c.likes", "m.image", "c.created_at", "u.name as user")
            ->leftJoin('movies as m','m.id' , '=', 'c.movies_id')
            ->join("users as u", "u.id", "=", "c.users_id")
            ->orderBy("c.updated_at", "DESC")
            ->where('m.id', '=', $id)
            ->limit(5)
            ->get();

        return $movie_comments;
    }

    public function commentLike($comment_id) {
        $comment = Comments::find($comment_id);
        $comment->likes++;
        $comment->save();

        return $comment;
    }

    public function userHadComment(Request $request){
        $exist = DB::table("comments")
            ->select("*")
            ->where("movies_id", $request->movie)
            ->where("users_id", $request->user)
            ->count();

        if ($exist) {
            return 1;
        }else {
            return 0;
        }



    }

}
