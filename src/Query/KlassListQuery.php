<?php
namespace App\Query;

interface KlassListQuery
{
    /** @return KlassView[] */
    public function getAll(): array;
}
