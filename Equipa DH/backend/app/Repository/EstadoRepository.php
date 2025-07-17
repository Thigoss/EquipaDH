<?php

namespace App\Repository;

use App\Models\Estado;

/**
 * Classe de manipulação da entidade de estado
 *
 * @author Eduardo Praxedes Heinske (eduardo.praxedes@jointecnologia.com.br)
 */
class EstadoRepository extends BaseRepository
{
    /**
     * Model de estado
     *
     * @var Estado
     */
    protected $model;

    /**
     * Construtor
     *
     * @param Estado $estado
     */
    public function __construct(Estado $estado)
    {
        $this->model = $estado;
    }
}
