<?php

namespace App\Repository;

use App\Models\Deficiencia;

class DeficienciaRepository extends BaseRepository
{
    protected $model;

    public function __construct(Deficiencia $model)
    {
        $this->model = $model;
    }
}
