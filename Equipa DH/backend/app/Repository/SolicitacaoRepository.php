<?php

namespace App\Repository;

use App\Exports\SolicitacaoExport;
use App\Helpers\EncryptHelper;
use App\Helpers\UtilHelper;
use App\Mail\CredenciamentoCriado;
use App\Mail\CredenciamentoSituacao;
use App\Models\Instituicao;
use App\Models\InstituicaoUser;
use App\Models\Perfil;
use App\Models\Solicitacao;
use App\Models\SolicitacaoHistorico;
use App\Models\SolicitacaoInstituicao;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use DB;
use Illuminate\Support\Facades\Mail;
use Excel;
use Illuminate\Support\Facades\Log;
use PDF;

/**
 * Classe de manipulação da entidade de solicitações
 *
 * @author Eduardo Praxedes Heinske (eduardo.praxedes@jointecnologia.com.br)
 */
class SolicitacaoRepository extends BaseRepository
{

    /**
     * Model de solicitação
     *
     * @var Solicitacao
     */
    protected $model;

    /**
     * Model de instituição
     * 
     * @var SolicitacaoInstituicao
     */
    protected $instituicao;

    /**
     * Model de Histórico
     * 
     * @var SolicitacaoHistorico
     */
    protected $historico;

    /**
     * Repository de usuários
     *
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * Repository de instituições
     * 
     * @var InstituicaoRepository
     */
    protected $instituicaoRepository;

    /**
     * Construtor
     *
     * @param Solicitacao $model
     * @param SolicitacaoInstituicao $instituicao
     * @param SolicitacaoHistorico $historico
     * @param UserRepository $userRepository
     * @param InstituicaoRepository $instituicaoRepository
     */
    public function __construct(Solicitacao $model, SolicitacaoInstituicao $instituicao, SolicitacaoHistorico $historico, UserRepository $userRepository, InstituicaoRepository $instituicaoRepository)
    {
        $this->model = $model;
        $this->instituicao = $instituicao;
        $this->historico = $historico;
        $this->userRepository = $userRepository;
        $this->instituicaoRepository = $instituicaoRepository;
    }

    /**
     * Realiza a pesquisa por solicitações	
     *
     * @param string $search
     * @param array $filtros
     * @param boolean $limit
     * @return Collection
     */
    public function search(string $search = '', array $filtros = [], $limit = false)
    {
        $query = $this->model->with(['instituicao', 'instituicao.estado', 'solicitacaoInstituicao', 'solicitacaoInstituicao.estado', 'sexo', 'raca', 'escolaridade', 'deficiencia']);

        if ($search) {
            $query = $query->where(function ($q) use ($search) {
                $q->orWhere('nome', EncryptHelper::encrypt($search))
                  ->orWhere('email', EncryptHelper::encrypt($search))
                  ->orWhere('cpf', EncryptHelper::encrypt($search));
            });
        }

        if ((isset($filtros['instituicao']) && !empty($filtros['instituicao'])) || (isset($filtros['cnpj']) && !empty($filtros['cnpj']))) {
            $query = $query->where(function ($q) use ($filtros) {
                return $q->whereHas('solicitacaoInstituicao', function ($wh) use ($filtros) {
                    if (isset($filtros['instituicao']) && !empty($filtros['instituicao'])) {
                        $wh->where('razao_social', 'ilike', '%' . $filtros['instituicao'] . '%');
                    }

                    if (isset($filtros['cnpj']) && !empty($filtros['cnpj'])) {
                        $wh->where('cnpj', 'ilike', '%' . $filtros['cnpj'] . '%');
                    }

                    return $wh;
                })->orWhereHas('instituicao', function ($wh) use ($filtros) {
                    if (isset($filtros['instituicao']) && !empty($filtros['instituicao'])) {
                        $wh->where('razao_social', 'ilike', '%' . $filtros['instituicao'] . '%');
                    }

                    if (isset($filtros['cnpj']) && !empty($filtros['cnpj'])) {
                        $wh->where('cnpj', 'ilike', '%' . $filtros['cnpj'] . '%');
                    }

                    return $wh;
                });
            });
        }
        
        if (isset($filtros['id']) && !empty($filtros['id'])) {
            $query = $query->where('id', $filtros['id']);
        }

        if (isset($filtros['nome']) && !empty($filtros['nome'])) {
            $query = $query->where('nome', EncryptHelper::encrypt($filtros['nome']));
        }

        if (isset($filtros['email']) && !empty($filtros['email'])) {
            $query = $query->where('email', EncryptHelper::encrypt($filtros['email']));
        }

        if (isset($filtros['cpf']) && !empty($filtros['cpf'])) {
            $query = $query->where('cpf', EncryptHelper::encrypt(UtilHelper::limparCpfCnpj($filtros['cpf'])));
        }

        if (isset($filtros['situacao']) && !empty($filtros['situacao'])) {
            $query = $query->where('situacao', $filtros['situacao']);
        }
        
        if (isset($filtros['unidade_id']) && !empty($filtros['unidade_id'])) {
            $query = $query->where('unidade_id', $filtros['unidade_id']);
        }
        
        if (isset($filtros['data_cadastro_inicio']) && !empty($filtros['data_cadastro_inicio'])) {
            $query = $query->whereDate('created_at', '>=', $filtros['data_cadastro_inicio'] . ' 00:00:00');
        }

        if (isset($filtros['data_cadastro_fim']) && !empty($filtros['data_cadastro_fim'])) {
            $query = $query->whereDate('created_at', '<=', $filtros['data_cadastro_fim'] . ' 23:59:59');
        }

        if (isset($filtros['order']) && !empty($filtros['order']) && isset($filtros['orderBy']) && !empty($filtros['orderBy'])) {
            $query = $query->orderBy($filtros['order'], $filtros['orderBy']);
        } else {
            $query = $query->orderBy('id', 'desc');
        }

        if ($limit !== false) {
            return $query->paginate($limit);
        } else {
            return $query->get();
        }
    }

