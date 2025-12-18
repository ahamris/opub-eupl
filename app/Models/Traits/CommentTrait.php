<?php

namespace App\Models\Traits;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait CommentTrait
{
    /**
     * Get all of the entity's comments.
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'entity');
    }
}
