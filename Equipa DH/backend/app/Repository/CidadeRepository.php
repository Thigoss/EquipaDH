<?php

namespace App\Repository;

use App\Models\Cidade;
use Illuminate\Database\Eloquent\Collection;

/**
 * Classe de manipulação da entidade de cidade
 *
 * @author Eduardo Praxedes Heinske (eduardo.praxedes@jointecnologia.com.br)
 */
class CidadeRepository extends BaseRepository
{

    /**
     * Model de cidade
     *
     * @var Cidade
     */
    protected $cidade;

    /**
     * Construtor
     *
     * @param Cidade $cidade
     */
    public function __construct(Cidade $cidade)
    {
        $this->cidade = $cidade;
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
        $query = $this->cidade;
        if ($search) {
            $query = $query->where('cidade', EncryptHelper::encrypt($search));
        }
        if ($limit !== false) {
            return $query->paginate($limit);
        } else {
            return $query->get();
        }
    }

    /**
     * Retorna todas as cidades vinculadas aos estados
     *
     * @param string $estadoId
     * @return Collection
     */
    public function getByEstadoId($estadoId)
    {
        $cidades = $this->cidade->where('estado_id', $estadoId)->get();
        $arrCidades = [];

        foreach ($cidades as $cidade) {
            $arrCidades[] = [
                'id' => $cidade->id,
                'cidade' => $cidade->cidade . ($cidade->tipo == 1 ? ' - Distrito' : ''),
                'codigo_ibge' => $cidade->codigo_ibge,
            ];
        }

        usort($arrCidades, function ($a, $b) {
            return strcmp($a['cidade'], $b['cidade']);
        });
        
        return $arrCidades;
    }
}