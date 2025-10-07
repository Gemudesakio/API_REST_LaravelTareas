<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Task extends Model
{
    //
    protected $fillable = ['user_id','title','description','is_completed','completed_at'];

/**
 * Relación muchos a muchos (N:M) entre Task y Tag.
 * Una tarea puede tener varios tags y un tag puede pertenecer a varias tareas.
 * Laravel usa la tabla pivote "tag_task" para gestionar esta relación.
 */
    public function tags(): BelongsToMany {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }
}
