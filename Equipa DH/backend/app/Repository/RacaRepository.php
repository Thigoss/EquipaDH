<?php

namespace App\Repository;

use App\Models\Raca;

class RacaRepository extends BaseRepository
{

    protected $model;

    public function __construct(Raca $model)
    {
        $this->model = $model;
    }
}
