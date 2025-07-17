<?php
namespace App\Exports;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

/**
 * Class de exportação da tela de instituicoes habilitadas
 */
class InstituicoesHabilitadasExport implements FromView
{

    /**
     * Atributo que recebe os dados da busca
     * 
     * @var array
     */
    protected $data = [];
    
    /**
     * Construtor
     *
     * @param array $data
     * @param array $filters
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * Método de especificação da view exportável
     *
     * @return View
     */
    public function view(): View
    {
        return view('adesao.export-xls-search-instituicoes-habilitadas', [
            'data' => $this->data,
            'esferas' => [
                'ED' => 'Estadual/Distrital',
                'MU' => 'Municipal'
            ]
        ]);
    }
}