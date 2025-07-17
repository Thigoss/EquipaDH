<?php

namespace App\Repository;

use App\Models\ConfiguracaoGeral;

/**
 * Classe de manipulação da entidade de configuração geral
 *
 * @author Eduardo Praxedes Heinske (eduardo.praxedes@jointecnologia.com.br)
 */
class ConfiguracaoGeralRepository extends BaseRepository
{

    /**
     * Model de configuração geral
     *
     * @var ConfiguracaoGeral
     */
    protected $configuracao;
    
    /**
     * Construtor
     *
     * @param ConfiguracaoGeral $configuracao
     */
    public function __construct(ConfiguracaoGeral $configuracao)
    {
        $this->model = $configuracao;
    }

    /**
     * Obtem a configuração geral
     *
     * @return ConfiguracaoGeral
     */
    public function getConfiguracao() : ?ConfiguracaoGeral
    {
        return $this->model->first();
    }

    /**
     * Realiza o cadastro / alteração de dados da configuração geral
     *
     * @param array $data
     * @return boolean
     */
    public function save(array $data) : bool
    {
         // Armazena o ato de delegação
         if (isset($data['arquivo_ato_declaracao'])) {
            $documento = $this->salvarArquivo($data['arquivo_ato_declaracao'], 'configuracao');
            if ($documento && isset($documento['caminho'])) {
                $data['arquivo_ato_declaracao'] = $documento['caminho'];
            }
        }

        // Armazena o ato de delegação
        if (isset($data['arquivo_ato_delegacao'])) {
            $documento = $this->salvarArquivo($data['arquivo_ato_delegacao'], 'configuracao');
            if ($documento && isset($documento['caminho'])) {
                $data['arquivo_ato_delegacao'] = $documento['caminho'];
            }
        }
        
        // Armazena o arquivo de declaração
        if (isset($data['arquivo_declaracao_unificada'])) {
            $documento = $this->salvarArquivo($data['arquivo_declaracao_unificada'], 'configuracao');
            if ($documento && isset($documento['caminho'])) {
                $data['arquivo_declaracao_unificada'] = $documento['caminho'];
            }
        }
        
        // Armazena o ato de convocação
        if (isset($data['arquivo_ato_convocacao'])) {
            $documento = $this->salvarArquivo($data['arquivo_ato_convocacao'], 'configuracao');
            if ($documento && isset($documento['caminho'])) {
                $data['arquivo_ato_convocacao'] = $documento['caminho'];
            }
        }

        // Armazena o arquivo de habilitação
        if (isset($data['arquivo_habilitacao'])) {
            $documento = $this->salvarArquivo($data['arquivo_habilitacao'], 'configuracao');
            if ($documento && isset($documento['caminho'])) {
                $data['arquivo_habilitacao'] = $documento['caminho'];
            }
        }

        if (isset($data['id']) && $data['id']) {
            $model = $this->model->find($data['id']);
            return $model->fill($data)->save();
        } else {
            return $this->model->fill($data)->save();  
        }
    }
}