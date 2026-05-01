<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Task;
use App\Models\TodoList;
use App\Models\Subtask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    /* ─────────────────────────────────────────────
     * BOOTSTRAP  –  load everything for this user
     * ───────────────────────────────────────────── */
    public function bootstrap()
    {
        $userId = Auth::id();

        $lists = TodoList::where('user_id', $userId)
            ->orderBy('position')
            ->get(['id', 'name', 'color']);

        $tasks = Task::where('tasks.user_id', $userId)
            ->with(['subtasks', 'tags', 'list', 'attachments'])
            ->orderBy('position')
            ->get()
            ->map(fn($t) => $this->formatTask($t));

        $tags = Tag::where('user_id', $userId)
            ->orderBy('name')
            ->get(['id', 'name', 'color']);

        return response()->json(compact('lists', 'tasks', 'tags'));
    }

    /* ─────────────────────────────────────────────
     * LISTS
     * ───────────────────────────────────────────── */
    public function storelist(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100']);
        $userId = Auth::id();

        $pos = TodoList::where('user_id', $userId)->max('position') + 1;

        $list = TodoList::create([
            'user_id'  => $userId,
            'name'     => $request->name,
            'color'    => $request->color ?? null,
            'position' => $pos,
        ]);

        return response()->json($list);
    }

    public function destroyList($id)
    {
        $list = TodoList::where('user_id', Auth::id())->findOrFail($id);
        $list->delete();
        return response()->json(['ok' => true]);
    }

    /* ─────────────────────────────────────────────
     * TASKS
     * ───────────────────────────────────────────── */
    public function storeTask(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'list_id' => 'required|integer',
        ]);

        $userId = Auth::id();

        // Ensure the list belongs to this user
        $list = TodoList::where('user_id', $userId)->findOrFail($request->list_id);

        $pos = Task::where('user_id', $userId)->max('position') + 1;

        $task = Task::create([
            'user_id'   => $userId,
            'list_id'   => $list->id,
            'title'     => $request->title,
            'note'      => $request->note ?? null,
            'due_date'  => $request->due_date ?? null,
            'priority'  => $request->priority ?? 'none',
            'status'    => 'todo',
            'is_my_day' => $request->is_my_day ?? false,
            'position'  => $pos,
        ]);

        $task->load(['subtasks', 'tags', 'list', 'attachments']);
        return response()->json($this->formatTask($task));
    }

    public function updateTask(Request $request, $id)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($id);

        $fields = $request->only([
            'title', 'note', 'due_date', 'priority', 'status',
            'is_my_day', 'list_id', 'position',
        ]);

        // Handle completion timestamp
        if (isset($fields['status'])) {
            $fields['completed_at'] = $fields['status'] === 'done' ? now() : null;
        }

        // If changing list, verify the new list belongs to this user
        if (isset($fields['list_id'])) {
            TodoList::where('user_id', Auth::id())->findOrFail($fields['list_id']);
        }

        $task->update($fields);
        $task->load(['subtasks', 'tags', 'list', 'attachments']);
        return response()->json($this->formatTask($task));
    }

    public function destroyTask($id)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($id);
        $task->delete();
        return response()->json(['ok' => true]);
    }

    /* ─────────────────────────────────────────────
     * SUBTASKS
     * ───────────────────────────────────────────── */
    public function storeSubtask(Request $request, $taskId)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($taskId);
        $request->validate(['title' => 'required|string|max:255']);

        $pos = Subtask::where('task_id', $task->id)->max('position') + 1;

        $subtask = Subtask::create([
            'task_id'  => $task->id,
            'title'    => $request->title,
            'position' => $pos,
        ]);

        return response()->json($subtask);
    }

    public function updateSubtask(Request $request, $id)
    {
        // Verify ownership via the parent task
        $subtask = Subtask::whereHas('task', fn($q) => $q->where('user_id', Auth::id()))
            ->findOrFail($id);

        $subtask->update($request->only(['title', 'is_completed', 'position']));
        return response()->json($subtask);
    }

    /* ─────────────────────────────────────────────
     * TAGS
     * ───────────────────────────────────────────── */
    public function storeTag(Request $request)
    {
        $request->validate(['name' => 'required|string|max:50']);
        $userId = Auth::id();

        $name = $request->name;
        if (!str_starts_with($name, '#')) $name = '#' . $name;

        $tag = Tag::firstOrCreate(
            ['user_id' => $userId, 'name' => $name],
            ['color' => $request->color ?? '#c4a882']
        );

        return response()->json($tag);
    }

    public function destroyTag($id)
    {
        $tag = Tag::where('user_id', Auth::id())->findOrFail($id);
        $tag->delete();
        return response()->json(['ok' => true]);
    }

    /* ─────────────────────────────────────────────
     * TASK ↔ TAG  (assign / unassign)
     * ───────────────────────────────────────────── */
    public function assignTag(Request $request, $taskId, $tagId)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($taskId);
        $tag  = Tag::where('user_id', Auth::id())->findOrFail($tagId);

        // Toggle: if already attached, detach; otherwise attach
        if ($task->tags()->where('tag_id', $tag->id)->exists()) {
            $task->tags()->detach($tag->id);
            $attached = false;
        } else {
            $task->tags()->attach($tag->id);
            $attached = true;
        }

        return response()->json(['attached' => $attached]);
    }

    /* ─────────────────────────────────────────────
     * ATTACHMENTS
     * ───────────────────────────────────────────── */
    public function storeAttachment(Request $request, $taskId)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($taskId);

        $request->validate([
            'file' => 'required|file|max:10240' // max 10MB
        ]);

        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        $path = $file->store('attachments', 'public');

        $attachment = $task->attachments()->create([
            'filename'  => $filename,
            'path'      => $path,
            'mime_type' => $file->getClientMimeType(),
            'size'      => $file->getSize(),
        ]);

        return response()->json([
            'id' => $attachment->id,
            'filename' => $attachment->filename,
            'url' => asset('storage/' . $attachment->path),
        ]);
    }

    public function destroyAttachment($id)
    {
        $attachment = \App\Models\Attachment::whereHas('task', fn($q) => $q->where('user_id', Auth::id()))
            ->findOrFail($id);

        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($attachment->path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($attachment->path);
        }

        $attachment->delete();
        return response()->json(['ok' => true]);
    }

    /* ─────────────────────────────────────────────
     * HELPERS
     * ───────────────────────────────────────────── */
    private function formatTask(Task $task): array
    {
        return [
            'id'        => $task->id,
            'title'     => $task->title,
            'completed' => $task->status === 'done',
            'status'    => $task->status,
            'list_id'   => $task->list_id,
            'list'      => $task->list?->name ?? 'Personal',
            'priority'  => $task->priority,
            'note'      => $task->note,
            'due_date'  => $task->due_date?->toDateString(),
            'is_my_day' => (bool) $task->is_my_day,
            'tag'       => $task->tags->first()?->name ?? '',
            'tag_id'    => $task->tags->first()?->id ?? null,
            'subtasks'  => $task->subtasks->map(fn($s) => [
                'id'   => $s->id,
                'text' => $s->title,
                'done' => $s->is_completed,
            ])->toArray(),
            'attachments' => $task->attachments->map(fn($a) => [
                'id' => $a->id,
                'filename' => $a->filename,
                'url' => asset('storage/' . $a->path),
            ])->toArray(),
        ];
    }
}
