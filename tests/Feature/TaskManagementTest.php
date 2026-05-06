<?php

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\patch;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

uses(RefreshDatabase::class);

test('a user can create update toggle and delete their own task', function () {
    $user = User::factory()->create();
    $category = $user->categories()->create([
        'name' => 'Work',
    ]);

    actingAs($user);

    post(route('tasks.store'), [
        'title' => 'Write weekly summary',
        'description' => 'Share team progress and blockers',
        'category_id' => $category->id,
        'task_date' => '2026-05-05',
        'is_recurring' => '1',
    ])
        ->assertRedirect(route('tasks.index'));

    $task = $user->tasks()->firstOrFail();

    expect($task)->not->toBeNull();

    assertDatabaseHas('tasks', [
        'id' => $task->id,
        'title' => 'Write weekly summary',
        'category_id' => $category->id,
        'is_recurring' => 1,
    ]);

    put(route('tasks.update', $task), [
        'title' => 'Write monthly summary',
        'description' => 'Share highlights with leadership',
        'category_id' => $category->id,
        'task_date' => '2026-05-06',
        'is_recurring' => '0',
    ])
        ->assertRedirect(route('tasks.index'));

    $task = Task::query()->findOrFail($task->id);

    expect($task->title)->toBe('Write monthly summary')
        ->and($task->is_recurring)->toBeFalse();

    patch(route('tasks.toggle-completion', $task))
        ->assertRedirect(route('tasks.index'));

    $task = Task::query()->findOrFail($task->id);
    expect($task->completed_at)->not->toBeNull();

    delete(route('tasks.destroy', $task))
        ->assertRedirect(route('tasks.index'));

    assertModelMissing($task);
});

test('a user cannot manage another users task', function () {
    $user = User::factory()->create();
    $owner = User::factory()->create();
    $task = $owner->tasks()->create([
        'title' => 'Private task',
        'description' => 'Owner only',
        'is_recurring' => false,
        'task_date' => '2026-05-04',
    ]);

    actingAs($user);

    get(route('tasks.edit', $task))
        ->assertForbidden();

    put(route('tasks.update', $task), [
        'title' => 'Hijacked task',
        'description' => 'Nope',
        'category_id' => '',
        'task_date' => '2026-05-05',
        'is_recurring' => '0',
    ])
        ->assertForbidden();

    patch(route('tasks.toggle-completion', $task))
        ->assertForbidden();

    delete(route('tasks.destroy', $task))
        ->assertForbidden();
});

test('task index filters by status category and date range', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $work = $user->categories()->create(['name' => 'Work']);
    $personal = $user->categories()->create(['name' => 'Personal']);
    $otherCategory = $otherUser->categories()->create(['name' => 'External']);

    $user->tasks()->create([
        'title' => 'Completed work task',
        'description' => 'Should remain visible',
        'category_id' => $work->id,
        'is_recurring' => false,
        'task_date' => '2026-05-10',
        'completed_at' => now(),
    ]);

    $user->tasks()->create([
        'title' => 'Incomplete work task',
        'description' => 'Wrong status',
        'category_id' => $work->id,
        'is_recurring' => false,
        'task_date' => '2026-05-11',
    ]);

    $user->tasks()->create([
        'title' => 'Completed personal task',
        'description' => 'Wrong category',
        'category_id' => $personal->id,
        'is_recurring' => false,
        'task_date' => '2026-05-12',
        'completed_at' => now(),
    ]);

    $user->tasks()->create([
        'title' => 'Completed outside range',
        'description' => 'Wrong date',
        'category_id' => $work->id,
        'is_recurring' => false,
        'task_date' => '2026-06-01',
        'completed_at' => now(),
    ]);

    $otherUser->tasks()->create([
        'title' => 'Other user task',
        'description' => 'Should not be visible',
        'category_id' => $otherCategory->id,
        'is_recurring' => false,
        'task_date' => '2026-05-10',
        'completed_at' => now(),
    ]);

    actingAs($user);

    $response = get(route('tasks.index', [
        'status' => 'completed',
        'category' => $work->uuid,
        'from' => '2026-05-01',
        'to' => '2026-05-31',
    ]));

    $response->assertSuccessful()
        ->assertSee('Completed work task')
        ->assertDontSee('Incomplete work task')
        ->assertDontSee('Completed personal task')
        ->assertDontSee('Completed outside range')
        ->assertDontSee('Other user task');
});
