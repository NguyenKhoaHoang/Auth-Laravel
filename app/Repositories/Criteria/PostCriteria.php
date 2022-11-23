<?php

namespace App\Repositories\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class PostCriteriaCriteria.
 *
 * @package namespace App\Criteria\Repositories;
 */
class PostCriteria implements CriteriaInterface
{
     /**
     * Apply criteria in query repository
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param Prettus\Repository\Contracts\RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $model = $model->orderBy('category_id');
        return $model;
    }
}