    /**
     * Realiza a pesquisa por solicitações do usuário logdo
     *
     * @param string $search
     * @param array $filtros
     * @param boolean $limit
     * @return Collection
     */
    public function minhasSolicitacoes(string $search = '', array $filtros = [], $limit = false)
    {
        $query = $this->model->with(['instituicao', 'solicitacaoInstituicao', 'sexo', 'raca', 'escolaridade', 'deficiencia']);

        $query = $query->where('user_id', auth()->user()->id);

        if ($search) {
            $query = $query->where(function ($q) use ($search) {
                $q->orWhere('nome', EncryptHelper::encrypt($search))
                  ->orWhere('email', EncryptHelper::encrypt($search))
                  ->orWhere('cpf', EncryptHelper::encrypt($search));
            });
        }
        
        if (isset($filtros['id']) && !empty($filtros['id'])) {
            $query = $query->where('id', $filtros['id']);
        }

        if (isset($filtros['situacao']) && !empty($filtros['situacao'])) {
            $query = $query->where('situacao', $filtros['situacao']);
        }
        
        if (isset($filtros['unidade_id']) && !empty($filtros['unidade_id'])) {
            $query = $query->where('unidade_id', $filtros['unidade_id']);
        }

        $query = $query->orderBy('created_at', 'desc');
        
        if ($limit !== false) {
            return $query->paginate($limit);
        } else {
            return $query->get();
        }
    }

    /**
     * Obtem os dados de uma solicitação
     *
     * @param integer $id
     * @return Solicitacao
     */
    public function find($id) : Solicitacao
    {
        return $this->model->with(['solicitacaoInstituicao', 'sexo', 'raca', 'escolaridade', 'deficiencia'])->where('id', $id)->first();
    }

    /**
     * Obtem os dados de uma solicitação por cpf
     *
     * @param string $id
     * @param integer $id
     * @return Solicitacao
     */
    public function findByCpf(string $cpf, int $id = 0) : ?Solicitacao
    {
        return $this->model
                    ->where('cpf', EncryptHelper::encrypt(UtilHelper::limparCpfCnpj($cpf)))
                    ->where('id', '<>', $id)
                    ->first();
    }

