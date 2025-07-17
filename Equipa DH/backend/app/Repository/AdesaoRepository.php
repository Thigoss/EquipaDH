<?php

namespace App\Repository;

use App\Exports\AdesaoExport;
use App\Exports\InstituicoesHabilitadasExport;
use App\Mail\AdesaoCriado;
use App\Mail\AdesaoSituacao;
use App\Models\Adesao;
use App\Models\AdesaoHistorico;
use App\Models\AdesaoRecurso;
use App\Models\AdesaoRecursoArquivo;
use App\Models\CronogramaArquivoClassificacaoEstadual;
use App\Models\CronogramaArquivoClassificacaoMunicipal;
use App\Models\PerfilUser;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Illuminate\Database\Eloquent\Collection;
use DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use PDF;
use Excel;
use Exception;

/**
 * Classe de manipulação da entidade de adesões
 *
 * @author Eduardo Praxedes Heinske (eduardo.praxedes@jointecnologia.com.br)
 */
class AdesaoRepository extends BaseRepository
{

    /**
     * Model de adesão
     *
     * @var Adesao
     */
    protected $model;

    /**
     * Model de histórico
     *
     * @var AdesaoHistorico
     */
    protected $historico;

    /**
     * Model de recurso
     *
     * @var AdesaoRecurso
     */
    protected $recurso;

    /**
     * Model de recurso arquivo
     *
     * @var AdesaoRecurso
     */
    protected $recursoArquivo;

    /**
     * Construtor
     *
     * @param Adesao $adesao
     * @param AdesaoHistorico $historico
     * @param AdesaoRecurso $recurso
     * @param AdesaoRecurso $recursoArquivo
     */
    public function __construct(Adesao $adesao, AdesaoHistorico $historico, AdesaoRecurso $recurso, AdesaoRecursoArquivo $recursoArquivo)
    {
        $this->model = $adesao;
        $this->historico = $historico;
        $this->recurso = $recurso;
        $this->recursoArquivo = $recursoArquivo;
    }

    /**
     * Realiza a pesquisa por adesões
     *
     * @param string $search
     * @param array $filtros
     * @param boolean $limit
     * @return Collection
     */
    public function search(string $search = '', array $filtros = [], $limit = false, $minhas = false, $habilitadas = false)
    {
        $query = $this->model->with(['instituicao', 'instituicao.estado', 'cronograma', 'cronograma.programa', 'instituicao.cidade']);

        if ($habilitadas) {
            $query = $query->where('situacao', "AP");

            if (isset($filtros['statusConvocacao'])) {
                $query = $query->where('convocado', (bool) $filtros['statusConvocacao']);
            }
        }

        // check de contexto para procurar apenas as adesões a cronogramas dos programas da unidade do user
        $contexto = ContextoRepository::contextoLogado();
        if ($contexto['contexto']->perfil->tipo != PerfilUser::TP_ADMIN && !$minhas) {
            $query = $query->whereHas('cronograma', function ($query) use ($contexto) {
                $query->whereHas('programa', function ($query) use ($contexto) {
                    if ($contexto['contexto']['unidade']) {
                        $query->where('unidade_id', $contexto['contexto']['unidade']->id);
                    }
                });
            });
        }

        if ($minhas) {
            $query = $query->where('instituicao_id', auth()->user()->instituicao_id);
        }

        if ($search) {
            $query = $query->where(function ($query) use ($search) {
                $query->orWhereHas('user', function ($query) use ($search) {
                    $query->where('nome', 'ilike', "%$search%");
                })
                    ->orWhereHas('instituicao', function ($query) use ($search) {
                        $query->where('razao_social', 'ilike', "%$search%");
                    })
                    ->orWhereHas('cronograma.programa', function ($query) use ($search) {
                        $query->where('nome', 'ilike', "%$search%");
                    });
            });
        }

        if (!empty($filtros['programa_id'])) {
            $query = $query->whereHas('cronograma', function ($query) use ($filtros) {
                $query->where('programa_id', $filtros['programa_id']);
            });
        }

        if (!empty($filtros['id'])) {
            $query = $query->where('id', $filtros['id']);
        }

        if (!empty($filtros['tipo'])) {
            $query = $query->where('tipo', $filtros['tipo']);
        }

        if (!empty($filtros['data_cadastro_inicio'])) {
            $query = $query->where('created_at', '>=', $filtros['data_cadastro_inicio']);
        }

        if (!empty($filtros['data_cadastro_fim'])) {
            $query = $query->where('created_at', '<=', $filtros['data_cadastro_fim']);
        }

        if (!empty($filtros['situacao'])) {
            $query = $query->where('situacao', $filtros['situacao']);
        }

        if (!empty($filtros['cnpj'])) {
            $query = $query->whereHas('instituicao', function ($query) use ($filtros) {
                $query->where('cnpj', 'ilike', '%' . $filtros['cnpj'] . '%');
            });
        }

        if (!empty($filtros['estado_id'])) {
            $query = $query->whereHas('instituicao', function ($query) use ($filtros) {
                $query->where('estado_id', '=', $filtros['estado_id']);

                if (!empty($filtros['cidade_id'])) {
                    $query->where('cidade_id', '=', $filtros['cidade_id']);
                }
            });
        }

        if (!empty($filtros['esfera'])) {
            $query = $query->whereHas('instituicao', function ($query) use ($filtros) {
                $query->where('esfera', '=', $filtros['esfera']);
            });
        }

        if (!empty($filtros['numero_cronograma'])) {
            $query = $query->whereHas('cronograma', function ($query) use ($filtros) {
                $query->where('numero', $filtros['numero_cronograma']);
            });
        }

        if (!empty($filtros['order']) && !empty($filtros['orderBy'])) {
            $query = $query->orderBy($filtros['order'], $filtros['orderBy']);
        } else {
            $query = $query->orderBy('classificacao', 'asc');
        }

        if ($limit !== false) {
            return $query->paginate($limit);
        } else {
            return $query->get();
        }
    }

