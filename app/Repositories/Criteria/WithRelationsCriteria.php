<?php

namespace App\Repositories\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class WithRelationCriteria.
 *
 * @package App\Repositories\Criteria
 */
class WithRelationsCriteria implements CriteriaInterface
{
    /**
     * List of request relations from query string
     *
     * @var array
     */
    protected $input;

    /**
     * List of allow relations
     *
     * @var array
     */
    protected $allows;

    /**
     * An constructor of WithRelationsCriteria
     *
     * @param mixed $input
     * @param array $allows
     */
    public function __construct(String|array|null $input, array $allows)
    {
        $this->input = is_array($input) ? $input : explode(',', $input);
        $this->allows = $allows;
    }

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
        $withs = [];

        foreach ($this->input as $key => $value) {
            if (array_key_exists($value, $this->allows)) {
                $withs[$value] = $this->allows[$value];
            } elseif (in_array($value, $this->allows)) {
                $withs[] = $value;
            }
        }

        return empty($withs) ? $model : $model->with($withs);
    }
}
