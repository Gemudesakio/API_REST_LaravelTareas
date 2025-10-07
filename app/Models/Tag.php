<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    //
    protected $fillable = ['name'];

/**
 * Relación muchos a muchos (N:M) entre Task y Tag.
 * Una tarea puede tener varios tags y un tag puede pertenecer a varias tareas.
 * Laravel usa la tabla pivote "tag_task" para gestionar esta relación.
 */
    public function tasks(): BelongsToMany {
        return $this->belongsToMany(Task::class)->withTimestamps();
    }
}
