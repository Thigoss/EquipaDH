<?php

namespace App\Repository;

use App\Exports\UserExport;
use App\Helpers\EncryptHelper;
use App\Helpers\UtilHelper;
use App\Models\Perfil;
use App\Models\PerfilUser;
use App\Service\Auth\Type\User as TypeUser;
use App\Service\Auth\Contract\ProfileInterface;
use App\Service\Auth\Contract\UserInterface;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use Illuminate\Support\Facades\Log;

/**
 * Classe de manipulação da entidade de usuário
 *
 * @author Eduardo Praxedes Heinske (eduardo.praxedes@jointecnologia.com.br)
 */
class UserRepository extends BaseRepository
{

    /**
     * Model de user
     *
     * @var User
     */
    protected $model;

    /**
     * Model de perfis
     *
     * @var PerfilUser
     */
    protected $perfis;

    /**
     * Service de usuários no autenticador
     *
     * @var UserInterface
     */
    protected $userService;

    /**
     * Service de perfis no autenticador
     *
     * @var ProfileInterface
     */
    protected $profileService;
    
    /**
     * Construtor
     *
     * @param User $user
     * @param PerfilUser $perfilUser
     * @param UserInterface $userService
     * @param ProfileInterface $profileService
     */
    public function __construct(User $user, PerfilUser $perfilUser, UserInterface $userService, ProfileInterface $profileService)
    {
        $this->model = $user;
        $this->perfis = $perfilUser;
        $this->userService = $userService;
        $this->profileService = $profileService;
    }


