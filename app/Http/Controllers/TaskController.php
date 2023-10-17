<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Task;
use Illuminate\Support\Carbon;
use Validator;
use App\Libraries\CommonHelper;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): JsonResponse
    {

        $taskData = Task::orderBy('created_at', 'desc')->get();
        
        if (!empty($taskData->count())) {
            foreach ($taskData as $key => $task) {
                $data[$key] = [
                    'id' => $task->id,
                    'task' => $task->task,
                    'status' => $task->status,
                    'create_at' => Carbon::createFromFormat('Y-m-d H:i:s', $task->created_at)->format('d/M/Y H:i:s'),
                    'update_at' => Carbon::createFromFormat('Y-m-d H:i:s', $task->updated_at)->format('d/M/Y H:i:s'),
                ];
            }
            return response()->json([
                'code' => '200',
                'message' => 'Tasks Data',
                'data' => $data
            ]);
        } else {
            return response()->json([
                'code' => '100',
                'message' => 'Tasks Data Not Found'
            ]);
        }

      
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): JsonResponse
    {

         // validate the taskname and status
         $validator = Validator::make($request->all(), [
            'taskname' => 'required',
            'status' => 'required'
        ]);

        // found any error 
        if ($validator->fails()) {
            $customError = CommonHelper::customErrorResponse($validator->messages()->get('*'));
            return response()->json([
                'code' => '401',
                'message' => $customError
            ]);
        }

        $newTask = new Task;
        $newTask->task = $request->taskname;
        $newTask->status = $request->status;
        $newTask->save();

        if ($newTask->id > 0) {
            return response()->json([
                'code' => '200',
                'message' => 'Task Saved!',
            ]);
        } else {
            // error
            $customError = "Task not stored, Please try again";
            return response()->json([
                'code' => '401',
                'message' => $customError
            ]);
        }
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
    public function update(Request $request, $id): JsonResponse
    {

        // validate the taskname and status
        $validator = Validator::make($request->all(), [
            'taskname' => 'required',
            'status' => 'required'
        ]);

        // found any error 
        if ($validator->fails()) {
            $customError = CommonHelper::customErrorResponse($validator->messages()->get('*'));
            return response()->json([
                'code' => '401',
                'message' => $customError
            ]);
        }


        $existingTask = Task::find($id);  

        if($existingTask){
            $existingTask->task = $request->taskname;
            $existingTask->status = $request->status;
            $existingTask->updated_at = Carbon::now() ;
            $existingTask->save();

            if ($existingTask->id > 0) {
                return response()->json([
                    'code' => '200',
                    'message' => 'Task Updated!',

                ]);
            } else {
                // error
                $customError = "Task not updated, Please try again";
                return response()->json([
                    'code' => '401',
                    'message' => $customError
                ]);
            }

        }else{
             // error
             $customError = "Task Not Found";
             return response()->json([
                 'code' => '401',
                 'message' => $customError
             ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): JsonResponse
    {
        $existingTask = Task::where('id', $id)->delete();

        if($existingTask){
            return response()->json([
                'code' => '200',
                'message' => 'Task Deleted Successfully!',

            ]);
        }else {
            // error
            $customError = "Task not found, Please try again";
            return response()->json([
                'code' => '401',
                'message' => $customError
            ]);
        }

    }
    
}
