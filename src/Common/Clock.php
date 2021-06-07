<?php

namespace App\Common;

interface Clock
{
    public function getCurrentTime(): \DateTimeImmutable;
}
