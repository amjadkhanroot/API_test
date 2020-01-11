<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Comment;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comments = auth()->user()->comments()->with('task')->paginate(100);
        return CommentResource::collection($comments);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validateData = $request-> validate([
            'content' => 'required',
            'task_id'=> 'required'
        ]);

        $task = Task::findOrFail($request->task_id);

        if(auth()->id() != $task->user_id){
            return response()->json(['message'=>'You Don\'t own this Task!'], '401');
        }

        $request['user_id'] = auth()->id();

        return auth()->user()->comments()->create($request->all());

    }

    /**
     * Display the specified resource.
     *
     * @param Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        if(Auth::id() != $comment->user_id){
            return response()->json(['message'=>'You Don\'t own this comment!'], '401');
        }

        return new CommentResource($comment->load('task'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        $validateData = $request-> validate([
            'content' => 'required'
        ]);

        if(auth()->id() != $comment->user_id){
            return response()->json(['message'=>'You Don\'t own this comment!'], '401');
        }

        if($comment->update($request->all())){
            return response()->json(['message'=>'successfully Updated!']);
        }

        return response()->json(['message'=>'Please Try later!'], 500);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Comment $comment
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Comment $comment)
    {

        if(Auth::id() != $comment->user_id){
            return response()->json(['message'=>'You Don\'t own this comment!'], '401');
        }

        if($comment->delete()){
            return response()->json(['message'=>'successfully Deleted!']);
        }

    }
}
