<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        // rate limit
        $this->middleware('throttle:70,1');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $tasks = auth()->user()->tasks()->with('category', 'comments', 'files')->paginate(100);
       return TaskResource::collection($tasks);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validateData = $request-> validate([
            'title' => 'required',
            'category_id'=> 'required',
            'due_date'=> 'required|date|date_format:Y-m-d|after or equal:'.date('Y-m-d')
        ]);

        $category = Category::findOrFail($request->category_id);

        if($category->user_id != auth()->id()){
            return response()->json(['message'=>'You Don\'n own this category!'], '401');
        }

        $request['user_id']= auth()->id();

        if($category->tasks()->create($request->all())){
            return response()->json(['message'=>'Task successfully created!']);
        }


        return response()->json(['message'=>'Please Try later!'], 500);

    }

    /**
     * Display the specified resource.
     *
     * @param Task $task
     * @return Response
     */
    public function show(Task $task)
    {
        if(Auth::id() != $task->user_id ){
            return response()->json(['message'=>'You Don\'n own this task!'], '401');
        }

        return new TaskResource($task->load('category', 'comments', 'files'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Task $task
     * @return void
     */
    public function update(Request $request, Task $task)
    {
        $validateData = $request-> validate([
            'title' => 'required',
            'category_id'=> 'required',
            'due_date'=> 'required|date|date_format:Y-m-d|after or equal:'.date('Y-m-d')
        ]);

        $category = Category::findOrFail($request->category_id);

        if($category->user_id != auth()->id()){
            return response()->json(['message'=>'You Don\'n own this category!'], '401');
        }

        if($task->user_id != auth()->id()){
            return response()->json(['message'=>'You Don\'n own this task!'], '401');
        }

        if($task->update($request->all())){
            return response()->json(['message'=>'Task successfully Updated!']);
        }


        return response()->json(['message'=>'Please Try later!'], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Task $task
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Task $task)
    {

        if($task->user_id != auth()->id()){
            return response()->json(['message'=>'You Don\'n own this task!'], '401');
        }

        if($task->delete()){
            return response()->json(['message'=>'task successfully Deleted!']);
        }

        return response()->json(['message'=>'Please Try later!'], 500);

    }

    /**
     * Restore a specified resource from storage.
     *
     */
    public function restore($taskId){

       $task = Task::withTrashed()->findOrFail($taskId);

        if(Auth::id() != $task->user_id){
            return response()->json(['message'=>'You Don\'n own this task!'], '401');
        }

        if ($task->restore()){
            return response()->json(['message'=>'Resource restored successfully!']);
        }

        return response()->json(['message'=>'Please Try later!'], 500);
    }

    /**
     * force delete a specified resource from storage.
     *
     */
    public function forceDelete($taskId){

        $task = Task::withTrashed()->findOrFail($taskId);

        if(Auth::id() != $task->user_id){
            return response()->json(['message'=>'You Don\'n own this task!'], '401');
        }

        if ($task->forceDelete()){
            Storage::deleteDirectory('public/tasks/'.$task->id);

            $userEmail = $task->user->email;
            $taskTitle = $task->title;

            Mail::send([],[], function ($message) use ($task, $userEmail, $taskTitle){
                $message->subject('Task was deleted!');
                $message->to($userEmail);
                $message->setBody('<h4>Task Title: '. $taskTitle . '</h4><p>This Task was deleted!</p>', 'text/html');
            });

            return response()->json(['message'=>'Resource force-deleted successfully!']);
        }

        return response()->json(['message'=>'Please Try later!'], 500);
    }
}
