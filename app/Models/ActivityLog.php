<?php

namespace App\Models;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'project_id',
        'task_id',
        'action',
        'description',
        'changes',
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

     public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Helper: log an activity for a project.
     */
    public static function forProject(Project $project, string $action, ?string $description = null, ?array $changes = null, ?User $user = null): self
    {
        $user ??= auth()->user();

        return self::create([
            'user_id'    => $user?->id,
            'project_id' => $project->id,
            'task_id'    => null,
            'action'     => $action,
            'description'=> $description,
            'changes'    => $changes,
        ]);
    }

    /**
     * Helper: log an activity for a task (and its parent project).
     */
    public static function forTask(Task $task, string $action, ?string $description = null, ?array $changes = null, ?User $user = null): self
    {
        $user ??= auth()->user();

        return self::create([
            'user_id'    => $user?->id,
            'project_id' => $task->project_id,
            'task_id'    => $task->id,
            'action'     => $action,
            'description'=> $description,
            'changes'    => $changes,
        ]);
    }

}
