<?php

use App\Models\Note;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseEmpty;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertNotSoftDeleted;
use function Pest\Laravel\assertSoftDeleted;

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('notes screen can be rendered', function () {
    actingAs($this->user)
        ->get('/')
        ->assertOk();
});

test('notes can be created', function () {
    actingAs($this->user)
        ->post('/notes', [
            'title' => 'new note',
            'content' => 'new note content',
        ])->assertRedirectBack();

    assertDatabaseHas(Note::class, [
        'title' => 'new note',
        'content' => 'new note content',
    ]);
});

test('notes can be updated', function () {
    $note = Note::factory()
        ->for($this->user)
        ->create();

    actingAs($this->user)
        ->put("/notes/{$note->id}", [
            'title' => 'updated title',
            'content' => 'updated content',
        ])->assertRedirectBack();

    assertDatabaseHas(Note::class, [
        'title' => 'updated title',
        'content' => 'updated content',
    ]);
});

test('notes can be deleted', function () {
    $note = Note::factory()
        ->for($this->user)
        ->create();

    actingAs($this->user)
        ->delete("/notes/{$note->id}")
        ->assertRedirectBack();

    assertDatabaseEmpty(Note::class);
});

test('notes can be archived', function () {
    $note = Note::factory()
        ->for($this->user)
        ->create();

    actingAs($this->user)
        ->put("/notes/{$note->id}/archive")
        ->assertRedirectBack();

    assertSoftDeleted($note);
});

test('notes can be restored', function () {
    $note = Note::factory()
        ->for($this->user)
        ->create([
            'deleted_at' => now(),
        ]);

    actingAs($this->user)
        ->put("/notes/{$note->id}/restore")
        ->assertRedirectBack();

    assertNotSoftDeleted($note);
});