    /**
     * Busca os dados de uma adesão
     * 
     * @param  $id
     * @return Adesao
     */
    public function find($id): ?Adesao
    {
        return $this->model->with(['cronograma', 'cronograma.programa', 'historico', 'historico.user'])->find(intval($id));
    }

    /**
     * Verifica se já existe uma adesão para o cronograma
     * 
     * @param int $cronograma
     * @return int
     */
    public function verificar(int $cronograma): int
    {
        return $this->model
            ->where('cronograma_id', $cronograma)
            ->where('instituicao_id', auth()->user()->instituicao_id)
            ->count();
    }

    /**
     * Lista os históricos de uma adesão
     * 
     * @param int $id
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
            ->where('adesao_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Realiza o cadastro / alteração de dados de uma adesão
     *
     * @param array $data
     * @return boolean
     */
    public function save(array $data): bool
    {
        try {
            DB::beginTransaction();

            if (isset($data['arquivo'])) {
                $documento = $this->salvarArquivo($data['arquivo'], 'documentos/adesao');

                if ($documento['success'] && $documento['caminho']) {
                    $data['arquivo'] = $documento['caminho'];
                }
            }

            if (isset($data['formulario_habilitacao'])) {
                $documento = $this->salvarArquivo($data['formulario_habilitacao'], 'documentos/adesao');
                if ($documento['success'] && $documento['caminho']) {
                    $data['formulario_habilitacao'] = $documento['caminho'];
                }
            }

            // Verifica a existência do id para criar ou alterar
            if (isset($data['id']) && $data['id']) {

                // Busca e altera os dados da adesão
                $model = $this->model->find($data['id']);

                $data['ciclo'] = $model->ciclo + 1;
                $model->fill($data)->save();
            } else {

                // Insere a adesão
                $data['user_id'] = auth()->user()->id;
                $data['instituicao_id'] = auth()->user()->instituicao_id;
                $data['situacao'] = Adesao::ST_PENDENTE;
                $data['tipo'] = Adesao::TP_ADESAO;
                $data['habilitado'] = false;
                $data['classificacao'] = false;
                $data['convocado'] = false;
                $data['ciclo'] = 1;
                $this->model->fill($data)->save();
                $model = $this->model;

                $instituicao = $model->instituicao;
                $users = $instituicao->users;
                foreach ($users as $user) {
                    if ($user->ativo) {
                        Mail::to($user->email)->send(new AdesaoCriado($model, $user));
                        foreach ($user->solicitacoes as $solicitacao) {
                            Mail::to($solicitacao->email)->send(new AdesaoCriado($model, $solicitacao));
                        }
                    }
                }
            }

            // Atualiza a situação
            $model->situacao = Adesao::ST_PENDENTE;
            $model->save();

            $contexto = ContextoRepository::contextoLogado();

            $this->historico->fill([
                'user_id' => $contexto['user']->id,
                'instituicao_id' => $contexto['user']->instituicao_id,
                'contexto_id' => $contexto['contexto']->contexto_atual_id,
                'adesao_id' => $model->id,
                'tipo' => $model->tipo,
                'situacao' => $model->situacao,
                'ciclo' => $model->ciclo,
                'arquivo' => $model->arquivo,
                'formulario_habilitacao' => $model->formulario_habilitacao,
            ])->save();

            DB::commit();
            return true;
        } catch (\Exception $ex) {
            report($ex);
            return false;
        }
    }

