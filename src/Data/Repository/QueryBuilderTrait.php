<?php

declare(strict_types=1);

namespace App\Data\Repository;

use Doctrine\ORM\QueryBuilder;
use function mb_strpos;
use function mb_substr;

trait QueryBuilderTrait
{
    private function addWhere(QueryBuilder $builder, string $name, string $function, array $values, int &$counter): void
    {
        $conditions = [];

        foreach ($values as $value) {
            $conditions[] = $builder->expr()->{$function}($name, '?'.$counter);
            $builder->setParameter($counter, '%'.$value.'%');
            ++$counter;
        }

        $builder->andWhere($builder->expr()->orX(...$conditions));
    }

    private function addSort(QueryBuilder $builder, string $value, string $alias): void
    {
        $field = $value;
        $order = 'ASC';

        if (0 === mb_strpos($value, '-')) {
            $field = mb_substr($value, 1);
            $order = 'DESC';
        }

        $builder->addOrderBy($alias.'.'.$field, $order);
    }
}
