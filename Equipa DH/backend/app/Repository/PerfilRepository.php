<?php

namespace App\Repository;

use App\Models\Perfil;
use App\Providers\AuthServiceProvider;
use App\Service\Auth\Contract\AuthInterface;
use App\Service\Auth\Contract\ProfileInterface;
use App\Service\Auth\Type\Profile;
use App\User;
use DB;
use Illuminate\Support\Facades\Log;
use PDF;

/**
 * Classe de manipulação da entidade de perfil
 * 
 * @author Eduardo Praxedes Heinske (eduardo.praxedes@jointecnologia.com.br)
 */
class PerfilRepository extends BaseRepository
{

    /**
     * Model de perfil
     *
     * @var Perfil
     */
    protected $model;

    /**
     * Service de perfis no autenticador
     *
     * @var ProfileInterface
     */
    protected $profileService;

    /**
     * Service de autenticação
     *
     * @var AuthInterface
     */
    protected $authService;
    
    /**
     * Construtor
     *
     * @param Perfil $perfil
     */
    public function __construct(Perfil $perfil, AuthInterface $authService, ProfileInterface $profileService)
    {
        $this->model = $perfil;
        $this->profileService = $profileService;
        $this->authService = $authService;
    }

    /**
     * Busca de perfis
     *
     * @param string $search
     * @param array $filtros
     * @param boolean $limit
     * @param boolean $sync
     * @return void
     */
    public function search(string $search = '', array $filtros = [], $limit = false, bool $sync = true)
    {
        $query = $this->model;

        // Filtra os perfis por status
        if($search)
        {
            $query = $query->where('nome', 'ilike', '%'.$search.'%');
        }

        // Filtra os perfis por status
        if(isset($filtros['status']) && !empty($filtros['status']))
        {
            $query = $query->where('ativo', $filtros['status']);
        }

        // Filtra por tipo de perfil
        if(isset($filtros['tipo_perfil_id']) && !empty($filtros['tipo_perfil_id']))
        {
            $query = $query->where('tipo', $filtros['tipo_perfil_id']);
        }

        $query = $query->orderBy('id', 'desc');
        
        // Sincroniza com os perfis o atenticador
        if($sync) {
            $this->sincronizarAutenticador();
        }

        if ($limit !== false) {
            return $query->paginate($limit);
        } else {
            return $query->get();
        }
    }

    /**
     * Busca os perfis ativos ordenados por nome
     * 
     * @return Collection
     */
    public function getAll()
    {
        return $this->model->where('ativo', true)->orderBy('nome', 'asc')->get();
    }

    /**
     * Busca o perfil por código
     *
     * @param string $codigo
     * @return void
     */
    public function getByCodigo($codigo)
    {
        return $this->model->where('codigo', $codigo)->first();
    }

    public function getProfileByCodigo(string $codigo)
    {
        $profile = $this->profileService->get($codigo);
        return $profile;
    }

    /**
     * Chama a url do frame de cadastro
     *
     * @return string
     */
    public function getStoreFrame() : string 
    {
        $token = $this->authService->getToken();
        return $this->profileService->storeFrame($token);
    }

    /**
     * Chama a url do frame de atualização
     *
     * @param integer $id
     * @return string
     */
    public function getUpdateFrame(int $id) : string
    {
        $token = $this->authService->getToken();
        return $this->profileService->updateFrame($id, $token);
    }

    /**
     * Chama a url do frame de visualização
     *
     * @param integer $id
     * @return string
     */
    public function getShowFrame(int $id) : string
    {
        $token = $this->authService->getToken();
        return $this->profileService->showFrame($id, $token);
    }