    /**
     * Aprova a adesão
     * 
     * @param integer $id
     * @return boolean
     */
    public function aprovar(int $id)
    {
        return $this->alterarSituacao($id, Adesao::ST_APROVADO);
    }

    /**
     * Recusa a adesão
     * 
     * @param integer $id
     * @return boolean
     */
    public function recusar(int $id, $data)
    {
        return $this->alterarSituacao($id, Adesao::ST_RECUSADO, $data['observacao'], isset($data['anexo']) ? $data['anexo'] : null);
    }

    /**
     * Devolve uma solicitação
     * 
     * @param integer $id
     * @return boolean
     */
    public function devolver(int $id, $data)
    {
        return $this->alterarSituacao($id, Adesao::ST_DEVOLVIDO, $data['observacao'], isset($data['anexo']) ? $data['anexo'] : null);;
    }

    /**
     * Altera a situação de uma adesão
     *
     * @param integer $id
     * @param string $situacao
     * @param string $justificativa
     * @return boolean
     */
    protected function alterarSituacao(int $id, string $situacao, string $justificativa = '', $anexo = null): bool
    {
        try {
            DB::beginTransaction();

            $model = $this->model->find($id);

            // Altera a situação da adesão
            $model->situacao = $situacao;

            // Habilita caso aprovado o tipo de adesão ou recurso de adesão
            if ($model->situacao === Adesao::ST_APROVADO && ($model->tipo == Adesao::TP_ADESAO || $model->tipo == Adesao::TP_RECURSO_ADESAO)) {
                $model->habilitado = true;
            }

            $model->save();

            $arquivo  = null;
            $habilitacao = null;

            if ($model->tipo == Adesao::TP_ADESAO) {
                $arquivo = $model->arquivo;
                $habilitacao = $model->formulario_habilitacao;
            }

            if ($model->tipo == Adesao::TP_CONVOCACAO) {
                $arquivo = $model->termo_convocacao;
            }

            if ($anexo) {
                $documento = $this->salvarArquivo($anexo, 'documentos/adesao');
                if ($documento && isset($documento['caminho'])) {
                    $anexo = $documento['caminho'];
                }
            }

            $instituicao = $model->instituicao;
            $users = $instituicao->users;
            if ($situacao !== Adesao::ST_PENDENTE) {
                foreach ($users as $user) {
                    if ($user->ativo) {
                        Mail::to($user->email)->send(new AdesaoSituacao($model, $user, $justificativa, $situacao, $anexo));
                        foreach ($user->solicitacoes as $solicitacao) {
                            Mail::to($solicitacao->email)->send(new AdesaoCriado($model, $solicitacao));
                        }
                    }
                }
            }

            $contexto = ContextoRepository::contextoLogado();

            $this->historico->fill([
                'user_id' => $contexto['user']->id,
                'instituicao_id' => $contexto['user']->instituicao_id,
                'contexto_id' => $contexto['contexto']->contexto_atual_id,
                'adesao_id' => $id,
                'tipo' => $model->tipo,
                'situacao' => $situacao,
                'justificativa' => $justificativa,
                'ciclo' => $model->ciclo,
                'arquivo' => $arquivo,
                'formulario_habilitacao' => $habilitacao,
                'anexo' => $anexo
            ])->save();

            DB::commit();
            return true;
        } catch (\Exception $ex) {
            report($ex);
            return false;
        }
    }

