<?php

namespace App\Repository;

use App\Models\Escolaridade;

class EscolaridadeRepository extends BaseRepository
{
    protected $model;

    public function __construct(Escolaridade $model)
    {
        $this->model = $model;
    }
}
