<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Task;
use Carbon\Carbon;

class TaskTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

      /**
     * A feature test to get all tasks
     *
     * @return void
     */
    public function test_get_all_tasks()
    {
        $response = $this->get('/api/todolist')
            ->assertStatus(200)
            ->assertJsonStructure(
                [
                    'code',
                    'message',
                    'data' =>  [
                        '*' => [
                            "id",
                            "task",
                            "status",
                            "create_at",
                            "update_at"
                        ],
                    ],
                ]
            );
    }

    /**
     * A feature test to add a tasks
     *
     * @return void
     */
    public function test_for_add_tasks()
    {

        $payload = [
            "taskname" => 'New Test Task',
            "status" => 'Pending',
        ];
 
        $this->json('POST', 'api/save', $payload)
            ->assertStatus(200)
            ->assertJson([
                'code' => '200',
                'message' => 'Task Saved!',
            ]);
    }

    /**
     * A feature test to add a tasks
     *
     * @return void
     */
    public function test_for_update_tasks()
    {

        $task = Task::create([
            'task' => 'New Task',
            'status' => 'Pending',
        ]);

        $payload = [
            "taskname" => 'New Task Update',
            "status" => 'In Progress',
        ];
 
        $this->json('PUT', 'api/update/' . $task->id, $payload)
            ->assertStatus(200)
            ->assertJson([
                'code' => '200',
                'message' => 'Task Updated!',
            ]);
    }

    /**
     * A feature test to delete a tasks
     *
     * @return void
     */
    public function test_for_delete_tasks()
    {

        $task = Task::create([
            'task' => 'New Task',
            'status' => 'Pending',
        ]);

 
        $this->json('DELETE', 'api/delete/' . $task->id)
            ->assertStatus(200)
            ->assertJson([
                'code' => '200',
                'message' => 'Task Deleted Successfully!',
            ]);
    }

    /**
     * A feature test to update task that do not exist
     *
     * @return void
     */
    public function test_for_update_task_that_not_exist()
    {
        //review id that not exist in database
        $taskid = random_int(100000, 999999);
        $payload = [
            "taskname" => 'New Task Update Not Exist',
            "status" => 'In Progress',
        ];
        $this->json('PUT', 'api/update/' . $taskid, $payload)
            ->assertStatus(200)
            ->assertJson([
                'code' => '401',
                'message' => 'Task Not Found',
            ]);
    }

    /**
     * A feature test to delete task that do not exist
     *
     * @return void
     */
    public function test_for_delete_task_that_not_exist()
    {
        //review id that not exist in database
        $taskid = random_int(100000, 999999);
        
        $this->json('DELETE', 'api/delete/' . $taskid)
            ->assertStatus(200)
            ->assertJson([
                'code' => '401',
                'message' => 'Task not found, Please try again',
            ]);
    }

    

}

