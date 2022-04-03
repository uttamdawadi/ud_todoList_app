<?php

namespace Tests\Feature;

use App\Models\TaskList;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskListsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_can_create_a_task(){
        $response = $this->postJson('/api/tasks/new', [
            'title' => 'Task Number 1: UD TEST 1',
            'due_date' => '2022-04-03',
        ]);
        $response->assertStatus(201)->assertJson([
            'result' => true,
        ]);

        $this->assertDatabaseHas('task_lists', [
            'title' => 'Task Number 1: UD TEST 1',
            'due_date' => '2022-04-03',
            'status' => false,
            'deleted_at' => null,
        ]);
    }

    /**
     * @return void
     */
    public function test_can_update_task_complete(){

        $task = TaskList::factory()->create([
            'title' => 'Task Number 2: UD Test 2 Complete',
            'due_date' => Carbon::now()->format('Y-m-d'),
            'status' => false,
        ]);

        $response = $this->putJson('/api/tasks/complete', [
            'id'=> $task->id,
        ]);
        $response->assertStatus(200)->assertJson([
            'result' => true,
        ]);

        $this->assertDatabaseHas('task_lists', [
            'title' => 'Task Number 2: UD Test 2 Complete',
            'due_date' => Carbon::now()->format('Y-m-d'),
            'status' => true,
        ]);
    }

    /**
     * @return void
     */
    public function test_can_delete_task(){

        $task = TaskList::factory()->create([
            'title' => 'Task Number 3: UD Test 3 Delete',
            'due_date' => Carbon::now()->format('Y-m-d'),
            'status' => true,
        ]);

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)->assertJson([
            'result' => true,
        ]);

        $this->assertDatabaseHas('task_lists', [
            'title' => 'Task Number 3: UD Test 3 Delete',
            'due_date' => Carbon::now()->format('Y-m-d'),
            'status' => true,
            'deleted_at'=> Carbon::now(),
        ]);
    }

    /**
     * @return void
     */
    public function test_can_filter_with_tittle(){

        TaskList::factory()->create([
            'title' => 'Task Number 4: Read a book.',
            'due_date' => Carbon::now()->add(8, 'days')->format('Y-m-d'),
            'status' => false,
        ]);
        TaskList::factory()->create([
            'title' => 'Task Number 5: Practise English Test',
            'due_date' => Carbon::now()->add(9, 'days')->format('Y-m-d'),
            'status' => false,
        ]);

        TaskList::factory()->create([
            'title' => 'Task Number 6: Give English Test',
            'due_date' => Carbon::now()->add(10, 'days')->format('Y-m-d'),
            'status' => false,
        ]);
        $response = $this->getJson('api/tasks/filter?title=english');
        $response->assertStatus(200)->assertJsonFragment(['total' => 2]);
    }

    /**
     * @return void
     */
    public function test_can_filter_with_due_today(){

        TaskList::factory()->create([
            'title' => 'Task Number 1: Go Bank',
            'due_date' => Carbon::now()->format('Y-m-d'),
            'status' => false,
        ]);
        TaskList::factory()->create([
            'title' => 'Task Number 2: Go Gym',
            'due_date' => Carbon::now()->add(1, 'days')->format('Y-m-d'),
            'status' => false,
        ]);
        TaskList::factory()->create([
            'title' => 'Task Number 3: Go Shopping',
            'due_date' => Carbon::now()->add(2, 'days')->format('Y-m-d'),
            'status' => false,
        ]);
        TaskList::factory()->create([
            'title' => 'Task Number 4: Read a book.',
            'due_date' => Carbon::now(),
            'status' => false,
        ]);

        $response = $this->getJson('api/tasks/filter?due_date=today');
        $response->assertStatus(200)->assertJsonFragment(['total' => 2]);
    }

    /**
     * @return void
     */
    public function test_can_filter_with_overdue(){

        TaskList::factory()->create([
            'title' => 'Task Number 1: Go Bank',
            'due_date' => Carbon::yesterday()->format('Y-m-d'),
            'status' => false,
        ]);
        TaskList::factory()->create([
            'title' => 'Task Number 2: Go Gym',
            'due_date' => Carbon::now()->add(1, 'days')->format('Y-m-d'),
            'status' => false,
        ]);

        $response = $this->getJson('api/tasks/filter?due_date=overdue');
        $response->assertStatus(200)->assertJsonFragment(['total' =>1]);
    }

    /**
     * @return void
     */
    public function test_can_filter_with_this_week(){

        TaskList::factory()->create([
            'title' => 'Task Number 1: Go Bank',
            'due_date' => Carbon::now()->add(10,'days')->format('Y-m-d'),
            'status' => false,
        ]);
        TaskList::factory()->create([
            'title' => 'Task Number 2: Go Gym',
            'due_date' => Carbon::now()->format('Y-m-d'),
            'status' => false,
        ]);
        TaskList::factory()->create([
            'title' => 'Task Number 3: Go Hospital',
            'due_date' => Carbon::now()->format('Y-m-d'),
            'status' => false,
        ]);

        $response = $this->getJson('api/tasks/filter?due_date=overdue');
        $response->assertStatus(200)->assertJsonFragment(['total' =>2]);
    }

    /**
     * @return void
     */
    public function test_can_filter_with_next_week(){

        TaskList::factory()->create([
            'title' => 'Task Number 1: Go Bank',
            'due_date' => Carbon::now()->add(10,'days')->format('Y-m-d'),
            'status' => false,
        ]);
        TaskList::factory()->create([
            'title' => 'Task Number 2: Go Gym',
            'due_date' => Carbon::now()->format('Y-m-d'),
            'status' => false,
        ]);
        TaskList::factory()->create([
            'title' => 'Task Number 3: Go Hospital',
            'due_date' => Carbon::now()->add(5, 'days')->format('Y-m-d'),
            'status' => false,
        ]);

        $response = $this->getJson('api/tasks/filter?due_date=overdue');
        $response->assertStatus(200)->assertJsonFragment(['total' =>1]);
    }
}
