<?php

namespace App\Repository;

use App\Models\OrientacaoSexual;

class OrientacaoSexualRepository extends BaseRepository
{
 
    protected $model;

    public function __construct(OrientacaoSexual $model)
    {
        $this->model = $model;
    }
}