    /**
     * Lista os habilitados
     * 
     * @param integer $cronograma
     * @return Collection
     */
    public function listarHabilitados(int $cronograma, $limit = false)
    {
        $query = $this->model
            ->with(['instituicao', 'instituicao.estado', 'instituicao.cidade'])
            ->where('cronograma_id', $cronograma)
            ->where('habilitado', true);

        if ($limit !== false) {
            return $query->paginate($limit);
        } else {
            return $query->get();
        }
    }

    /**
     * Solicita um recurso
     * 
     * @param integer $id
     * @param string $justificativa
     * @param array $arquivos
     * @param string $tipo
     * @return boolean
     */
    public function solicitarRecurso(int $id, string $justificativa, array $arquivos = [], string $tipo): bool
    {
        try {
            DB::beginTransaction();

            // Cadastra o recurso
            $this->recurso->fill([
                'user_id' => auth()->user()->id,
                'adesao_id' => $id,
                'tipo' => $tipo,
                'justificativa' => $justificativa
            ])->save();

            // Salva os arquivos
            foreach ($arquivos as $arquivo) {
                $documento = $this->salvarArquivo($arquivo['arquivo'], 'documentos/recursos');
                if ($documento && $documento['caminho']) {
                    $recursoArquivo = new AdesaoRecursoArquivo();
                    $recursoArquivo->fill([
                        'adesao_recurso_id' => $this->recurso->id,
                        'nome' => $arquivo['nome'],
                        'arquivo' => $documento['caminho']
                    ])->save();
                }
            }

            // Altera o tipo e ciclo
            $model = $this->model->find($id);
            $model->ciclo = $model->tipo == Adesao::TP_ADESAO ? $model->ciclo + 1 : 1;
            $model->tipo = $tipo;
            $model->save();

            // Altera a situação
            $this->alterarSituacao($id, Adesao::ST_PENDENTE);

            DB::commit();
            return true;
        } catch (\Exception $ex) {
            DB::rollback();
            report($ex);
            return false;
        }
    }

    /**
     * Visualiza um recurso
     * 
     * @param integer $id
     */
    public function visualizarRecurso(int $id, string $tipo): ?AdesaoRecurso
    {
        return $this->recurso->with('arquivos')->where('tipo', $tipo)->where('adesao_id', $id)->latest('id')->first();
    }

