<?php

namespace App\Query;

use App\Entity\Klass;
use App\Entity\User;
use App\Repository\KlassRepository;

class DbalKlassListQuery implements KlassListQuery
{
    private KlassRepository $klassRepository;

    public function __construct(KlassRepository $klassRepository)
    {
        $this->klassRepository = $klassRepository;
    }

    /** @return KlassView[] */
    public function getAll(): array
    {
        $allKlasses = $this->klassRepository->findAll();

        return array_map(function (Klass $klass) {
            return KlassView::fromKlass($klass);
        }, $allKlasses);
    }
}