    /**
     * Realiza o cadastro / alteração de dados de um usuário
     *
     * @param array $data
     * @param boolean $sync
     * @return boolean
     */
    public function save(array $data, bool $sync = true) : bool
    {
        try {
            DB::beginTransaction();

            $arquivo['documento_rg'] = null;
            $arquivo['documento_cpf'] = null;
            $arquivo['documento_ato_posse'] = null;
            $arquivo['documento_ato_delegacao'] = null;

            // Armazena o documento de RG
            if (isset($data['documento_rg'])) {
                $documento = $this->salvarArquivo($data['documento_rg'], 'documentos/users');
                if ($documento && isset($documento['caminho'])) {
                    $data['documento_rg'] = $documento['caminho'];
                }
            }

            // Armazena o documento de CPF
            if (isset($data['documento_cpf'])) {
                $documento = $this->salvarArquivo($data['documento_cpf'], 'documentos/users');
                if ($documento && isset($documento['caminho'])) {
                    $data['documento_cpf'] = $documento['caminho'];
                }
            }

            // Armazena o documento de comprovação de nomeação/designação
            if (isset($data['documento_ato_posse'])) {
                $documento = $this->salvarArquivo($data['documento_ato_posse'], 'documentos/users');
                if ($documento && isset($documento['caminho'])) {
                    $data['documento_ato_posse'] = $documento['caminho'];
                }
            }

            // Armazena o documento de comprovação de nomeação/designação
            if (isset($data['documento_ato_delegacao'])) {
                $documento = $this->salvarArquivo($data['documento_ato_delegacao'], 'documentos/users');
                if ($documento && isset($documento['caminho'])) {
                    $data['documento_ato_delegacao'] = $documento['caminho'];
                }
            }

            if (isset($data['autoridade_rg'])) {
                $documento = $this->salvarArquivo($data['autoridade_rg'], 'documentos/users');
                if ($documento && isset($documento['caminho'])) {
                    $data['autoridade_rg'] = $documento['caminho'];
                }
            }

            if (isset($data['autoridade_cpf'])) {
                $documento = $this->salvarArquivo($data['autoridade_cpf'], 'documentos/users');
                if ($documento && isset($documento['caminho'])) {
                    $data['autoridade_cpf'] = $documento['caminho'];
                }
            }

            if (isset($data['autoridade_ato_posse'])) {
                $documento = $this->salvarArquivo($data['autoridade_ato_posse'], 'documentos/users');
                if ($documento && isset($documento['caminho'])) {
                    $data['autoridade_ato_posse'] = $documento['caminho'];
                }
            }

            if (isset($data['autoridade_ato_delegacao'])) {
                $documento = $this->salvarArquivo($data['autoridade_ato_delegacao'], 'documentos/users');
                if ($documento && isset($documento['caminho'])) {
                    $data['autoridade_ato_delegacao'] = $documento['caminho'];
                }
            }
            
            $data['cpf'] = UtilHelper::limparCpfCnpj($data['cpf']);

            // Verifica a existência do id para criar ou alterar
            if (isset($data['id']) && $data['id']) {
                // Insere a solicitação
                $enviado = $this->enviar($data['id'], 'Solicitação editada.');
                if (!$enviado) {
                    throw new \Exception('Falha ao salvar os dados da solicitação');
                }
                
                $data['situacao'] = Solicitacao::ST_PENDENTE;

                // Busca e altera os dados da solicitação
                $model = $this->model->find($data['id']);
                $model->fill($data)->save();
                
                // Salva os dados da instituição
                if (!$data['instituicao_id']) {
                    $instituicao = $this->instituicao->where('solicitacao_id', $model->id)->first();
                    if ($instituicao) {
                        $this->instituicao = $instituicao;
                    }
                    
                    $this->instituicao->fill([
                        'solicitacao_id' => $model->id,
                        'razao_social' => $data['ins_razao_social'],
                        'cnpj' => $data['ins_cnpj'],
                        'esfera' => $data['ins_esfera'],
                        'telefone' => $data['ins_telefone'],
                        'estado_id' => $data['ins_estado_id'],
                        'cidade_id' => $data['ins_cidade_id'],
                        'cep' => $data['ins_cep'], 
                        'endereco' => $data['ins_endereco'],
                        'bairro' => $data['ins_bairro'],
                        'numero' => $data['ins_numero'],
                        'complemento' => isset($data['ins_complemento']) ? $data['ins_complemento'] : null
                    ])->save();
                }
            } else {
                // Insere a solicitação
                $data['situacao'] = Solicitacao::ST_PENDENTE;
                $this->model->fill($data)->save();

                $historyNew = true;

                // Salva os dados da instituição
                if (!isset($data['instituicao_id']) || !$data['instituicao_id']) {
                    $this->instituicao->fill([
                        'solicitacao_id' => $this->model->id,
                        'razao_social' => $data['ins_razao_social'],
                        'cnpj' => $data['ins_cnpj'],
                        'esfera' => $data['ins_esfera'],
                        'telefone' => $data['ins_telefone'],
                        'estado_id' => $data['ins_estado_id'],
                        'cidade_id' => $data['ins_cidade_id'],
                        'cep' => $data['ins_cep'], 
                        'endereco' => $data['ins_endereco'],
                        'bairro' => $data['ins_bairro'],
                        'numero' => $data['ins_numero'],
                        'complemento' => isset($data['ins_complemento']) ? $data['ins_complemento'] : null
                    ])->save();
                }
                $model = $this->model;
            }

            $user = $this->userRepository->findByCPF($model->cpf);
            $dataUser = $data;
            if ($user) {
                $dataUser['id'] = $user->id;
            }
            
            $dataUser['tipo'] = User::TP_EXTERNO;

            $saveUser = $this->userRepository->save($dataUser, false);
            if (!$saveUser) {
                throw new \Exception('Falha ao salvar os dados do usuário');
            }

            // Atualiza os dados da solicitação vinculando ao usuário inserido
            if (!$user) {
                $user = $this->userRepository->findByCPF($model->cpf);
            }

            if (!$user || $user->cpf != $model->cpf) {
                throw new \Exception('Falha ao buscar os dados do usuário');
            }

            $model->fill([
                'user_id' => $user->id
            ])->save();

            if (isset($historyNew) && $historyNew) {
                $this->historico->fill([
                    'solicitacao_id' => $model->id,
                    'user_id' => $user->id,
                    'observacao' => 'Solicitação inserida.',
                    'situacao' => Solicitacao::ST_PENDENTE,
                    'instituicao_id' => $user->instituicao_id ?? null,
                ])->save();
            }

            if (!isset($data['id']) && !$data['id']) {
                if ($user->ativo) {
                    Mail::to($model->email)->send(new CredenciamentoCriado($model));
                }
            }

            DB::commit();
            return true;
        } catch (\Exception $ex) {
            DB::rollBack();
            report($ex);
            return false;
        }
    }

