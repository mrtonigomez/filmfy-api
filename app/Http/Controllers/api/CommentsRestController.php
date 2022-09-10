<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Comments;
use App\Models\Lists;
use App\Models\Movies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentsRestController extends Controller
{

    public function index()
    {
        return DB::table('comments')
            ->select("*")
            ->get();
    }

    public function storeCommentMovie(Request $request, $movie_id)
    {
        $movie = Movies::find($movie_id);
        $new_comment = Comments::create([
            'users_id' => $request->users_id,
            'title' => $request->title,
            'body' => $request->body,
            'rating' => $request->rating,
            'moderated' => 0,
            'status' => 0,
            'likes' => 0

        ]);

        $new_comment->commentable()->associate($movie)->save();
        return $new_comment;

    }

    public function storeCommentList(Request $request, $list_id)
    {
        $list = Lists::find($list_id);
        $new_comment = Comments::create([
            'users_id' => $request->users_id,
            'title' => $request->title,
            'body' => $request->body,
            'rating' => $request->rating,
            'moderated' => 0,
            'status' => 0,
            'likes' => 0

        ]);

        $new_comment->commentable()->associate($list)->save();
        return $new_comment;

    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }


    public function userComments($user_id)
    {
        //whereHas
        $comments = Comments::with(["commentable", "users"])
            ->where("users_id", $user_id)
            ->get();

        $response = [];
        foreach ($comments as $comment) {
            $response[] = [
                "id" => $comment->id,
                "title" => $comment->title,
                "body" => $comment->body,
                "rating" => $comment->rating,
                "likes" => $comment->likes,
                "movie" => $comment->commentable,
                "created_at" => $comment->created_at,
                "updated_at" => $comment->updated_at,
            ];
        }
        return $response;
    }

    public function recentComments()
    {
        $comments = DB::table("comments as c")
            ->select('m.title as m_title', 'm.release_date as m_release', 'm.image as m_image', 'c.id',
                'c.commentable_id as movies_id', 'c.users_id', 'c.title', 'c.body', 'c.rating', 'c.moderated', 'c.status', 'c.likes', 'c.created_at', 'c.updated_at', 'u.name as u_name', 'u.profile_image as u_image')
            ->leftJoin('movies as m', 'm.id', '=', 'c.commentable_id')
            ->leftJoin('users as u', 'u.id', '=', 'c.users_id')
            ->where("commentable_type", "App\Models\Movies")
            ->orderBy("c.updated_at", "DESC")
            ->limit(5)
            ->get();

        return $comments;
    }

    public function movieComments($id)
    {
        $comments = Comments::with(["users", "commentable"])
            ->where("commentable_id", $id)
            ->where("commentable_type", "App\Models\Movies")
            ->orderBy("comments.updated_at", "DESC")
            ->get();


        $response = $comments->map(function ($item) {
            return [
                "id" => $item->id,
                "movie" => $item->commentable->title,
                "title" => $item->title,
                "body" => $item->body,
                "rating" => $item->rating,
                "likes" => $item->likes,
                "image" => $item->commentable->image,
                "created_at" => $item->created_at,
                "user" => $item->users->name,
            ];
        });

        /*$response = [];
        foreach ($comments as $comment) {
            $response[] = [
                "movie" => $comment->commentable->title,
                "id" => $comment->id,
                "title" => $comment->title,
                "body" => $comment->body,
                "rating" => $comment->rating,
                "likes" => $comment->likes,
                "image" => $comment->commentable->image,
                "created_at" => $comment->created_at,
                "user" => $comment->users->name,
            ];
        }*/

        return $response;
    }

    public function commentLike($comment_id)
    {
        $comment = Comments::find($comment_id);
        $comment->likes++;
        $comment->save();

        return $comment;
    }

    public function userHadComment(Request $request)
    {
        $exist = DB::table("comments")
            ->select("*")
            ->where("commentable_id", $request->movie)
            ->where("commentable_type", "App\Models\Movies")
            ->where("users_id", $request->user)
            ->count();

        if ($exist) {
            return $response = [
                "status" => 1,
            ];
        } else {
            return $response = [
                "status" => 0,
            ];
        }
    }
}
