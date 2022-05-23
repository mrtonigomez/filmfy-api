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
    public function store(Request $request)
    {
        //
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

    public function recentComments()
    {
        $comments = DB::table("comments")
            ->select('m.id','m.title','m.release_date', 'm.image','c.*')
            ->leftJoin('movies as m','m.id' , '=', 'c.movies_id')
            ->orderBy("c.updated_at", "DESC")
            ->limit(5)
            ->get();

        return $comments;
    }

    public function movieComments($id)
    {
        $movie_comments = DB::table('comments as c')
            ->leftJoin('movies as m','m.id' , '=', 'c.movies_id')
            ->where('m.id', '=', $id)->get();

        return $movie_comments;
    }

}