    /**
     * Aprova uma solicitação
     * 
     * @param integer $id
     * @return boolean
     */
    public function aprovar(int $id)
    {
        try{
            DB::beginTransaction();

            $aprovado = $this->alterarSituacao($id, Solicitacao::ST_APROVADO);
            $solicitacao = $this->model->find($id);

            if (!$aprovado) {
                DB::rollback();
                return false;
            }
    
            if ($aprovado && !$solicitacao->instituicao_id) {
                $instituicao = $this->instituicao->where('solicitacao_id', $solicitacao->id)->first();
                
                if ($instituicao) {
                    $existente = $this->instituicaoRepository->findByCnpj($instituicao->cnpj);

                    if ($solicitacao->tipo_representacao == 'A') {
                        $arquivoRg = $solicitacao->documento_rg;
                        $arquivoCpf = $solicitacao->documento_cpf;
                        $arquivoAtoPosse = $solicitacao->documento_ato_posse;
                        $arquivoAtoDelegacao = $solicitacao->documento_ato_delegacao;
                    } else {
                        $arquivoRg = $solicitacao->autoridade_rg;
                        $arquivoCpf = $solicitacao->autoridade_cpf;
                        $arquivoAtoPosse = $solicitacao->autoridade_ato_posse;
                        $arquivoAtoDelegacao = $solicitacao->autoridade_ato_delegacao;
                    }

                    if (empty($existente)) {
                        $instituicao = $this->instituicaoRepository->save([
                            'razao_social' => $instituicao->razao_social,
                            'cnpj' => $instituicao->cnpj,
                            'email' => $instituicao->email,
                            'telefone' => $instituicao->telefone,
                            'estado_id' => $instituicao->estado_id,
                            'cidade_id' => $instituicao->cidade_id,
                            'esfera' => $instituicao->esfera,
                            'cep' => $instituicao->cep,
                            'endereco' => $instituicao->endereco,
                            'bairro' => $instituicao->bairro,
                            'numero' => $instituicao->numero,
                            'complemento' => $instituicao->complemento,
                            'ativo' => true,
                            'autoridade_rg' => $arquivoRg,
                            'autoridade_cpf' => $arquivoCpf,
                            'autoridade_ato_posse' => $arquivoAtoPosse,
                            'autoridade_ato_delegacao' => $arquivoAtoDelegacao,
                            // alterar abaixo
                            'representantes' => [
                                [
                                    'tipo' => $solicitacao->tipo_representacao == 'A' ? 1 : 2,
                                    'nome' => $solicitacao->nome,
                                    'telefone' => $solicitacao->telefone_funcional,
                                    'email' => $solicitacao->email_funcional
                                ]
                            ]
                        ]);
                    } else {
                        $instituicao = $existente->id;
                    }
                }
            }

            if ($aprovado && $solicitacao->instituicao_id) {
                $instituicao = $solicitacao->instituicao_id;

                if ($solicitacao->tipo_representacao == 'A') {
                    $arquivoRg = $solicitacao->documento_rg;
                    $arquivoCpf = $solicitacao->documento_cpf;
                    $arquivoAtoPosse = $solicitacao->documento_ato_posse;
                    $arquivoAtoDelegacao = $solicitacao->documento_ato_delegacao;
                } else {
                    $arquivoRg = $solicitacao->autoridade_rg;
                    $arquivoCpf = $solicitacao->autoridade_cpf;
                    $arquivoAtoPosse = $solicitacao->autoridade_ato_posse;
                    $arquivoAtoDelegacao = $solicitacao->autoridade_ato_delegacao;
                }

                // update the instituicao
                $this->instituicaoRepository->save([
                    'id' => $instituicao,
                    'autoridade_rg' => $arquivoRg,
                    'autoridade_cpf' => $arquivoCpf,
                    'autoridade_ato_posse' => $arquivoAtoPosse,
                    'autoridade_ato_delegacao' => $arquivoAtoDelegacao,
                    'representantes' => [
                        [
                            'tipo' => $solicitacao->tipo_representacao == 'A' ? 1 : 2,
                            'nome' => $solicitacao->nome,
                            'telefone' => $solicitacao->telefone_funcional,
                            'email' => $solicitacao->email_funcional
                        ]
                    ]
                ]);
            }

            if (isset($instituicao)) {
                $user = User::find($solicitacao->user_id);
                $user->instituicao_id = $instituicao;
                $user->save();

                $user->instituicoes()->sync([$instituicao]);

                $perfil = Perfil::where('tipo', 3)->first();

                $existente = $user->perfis()->where('perfil_id', $perfil->id)->first();

                if ($existente) {
                    $existente->ativo = true;
                    $existente->save();
                } else {
                    $user->perfis()->create(['perfil_id' => $perfil->id]);
                }

                $user->perfis()->where('perfil_id', '!=', $perfil->id)->update([
                    'ativo' => false
                ]);
            }

            DB::commit();
            return $aprovado;
        } catch(\Exception $ex) {
            DB::rollback();
            report($ex);
            return false;
        }
    }

