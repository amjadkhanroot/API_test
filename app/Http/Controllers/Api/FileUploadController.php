<?php

namespace App\Http\Controllers\Api;

use App\FileUpload;
use App\Http\Controllers\Controller;
use App\Http\Resources\FileUploadResource;
use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Task $task
     * @return FileUpload
     */
    public function upload(Request $request, $taskId)
    {
        $validateData = $request-> validate([
            'file' => 'required|max:10000|mimes:jpg,jpeg,pdf,pdf'
        ]);

        $task = Task::findOrFail($taskId);
        if(auth()->id() != $task->user_id){
            return response()->json(['message'=>'You Don\'t own this Task!'], '401');
        }

        $filename = $request->file('file')->hashName();
        $uploaded = $request->file('file')->storeAs('public/tasks/'.$task->id, $filename);

        if($uploaded){
            $data =[
                'user_id'=> \auth()->id(),
                'name'=> $filename,
            ];

            $file = $task->files()->create($data);
            if($file){
                return new FileUploadResource($file);
            }

        }

        return response()->json(['message'=>'Please Try later!'], 500);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param FileUpload $file
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(FileUpload $file)
    {

        if($file->user_id != auth()->id()){
            return response()->json(['message'=>'You Don\'n own this File!'], '401');
        }

        if($file->delete()){

            $deleted = Storage::delete('public/tasks/'.$file->task_id.'/'.$file->name);
            if($deleted){
                return response()->json(['message'=>'task successfully Deleted!']);
            }

        }

        return response()->json(['message'=>'Please Try later!'], 500);

    }
}
