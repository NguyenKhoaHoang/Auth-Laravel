<?php

namespace App\Services;

use App\Exceptions\CommentException;
use App\Models\Comment;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CommentService
{
    public function search($id)
    {
        $commentSearch = Comment::find($id);
        if (!$commentSearch) {
            throw new CommentException('Comment not found by ID ' . $id);
            // throw new ModelNotFoundException('Comment not found by ID ' . $id);
        }

        return $commentSearch;
    }
}
