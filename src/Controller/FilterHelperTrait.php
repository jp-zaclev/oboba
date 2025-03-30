<?php
namespace App\Controller;

trait FilterHelperTrait
{
    private function applyNumericFilter($qb, string $field, string $value, string $paramPrefix): void
    {
        if (preg_match('/^>(.*)$/', $value, $matches)) {
            $qb->andWhere("$field > :{$paramPrefix}_gt")->setParameter("{$paramPrefix}_gt", (float)$matches[1]);
        } elseif (preg_match('/^<(.*)$/', $value, $matches)) {
            $qb->andWhere("$field < :{$paramPrefix}_lt")->setParameter("{$paramPrefix}_lt", (float)$matches[1]);
        } elseif (preg_match('/^(\d*\.?\d+)-(\d*\.?\d+)$/', $value, $matches)) {
            $qb->andWhere("$field BETWEEN :{$paramPrefix}_min AND :{$paramPrefix}_max")
               ->setParameter("{$paramPrefix}_min", (float)$matches[1])
               ->setParameter("{$paramPrefix}_max", (float)$matches[2]);
        } else {
            $qb->andWhere("$field = :{$paramPrefix}_eq")->setParameter("{$paramPrefix}_eq", (float)$value);
        }
    }
}
