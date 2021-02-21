<?php

namespace App\Query;

class KlassView
{
    public int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }
}