    /**
     * Reprova uma solicitação
     * 
     * @param integer $id
     * @return boolean
     */
    public function reprovar(int $id, $data)
    {
        return $this->alterarSituacao($id, Solicitacao::ST_REPROVADO, $data['observacao'], $data['anexo']);
    }    
    
    /**
    * Justifica uma solicitação
    * 
    * @param integer $id
    * @return boolean
    */
   public function justificar(int $id, $data)
   {
       return $this->alterarSituacao($id, Solicitacao::ST_PENDENTE, $data['observacao'], $data['anexo']);
   }

    /**
     * Devolve uma solicitação
     * 
     * @param integer $id
     * @return boolean
     */
    public function devolver(int $id, $data)
    {
        return $this->alterarSituacao($id, Solicitacao::ST_DEVOLVIDO, $data['observacao'], $data['anexo']);
    }

    /**
     * Envia uma solicitação
     * 
     * @param integer $id
     */
    public function enviar(int $id, $observacao)
    {
        return $this->alterarSituacao($id, Solicitacao::ST_PENDENTE, $observacao);
    }

    /**
     * Altera a situação de uma solicitação
     *
     * @param integer $id
     * @param string $situacao
     * @param string $observacao
     * @return boolean
     */
    protected function alterarSituacao(int $id, string $situacao, string $observacao = '', $anexo = null) : bool
    {
        try {
            DB::beginTransaction();

            // Aprova a solicitação
            $model = $this->model->find($id);
            $model->situacao = $situacao;
            $model->save();

            if ($anexo) {
                $documento = $this->salvarArquivo($anexo, 'documentos/solicitacoes');
                if ($documento && isset($documento['caminho'])) {
                    $anexo = $documento['caminho'];
                }
            }

            $contexto = ContextoRepository::contextoLogado();

            // Salva o histórico
            $this->historico->fill([
                'solicitacao_id' => $id,
                'user_id' => $contexto['user']?->id,
                'observacao' => $observacao,
                'instituicao_id' => $contexto['user']?->instituicao_id,
                'contexto_id' => $contexto['contexto']?->id,
                'anexo' => $anexo,
                'situacao' => $situacao
            ])->save();

            if ($situacao !== Solicitacao::ST_PENDENTE) {
                if ($model->user->ativo) {
                    Mail::to($model->email)->send(new CredenciamentoSituacao($model, $observacao, $situacao, $anexo));
                }
            }
            
            DB::commit();
            return true;
        } catch (\Exception $ex) {
            report($ex);
            return false;
        }
    }

