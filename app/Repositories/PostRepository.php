<?php

namespace App\Repositories;

use App\Models\Post;
use App\Repositories\Criteria\PostCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

class PostRepository extends BaseRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Post::class;
    }

    public function allowRelation()
    {
        return [
            'comments',
        ];
    }

    public function boot()
    {
        $this->pushCriteria(PostCriteria::class);
    }
}
