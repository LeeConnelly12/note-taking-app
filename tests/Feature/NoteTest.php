<?php

use App\Models\Note;
use App\Models\Tag;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseEmpty;
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
    $tags = Tag::factory()->count(2)->create();

    actingAs($this->user)
        ->post('/notes', [
            'title' => 'new note',
            'tags' => $tags->toArray(),
            'content' => 'new note content',
        ])->assertRedirectBackWithoutErrors();

    $note = Note::where('title', 'new note')->first();
    expect($note->tags()->count())->toBe(2);
    expect($note->content)->toBe('new note content');
});

test('notes can be updated', function () {
    $note = Note::factory()
        ->for($this->user)
        ->hasTags(2)
        ->create();

    $tag = Tag::factory()->create([
        'name' => 'new tag',
    ]);

    actingAs($this->user)
        ->put("/notes/{$note->id}", [
            'title' => 'updated title',
            'tags' => [$tag],
            'content' => 'updated content',
        ])->assertRedirectBackWithoutErrors();

    $note = Note::where('title', 'updated title')->first();
    expect($note->tags()->count())->toBe(1);
    expect($note->content)->toBe('updated content');
});

test('notes can be deleted', function () {
    $note = Note::factory()
        ->for($this->user)
        ->create();

    actingAs($this->user)
        ->delete("/notes/{$note->id}")
        ->assertRedirectBackWithoutErrors();

    assertDatabaseEmpty(Note::class);
});

test('notes can be archived', function () {
    $note = Note::factory()
        ->for($this->user)
        ->create();

    actingAs($this->user)
        ->put("/notes/{$note->id}/archive")
        ->assertRedirectBackWithoutErrors();

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
        ->assertRedirectBackWithoutErrors();

    assertNotSoftDeleted($note);
});

test('users can only update their own notes', function () {
    $note = Note::factory()
        ->for($this->user)
        ->create();

    $otherUser = User::factory()->create();

    actingAs($otherUser)
        ->put("/notes/{$note->id}", [
            'title' => 'updated title',
            'content' => 'updated content',
        ])->assertForbidden();
});

test('users can only delete their own notes', function () {
    $note = Note::factory()
        ->for($this->user)
        ->create();

    $otherUser = User::factory()->create();

    actingAs($otherUser)
        ->delete("/notes/{$note->id}")
        ->assertForbidden();
});

test('users can only archive their own notes', function () {
    $note = Note::factory()
        ->for($this->user)
        ->create();

    $otherUser = User::factory()->create();

    actingAs($otherUser)
        ->put("/notes/{$note->id}/archive")
        ->assertForbidden();
});

test('users can only restore their own notes', function () {
    $note = Note::factory()
        ->for($this->user)
        ->create();

    $otherUser = User::factory()->create();

    actingAs($otherUser)
        ->put("/notes/{$note->id}/restore")
        ->assertForbidden();
});
