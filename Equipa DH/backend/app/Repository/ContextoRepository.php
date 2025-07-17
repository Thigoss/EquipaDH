<?php

namespace App\Repository;

use App\Models\Perfil;
use App\Models\PerfilUser;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use DB;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Classe de manipulação de contextos de login do user
 * 
 * @author Wellington Agliardi (wellington.agliardi@jointecnologia.com.br)
 */
class ContextoRepository extends BaseRepository
{

    /**
     * Model de PerfilUser
     *
     * @var PerfilUser
     */
    protected $contexto;
    
    /**
     * Construtor
     *
     * @param PerfilUser $contexto
     */
    public function __construct(PerfilUser $contexto)
    {
        $this->contexto = $contexto;
    }


    /**
     * Obtem os dados de um contexto junto com as informações relacionadas
     *
     * @param integer $id
     * @return PerfilUser|null
     */
    public function find($id) : ?PerfilUser
    {
        return $this->contexto->with([
            'user',
            'perfil',
            'unidade.estado',
            'unidade.cidade'
        ])
        ->where('id', $id)
        ->first();
    }

    /**
     * Obtem todos os contextos de um user
     *
     * @param integer $id
     * @return Collection|null
     */
    public function findByUser($idUser) : ?Collection
    {
        return $this->contexto->with([
            'perfil',
            'unidade',
        ])
        ->where('user_id', $idUser)
        ->where('ativo', true)
        ->get();
    }

    /**
     * Obtem todos os contextos de um user
     *
     * @param integer $id
     * @return PerfilUser
     */
    public function contextoAtualUser($idUser) : ?PerfilUser
    {
        $user = User::find($idUser);
        $contexto = $this->contexto->with(['perfil'])->find($user->contexto_atual_id);
        return $contexto;
    }

    public static function contextoLogado () : array
    {
        $payload = JWTAuth::payload();
        $user = User::find($payload['id']);
        $contexto = PerfilUser::with(['perfil', 'unidade.estado', 'unidade.cidade'])->find($payload['contexto_atual_id']);
        return ['user' => $user, 'contexto' => $contexto];
    }

}