    /**
     * Remove uma solicitação
     *
     * @param integer $id
     * @return boolean
     */
    public function delete($id) : bool
    {
        try {
            DB::beginTransaction();
            
            // Busca o usuário e remove a relação com os perfis
            $user = $this->model->find($id);
            $user->solicitacaoInstituicao()->delete();
            $user->historicos()->delete();
            $user->delete();

            DB::commit();
            return true;
        } catch (\Exception $ex) {
            report($ex);
            DB::commit();
            return false;
        }
    }

    /**
     * Lista o histórico de uma solicitação
     * 
     * @param integer $id
     * @return Collection
     */
    public function listarHistorico(int $id)
    {
        return $this->historico
                    ->with([
                        'user',
                        'instituicao',
                        'contexto.unidade',
                        'contexto.perfil'
                    ])
                    ->where('solicitacao_id', $id)
                    ->orderBy('created_at', 'desc')
                    ->get();
    }

    public function exportExcel(array $filtros = [])
    {
        ini_set('memory_limit', '2G');
        set_time_limit(0);

        $dados = $this->search(isset($filtros['filter']) ? $filtros['filter'] : '', $filtros)->toArray();
        return Excel::download(new SolicitacaoExport($dados), 'solicitacoes.xlsx');
    }

    public function exportPDF(array $filtros = [])
    {
        ini_set('memory_limit', '2G');
        set_time_limit(0);

        $dados = $this->search(isset($filtros['filter']) ? $filtros['filter'] : '', $filtros)->toArray();
        $pdf = PDF::loadView("solicitacao.export-pdf-search", [
            'filtros' => $filtros,
            'data'  => $dados
        ])->setPaper('a4', 'landscape');

        return $pdf->download('solicitacoes.pdf');
    }
}