    /**
     * Realiza a pesquisa por usuários
     *
     * @param string $search
     * @param array $filtros
     * @param boolean $limit
     * @return Collection
     */
    public function search(string $search = '', array $filtros = [], $limit = false)
    {
        $query = $this->model->with([
            'perfis' => function ($q) {
                $q->where('ativo', true);
            },
            'perfis.perfil',
            'perfis.unidade',
            'instituicoes',
            'instituicao',
            'sexo',
            'raca',
            'escolaridade',
            'deficiencia'
        ]);

        if ($search) {
            $query = $query->where(function ($q) use ($search) {
                $q->orWhere('nome', EncryptHelper::encrypt($search))
                  ->orWhere('email', EncryptHelper::encrypt($search))
                  ->orWhere('cpf', EncryptHelper::encrypt($search));
            });
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

        if (isset($filtros['perfil_id']) && !empty($filtros['perfil_id'])) {
            $query = $query->whereHas('perfis', function ($q) use ($filtros) {
                $q->where('perfil_id', $filtros['perfil_id'])
                    ->where('ativo', true);
            });
        }
        
        if (isset($filtros['unidade_id']) && !empty($filtros['unidade_id'])) {
            $query = $query->whereHas('perfis', function ($q) use ($filtros) {
                $q->where('unidade_id', $filtros['unidade_id']);
            });
        }

        if (isset($filtros['instituicao_id']) && !empty($filtros['instituicao_id'])) {
            $query = $query->where('instituicao_id', $filtros['instituicao_id']);
        }
        
        if (isset($filtros['tipo'])) {
            $query = $query->where('tipo', $filtros['tipo']);
        }

        if (isset($filtros['situacao'])) {
            $query = $query->where('ativo', $filtros['situacao']);
        }

        $query = $query->orderBy('id', 'desc');
        if ($limit !== false) {
            return $query->paginate($limit);
        } else {
            return $query->get();
        }
    }

    /**
     * Obtem os dados e um usuário específico
     *
     * @param integer $id
     * @return User
     */
    public function find($id) : User
    {
        return $this->model->with(['perfis', 'perfis.perfil', 'perfis.unidade', 'instituicao', 'solicitacoes', 'sexo', 'raca', 'escolaridade', 'deficiencia'])->where('id', $id)->first();
    }

    /**
     * Busca por CPF
     *
     * @param string $cpf
     * @param integer $id
     * @return User|null
     */
    public function findByCPF(string $cpf, int $id = 0) : ?User
    {
        return $this->model->where('cpf', EncryptHelper::encrypt($cpf))->where('id', '<>', $id)->first();
    }

    public function getByUnidade(int $id)
    {
        return $this->model->with(['perfis', 'perfis.perfil', 'perfis.unidade'])->whereHas('perfis', function ($q) use ($id) {
            $q->where('unidade_id', $id);
        })->get();
    }

    public function getByInstituicao(int $id)
    {
        return $this->model->with(['perfis.perfil'])->whereHas('instituicoes', function ($q) use ($id) {
            $q->where('instituicao_id', $id);
        })->get();
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

            $perfisNovos = [];
            $perfisAntigos = [];

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

            if (isset($data['cpf'])) {
                $data['cpf'] = UtilHelper::limparCpfCnpj($data['cpf']);
            }

            // Verifica a existência do id para criar ou alterar
            if (isset($data['id']) && $data['id']) {
                
                // Busca e altera os dados do usuário
                $model = $this->model->find($data['id']);

                // Verifica o tipo e remove os campos que não são necessários
                if (isset($data['tipo']) && $data['tipo'] == $this->model::TP_INTERNO) {
                    $data['tipo_representacao'] = null;
                    $data['documento_rg'] = null;
                    $data['documento_cpf'] = null;
                    $data['documento_ato_posse'] = null;
                    $data['documento_ato_delegacao'] = null;
                    $data['instituicao_id'] = null;
                }

                if (isset($data['tipo']) && $data['tipo'] == $this->model::TP_EXTERNO) {
                    $data['unidade_id'] = null;
                }

                $model->fill($data)->save();
                
                // Renova a relação com os perfis
                $perfis = Perfil::all(); 
                $perfisAntigos = [];
                foreach ($perfis as $perfil) {
                    if ($perfil->codigo) {
                        $perfisAntigos[] = $perfil->codigo;
                    }
                }
                
                if (isset($data['perfil_id']) && $data['perfil_id']) {
                    $perfil = Perfil::find($data['perfil_id']);
                    $perfisNovos[] = $perfil->codigo;

                    $latestVinculo = PerfilUser::where('user_id', $model->id)
                        ->where('perfil_id', $perfil->id)
                        ->where('unidade_id', isset($data['unidade_id']) && $data['unidade_id'] ? $data['unidade_id'] : null)
                        ->orderByDesc('id')
                        ->first();

                    if ($latestVinculo) {
                        PerfilUser::where('user_id', $model->id)
                            ->where('perfil_id', $perfil->id)
                            ->where('unidade_id', isset($data['unidade_id']) && $data['unidade_id'] ? $data['unidade_id'] : null)
                            ->where('id', '!=', $latestVinculo->id ?? 0)
                            ->delete();
                    }

                    if (!$latestVinculo) {
                        $this->perfis->fill([
                            'user_id' => $model->id,
                            'perfil_id' => $perfil->id,
                            'unidade_id' => isset($data['unidade_id']) && $data['unidade_id'] ? $data['unidade_id'] : null
                        ])->save();
                    }
                }

                if (isset($data['perfis']) && sizeof($data['perfis'])) {
                    foreach ($data['perfis'] as $perfil) {
                        $perfilLocal = Perfil::find($perfil['perfil_id']);
                        $perfisNovos[] = $perfilLocal->codigo;

                        $vinculo = PerfilUser::where('user_id', $model->id)
                            ->where('perfil_id', $perfil['perfil_id'])
                            ->where('unidade_id', $perfil['unidade_id'])
                            ->first();

                        if (!$vinculo) {
                            $this->perfis->fill([
                                'user_id' => $model->id,
                                'perfil_id' => $perfil->id,
                                'unidade_id' => isset($perfil['unidade_id']) && $perfil['unidade_id'] ? $perfil['unidade_id'] : null
                            ])->save();
                        }
                    }
                }
            } else {
                $data['ativo'] = true;
                $data['token'] = '*';
                $data['password'] = '*';
                $data['alterar_senha'] = false;
                $data['permissoes_externas'] = '[]';
                
                $this->model->fill($data)->save();

                if(isset($data['perfil_id']) && $data['perfil_id']) {
                    $perfil = Perfil::find($data['perfil_id']);
                    $perfisNovos[] = $perfil->codigo;

                    $this->perfis->fill([
                        'user_id' => $this->model->id,
                        'perfil_id' => $perfil->id,
                        'unidade_id' => isset($data['unidade_id']) && $data['unidade_id'] ? $data['unidade_id'] : null
                    ])->save();
                }
                
                $model = $this->model;
            }

            // Sincroniza com o autenticador
            if ($sync) {
                $this->sincronizaAutenticador($model);
                if ($perfisAntigos) {
                    $this->removePerfisAutenticador($model->cpf, $perfisAntigos);
                }
                if ($perfisNovos) {
                    $this->adicionaPerfisAutenticador($model->cpf, $perfisNovos);
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
     * Altera a situação de ativação de um usuário
     *
     * @param integer $id
     * @param boolean $active
     * @return boolean
     */
    public function changeActivate(int $id, bool $active) : bool
    {
        try {
            DB::beginTransaction();

            $model = $this->model->find($id);
            $model->fill([
                'ativo' => $active
            ])->save();

            DB::commit();
            return true;
        } catch (\Exception $ex) {
            report($ex);
            return false;
        }
    }

    /**
     * Atualiza a instituição de um usuário
     * 
     * @param integer $id
     * @param integer $instituicao
     * @return boolean
     */
    public function atualizarInstituicaoUser(int $id, int $instituicao) : bool
    {
        $model = $this->model->find($id);
        $model->instituicao_id = $instituicao;
        $model->instituicoes()->sync([$instituicao]);
        return $model->save();
    }

    /**
     * Remove um usuário
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
            $user->perfis()->delete();
            $user->delete();

            DB::commit();
            return true;
        } catch (\Exception $ex) {
            report($ex);
            DB::commit();
            return false;
        }
    }

    public function vincularUser($data)
    {
        try {
            DB::beginTransaction();
            $user = $this->model->find($data['usuario_id']);

            $existente = $this->perfis->where('user_id', $user->id)
                ->where('perfil_id', $data['perfil_id'])
                ->where('unidade_id', isset($data['unidade_id']) && $data['unidade_id'] ? $data['unidade_id'] : null)
                ->first();

            if ($existente) {
                $existente->ativo = true;
                $existente->save();
            } else {
                $perfil = Perfil::find($data['perfil_id']);
                $this->perfis->fill([
                    'user_id' => $user->id,
                    'perfil_id' => $perfil->id,
                    'unidade_id' => isset($data['unidade_id']) && $data['unidade_id'] ? $data['unidade_id'] : null
                ])->save();   
            }

            DB::commit();
            return true;
        } catch (\Exception $ex) {
            report($ex);
            DB::commit();
            return false;
        }
    }

    public function alterarVinculo($id)
    {
        try {
            DB::beginTransaction();

            $vinculo = $this->perfis->find($id);
            $vinculo->ativo = !$vinculo->ativo;
            $vinculo->save();

            if ($vinculo->ativo) {
                $msg = 'Vínculo ativado com sucesso!';
            } else {
                $msg = 'Vínculo inativado com sucesso!';
            }
            
            DB::commit();
            return ['success' => true, 'msg' => $msg];
        } catch (\Exception $ex) {
            report($ex);
            DB::commit();
            return ['success' => false, 'msg' => 'Erro ao alterar o vínculo!'];
        }
    }

    /**
     * Remove os perfis do autenticador
     *
     * @param User $user
     * @return bool
     */
    protected function sincronizaAutenticador(User $user) : bool
    {
        // Pesquisa o usuário
        $userType = new TypeUser();
        $userType->setCpf(UtilHelper::limparCpfCnpj($user->cpf));
        $userType->setEmail($user->email);
        $userType->setNome($user->nome);

        // Cadastra / altera o usuário
        return $this->userService->save($userType);
    }

    /**
     * Remove os perfis do autenticador
     *
     * @param User $user
     * @return bool
     */
    protected function adicionaPerfisAutenticador(string $cpf, array $perfis) : bool
    {
        // Atribui os perfis
        foreach ($perfis as $perfil) {
            $this->profileService->attachProfile(UtilHelper::limparCpfCnpj($cpf), $perfil);
        }
        
        return true;
    }

    /**
     * Remove os perfis do autenticador
     *
     * @param User $user
     * @return bool
     */
    protected function removePerfisAutenticador(string $cpf, array $perfis) : bool
    {
        // Pesquisa o usuário
       /* $userType = new TypeUser();
        $userType->setCpf($user->cpf);
        $userType->setEmail($user->email);
        $userType->setNome($user->nome);

        // Cadastra / altera o usuário
        if($this->userService->get($userType)){
            $this->userService->store($userType);
        }else{
            $this->userService->update($userType);
        }*/

        // Atribui os perfis
        foreach ($perfis as $perfil) {
            $this->profileService->dettachProfile(UtilHelper::limparCpfCnpj($cpf), $perfil);
        }
        
        return true;
    }

    /**
     * Exporta uma pesquisa de usuários em excel
     *
     * @param array $filtros
     * @return string
     */
    public function exportExcel(array $filtros = [])
    {
        ini_set('memory_limit', '2G');
        set_time_limit(0);

        $dados = $this->search(isset($filtros['filter']) ? $filtros['filter'] : '', $filtros)->toArray();
        return Excel::download(new UserExport($dados), 'usuarios.xlsx');
    }

    public function exportPDF(array $filtros = [])
    {
        ini_set('memory_limit', '2G');
        set_time_limit(0);

        $dados = $this->search(isset($filtros['filter']) ? $filtros['filter'] : '', $filtros)->toArray();
        $pdf = PDF::loadView("user.export-pdf-search", [
            'filtros' => $filtros,
            'data'  => $dados
        ]);

        return $pdf->download('usuarios.pdf');
    }

}