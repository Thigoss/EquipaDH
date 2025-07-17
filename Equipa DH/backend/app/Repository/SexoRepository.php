<?php

namespace App\Repository;

use App\Models\Sexo;

class SexoRepository extends BaseRepository
{
    /**
     * Model de Sexo
     *
     * @var Sexo
     */
    protected $model;

    /**
     * Construtor
     *
     * @param Sexo $sexo
     */
    public function __construct(Sexo $sexo)
    {
        $this->model = $sexo;
    }

    /**
     * Realiza a pesquisa por unidades
     *
     * @param string $search
     * @param array $filtros
     * @param boolean $limit
     * @return Collection
     */
    public function search(string $search = '', array $filtros = [], $limit = false)
    {
        $query = $this->model;
        if ($limit !== false) {
            return $query->paginate($limit);
        } else {
            return $query->get();
        }
    }
}
