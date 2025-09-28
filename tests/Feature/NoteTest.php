<?php

use App\Models\Note;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseEmpty;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAs($this->user);
});

test('notes screen can be rendered', function () {
    get('/')->assertOk();
});

test('notes can be created', function () {
    post('/notes', [
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

    put("/notes/{$note->id}", [
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

    delete("/notes/{$note->id}")->assertRedirectBack();

    assertDatabaseEmpty(Note::class);
});

test('notes can be archived', function () {
    $note = Note::factory()
        ->for($this->user)
        ->create([
            'is_archived' => false,
        ]);

    put("/notes/{$note->id}/archive")->assertRedirectBack();

    assertDatabaseHas(Note::class, [
        'is_archived' => true,
    ]);
});

test('notes can be restored', function () {
    $note = Note::factory()
        ->for($this->user)
        ->create([
            'is_archived' => true,
        ]);

    put("/notes/{$note->id}/restore")->assertRedirectBack();

    assertDatabaseHas(Note::class, [
        'is_archived' => false,
    ]);
});