    /**
     * Realiza a classificação de todos os habilitados do cronograma
     * 
     * @param integer $cronograma
     * @return boolean
     */
    public function classificar(int $cronograma, $data)
    {
        try {
            DB::beginTransaction();

            $query = $this->model->with(['instituicao', 'instituicao.estado', 'instituicao.cidade'])
                ->where('cronograma_id', $cronograma)
                ->where('situacao', Adesao::ST_APROVADO)
                ->where('habilitado', true);

            $arquivoClassificacao = null;
            $msg = 'Classificação realizada com sucesso!';

            // filtra as adesões alvos
            if (isset($data['tipo']) && $data['tipo'] == 'estadual') {
                $query = $query->whereHas('instituicao', function ($query) {
                    $query->where('esfera', 'ED');
                });

                $arquivoClassificacao = CronogramaArquivoClassificacaoEstadual::where('cronograma_id', $cronograma)->where('ativo', true)->first();

                if (!$arquivoClassificacao) {
                    throw new \Exception('Não foi encontrado um arquivo de classificação estadual/distrital ativo para o cronograma!');
                }

                $msg = 'Classificação Estadual/Distrital realizada com sucesso!';
            }

            if (isset($data['tipo']) && $data['tipo'] == 'municipal') {
                $query = $query->whereHas('instituicao', function ($query) {
                    $query->where('esfera', 'MU');
                });

                $arquivoClassificacao = CronogramaArquivoClassificacaoMunicipal::where('cronograma_id', $cronograma)->where('ativo', true)->first();

                if (!$arquivoClassificacao) {
                    throw new \Exception('Não foi encontrado um arquivo de classificação municipal ativo para o cronograma!');
                }

                $msg = 'Classificação Municipal realizada com sucesso!';
            }

            $adesoes = $query->get();

            Log::debug(sizeof($adesoes));

            if (!sizeof($adesoes)) {
                throw new \Exception('Não foram encontradas adesões habilitadas para classificação!');
            }

            // colhe os objetos de classificação
            $handle = fopen(public_path($arquivoClassificacao->arquivo), 'r');
            if (!$handle) {
                throw new \Exception("Erro ao abrir o arquivo de classificação!");
            }

            // lê o arquivo e monta o array de classificação
            $firstLine = fgets($handle);
            $delimiter = strpos($firstLine, ';') !== false ? ';' : ',';
            rewind($handle);
            $headers = fgetcsv($handle, 0, $delimiter);
            $headers = array_filter($headers, function ($value) {
                return $value !== '';
            });

            if ($data['tipo'] == 'estadual') {
                $parametros = array_slice($headers, 1);
            } elseif ($data['tipo'] == 'municipal') {
                $parametros = array_slice($headers, 3);
            }

            $arrayClassifica = [];

            while (($row = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
                if (!empty(array_filter($row))) {
                    $trimmedRow = array_slice($row, 0, count($headers));

                    // insere a key como o validador sigla ou codigo_ibge
                    $arrayClassifica[$data['tipo'] == 'estadual' ? $row[0] : $row[2]] = array_combine($headers, $trimmedRow);
                }
            }

            if (!sizeof($arrayClassifica)) {
                throw new \Exception("Não foi possível carregar os dados do arquivo de classificação!");
            }

            foreach ($adesoes as $kA => $adesao) {
                if ($data['tipo'] == 'estadual' && !isset($arrayClassifica[$adesao->instituicao->estado->sigla])) {
                    throw new \Exception("Não foi possível encontrar a classificação para a Instituição: {$adesao->instituicao->razao_social} UF: {$adesao->instituicao->estado->sigla}!");
                }

                if ($data['tipo'] == 'municipal' && !isset($arrayClassifica[$adesao->instituicao->cidade->codigo_ibge])) {
                    throw new \Exception("Não foi possível encontrar a classificação para a Instituição: {$adesao->instituicao->razao_social} Código IBGE: {$adesao->instituicao->cidade->codigo_ibge}!");
                }

                $classificacao = $data['tipo'] == 'estadual' ? $arrayClassifica[$adesao->instituicao->estado->sigla] : $arrayClassifica[$adesao->instituicao->cidade->codigo_ibge];

                $media = 0.0;
                foreach ($parametros as $kP => $param) {
                    $media += (float) $classificacao[$param];
                }

                $adesao->media_classificacao = $media;
                $adesao->save();

                if ($adesao->instituicao->esfera == null) {
                    Log::debug("Instituição sem esfera: {$adesao->instituicao->razao_social} CNPJ: {$adesao->instituicao->cnpj}");
                }
            }

            $adesoesPosMedia = $query->whereNotNull('media_classificacao');
            $adesoesPosMedia = $query->orderBy('media_classificacao', 'DESC')->get();

            foreach ($adesoesPosMedia as $kAP => $adesao) {
                $adesao->classificacao = $kAP + 1;
                $adesao->save();
            }

            DB::commit();
            return ['success' => true, 'msg' => $msg];
        } catch (\Exception $ex) {
            DB::rollback();
            report($ex);
            return ['success' => false, 'msg' => $ex->getMessage()];
        }
    }

    /**
     * Lista os classificados
     * 
     * @param integer $cronograma
     * @return Collection
     */
    public function listarClassificados(int $cronograma, $filters, $limit = false)
    {

        $query = $this->model->with(['instituicao', 'instituicao.estado', 'instituicao.cidade'])
            ->where('cronograma_id', $cronograma)
            ->where('situacao', Adesao::ST_APROVADO)
            ->where('habilitado', true);

        if (isset($filters['estadual']) && $filters['estadual']) {
            $query = $query->whereHas('instituicao', function ($query) {
                $query->where('esfera', 'ED');
            });
        }

        if (isset($filters['municipal']) && $filters['municipal']) {
            $query = $query->whereHas('instituicao', function ($query) {
                $query->where('esfera', 'MU');
            });
        }

        $query = $query->orderByRaw('CAST(classificacao AS INTEGER) asc');

        if ($limit !== false) {
            return $query->paginate($limit);
        } else {
            return $query->get();
        }
    }

    public function exportPDFClassificados($classificados, $estadual = false)
    {
        ini_set('memory_limit', '2G');
        set_time_limit(0);

        $tipo = $estadual ? 'estadual' : 'municipal';

        $pdf = FacadePdf::loadView("classificados.export-pdf", [
            'data' => $classificados
        ])->setPaper('a4');

        return $pdf->download("classificados_$tipo.pdf");
    }

    /**
     * Convoca um classificado
     * 
     * @param integer $id
     * @return boolean
     */
    public function convocar(int $id): bool
    {
        try {
            DB::beginTransaction();

            // Altera a situação de convocação e tipo da adesão
            $model = $this->model->find($id);
            $model->convocado = true;
            $model->tipo = Adesao::TP_CONVOCACAO;
            $model->save();

            // Altera a situação
            $this->alterarSituacao($id, Adesao::ST_CONVOCADO);

            DB::commit();
            return true;
        } catch (\Exception $ex) {
            report($ex);
            return false;
        }
    }

    /**
     * Envia o termo de convocação
     * 
     * @param integer $id
     * @param string $arquivo
     * @return boolean
     */
    public function enviarTermo(int $id, string $arquivo): bool
    {
        try {
            DB::beginTransaction();

            // Sobe o tempo_convocacao            
            if ($arquivo) {
                $documento = $this->salvarArquivo($arquivo, 'documentos/convocacao');
                if ($documento && $documento['caminho']) {
                    $arquivo = $documento['caminho'];
                }
            }

            $model = $this->model->find($id);
            $model->tipo = Adesao::TP_CONVOCACAO;
            $model->termo_convocacao = $arquivo;
            $model->ciclo = $model->ciclo ? $model->ciclo + 1 : 1;
            $model->save();

            // Altera a situação
            $this->alterarSituacao($id, Adesao::ST_PENDENTE);

            DB::commit();
            return true;
        } catch (\Exception $ex) {
            report($ex);
            return false;
        }
    }

    /**
     * Lista os convocados
     * 
     * @param integer $cronograma
     * @return Collection
     */
    public function listarConvocados(int $cronograma, $limit = false)
    {
        $query = $this->model
            ->with(['instituicao', 'instituicao.estado', 'instituicao.cidade'])
            ->where('cronograma_id', $cronograma)
            ->where('convocado', true);

        $query = $query->orderByRaw('CAST(classificacao AS INTEGER) asc');

        if ($limit !== false) {
            return $query->paginate($limit);
        } else {
            return $query->get();
        }
    }

    /** 
     * Troca a instituição de uma adesão
     */
    public function changeInstitute($id, $institute)
    {
        try {
            DB::beginTransaction();

            $model = $this->model->find($id);

            if (!$model) {
                throw new Exception('Adesão não econtrada!');
            }

            $model->instituicao_id = $institute;
            $model->save();

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function exportExcel(array $filtros = [])
    {
        ini_set('memory_limit', '2G');
        set_time_limit(0);

        $dados = $this->search(isset($filtros['filter']) ? $filtros['filter'] : '', $filtros)->toArray();
        return Excel::download(new AdesaoExport($dados), 'adesoes.xlsx');
    }

    public function exportPDF(array $filtros = [])
    {
        ini_set('memory_limit', '2G');
        set_time_limit(0);

        $dados = $this->search(isset($filtros['filter']) ? $filtros['filter'] : '', $filtros)->toArray();
        $pdf = PDF::loadView("adesao.export-pdf-search", [
            'filtros' => $filtros,
            'data'  => $dados
        ])->setPaper('a4', 'landscape');

        return $pdf->download('adesoes.pdf');
    }

    public function exportHabilitadas(array $dados = [])
    {
        ini_set('memory_limit', '2G');
        set_time_limit(0);

        return Excel::download(new InstituicoesHabilitadasExport($dados), 'instituicoes-habilitadas.xlsx');
    }
}