    public function historicoPerfil($id)
    {
        $perfil = $this->model->find($id);

        if ($perfil && $perfil->codigo) {
            $perfil = $this->profileService->get($perfil->codigo);
            $historicos = [];

            if ($perfil && $perfil['historicos']) {
                foreach($perfil['historicos'] as $key => $historico) {
                    $user = User::find($historico['referencia_id']);
                    $user = $user ? $user->cpf . ' - ' . $user->nome : null;

                    $historicos[] = [
                        'id' => $historico['id'],
                        'created_at' => $historico['created_at'],
                        'acao_realizada' => count($perfil['historicos']) - 1 == $key ? 'Cadastro' : 'Alteração',
                        'usuario_nome' => $user,
                        'dados' => json_decode($historico['json_auditoria'], true)
                    ];
                }
            }

            return $historicos;
        } else {
            return [];
        }
    }

    public function pdfHistoricoPerfil($id, $idhistorico)
    {
        $perfil = $this->model->find($id);
        $perfil = $this->profileService->get($perfil->codigo);
        $historicoDetalhe = [];

        if ($perfil && $perfil['historicos']) {
            foreach($perfil['historicos'] as $key => $historico) {
                $user = User::find($historico['referencia_id']);
                $user = $user ? $user->cpf . ' - ' . $user->nome : null;
                
                if($idhistorico == $historico['id']){
                    $historicoDetalhe = [
                        'id' => $historico['id'],
                        'created_at' => (new \DateTime($historico['created_at']))->format('d/m/Y H:i'),
                        'acao_realizada' => count($perfil['historicos']) - 1 == $key ? 'Cadastro' : 'Alteração',
                        'usuario_nome' => $user,
                        'dados' => json_decode($historico['json_auditoria'], true)
                    ];
                }
            }
        }

        $pdf = PDF::loadView("perfil.export-pdf-historico", [
            'historico'  => $historicoDetalhe
        ]);

        return $pdf->download('perfil_historico.pdf');
    }

    /**
     * Sincroniza os dados do perfil com o autenticador
     *
     * @return bool
     */
    protected function sincronizarAutenticador() : bool
    {
        // Pesquisa os perfis do serviço
        $perfisAuth = $this->profileService->list();
        
        // Pesquisa os perfis do sistema
        $perfis = $this->all();
        $perfis = $perfis->mapWithKeys(function($perfil, $key) {
            return [$perfil->codigo => $perfil];
        });

        // Cadastra ou altera
        foreach($perfisAuth as $perfil){
            if(!isset($perfis[$perfil->getCodigo()])){
                $this->save([
                    'codigo' => $perfil->getCodigo(),
                    'nome' => $perfil->getNome(),
                    'descricao' => $perfil->getDescricao(),
                    'ativo' => $perfil->getSituacao(),
                    'tipo' => 2
                ]);
            } else {
                $this->save([
                    'id' => $perfis[$perfil->getCodigo()]->id,
                    'codigo' => $perfil->getCodigo(),
                    'nome' => $perfil->getNome(),
                    'descricao' => $perfil->getDescricao(),
                    //'ativo' => $perfil->getSituacao()
                ]);
            }
        }
        
        return true;
    }

    public function configurarLimite($data)
    {
        try {
            \DB::beginTransaction();

            $model = $this->model->find($data['perfil_id'])->first();

            $model->limite_usuarios = $data['limite_usuarios'];

            $qtd_usuarios = 0;
            if ($data['limite_usuarios'] == true) {
                $qtd_usuarios = $data['qtd_limite_usuarios'];
            }
            $model->qtd_limite_usuarios = $qtd_usuarios;
            $model->update();

            \DB::commit();
            return true;
        } catch(\Exception $ex){
            DB::rollback();
            report($ex);
            return false;
        }
    }

    public function tipoPerfil($data) {
        try {
            \DB::beginTransaction();

            $model = $this->model->where('id', $data['perfil_id'])->first();

            if (!$model) {
                return false;
            }
            $model->tipo = $data['tipo_perfil'];
            $model->update();

            if ($data['tipo_perfil'] == 3) {
                Perfil::where('id', '!=', $data['perfil_id'])->update(['tipo' => 2]);
            }

            \DB::commit();
            return true;
        } catch (\Exception $ex) {
            DB::rollback();
            report($ex);
            return false;
        }
    }

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
}