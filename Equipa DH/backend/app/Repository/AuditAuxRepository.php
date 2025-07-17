<?php 

namespace App\Repository;

use App\Models\Cidade;
use App\Models\Cronograma;
use App\Models\Deficiencia;
use App\Models\Escolaridade;
use App\Models\Estado;
use App\Models\Instituicao;
use App\Models\OrientacaoSexual;
use App\Models\Perfil;
use App\Models\Raca;
use App\Models\Unidade;
use App\Models\Programa;
use App\Models\Sexo;
use App\User;

class AuditAuxRepository
{

  public function comboLabels()
  {
    $dados = [];

    // Estados
    $allEstados = Estado::all();
    $estados = [];
    foreach($allEstados as $estado) {
      $estados[$estado->id] = $estado->sigla;
    }

    $dados['estado_id'] = [
      'label' => 'Estado',
      'values' => $estados
    ];

    // Cidades
    $allCidades = Cidade::all();
    $cidades = [];
    foreach($allCidades as $cidade) {
      $cidades[$cidade->id] = $cidade->cidade;
    }

    $dados['cidade_id'] = [
      'label' => 'Cidade',
      'values' => $cidades
    ];

    // Orientação sexual
    $orientacoesSexuais = [];
    $allOrientacoesSexuais = OrientacaoSexual::all();
    foreach($allOrientacoesSexuais as $orientacaoSexual) {
      $orientacoesSexuais[$orientacaoSexual->id] = $orientacaoSexual->nome;
    }

    $dados['orientacao_sexual_id'] = [
      'label' => 'Orientação sexual',
      'values' => $orientacoesSexuais
    ];

    // Escolaridades
    $escolaridades = [];
    $allEscolaridades = Escolaridade::all();
    foreach($allEscolaridades as $escolaridade) {
      $escolaridades[$escolaridade->id] = $escolaridade->nome;
    }

    $dados['escolaridade_id'] = [
      'label' => 'Escolaridade',
      'values' => $escolaridades
    ];

    // Deficiências
    $deficiencias = [];
    $allDeficiencias = Deficiencia::all();
    foreach($allDeficiencias as $deficiencia) {
      $deficiencias[$deficiencia->id] = $deficiencia->nome;
    }

    $dados['deficiencia_id'] = [
      'label' => 'Deficiência',
      'values' => $deficiencias
    ];

    // Instituições
    $instituicoes = [];
    $allInstituicoes = Instituicao::all();
    foreach($allInstituicoes as $instituicao) {
      $instituicoes[$instituicao->id] = $instituicao->razao_social;
    }

    $dados['instituicao_id'] = [
      'label' => 'Instuição',
      'values' => $instituicoes
    ];

    // Cronogramas
    $cronogramas = [];
    $allCronogramas = Cronograma::all();
    foreach($allCronogramas as $cronograma) {
      $allCronogramas[$cronograma->id] = $cronograma->numero . ' - ' . $cronograma->programa->nome;
    }

    $dados['cronograma_id'] = [
      'label' => 'Cronograma',
      'values' => $allCronogramas
    ];

    $dados['adesoes.cronograma_id'] = [
      'label' => 'Cronograma',
      'values' => $allCronogramas
    ];

    
    $dados['adesao.cronograma_id'] = [
      'label' => 'Cronograma',
      'values' => $allCronogramas
    ];

    // Tipo de adesão
    $dados['adesoes.tipo'] = [
      'label' => 'Tipo de Adesão',
      'values' => [
        'AD' => 'Adesão',
        'RA' => 'Recurso de Adesão',
        'RC' => 'Recurso de Classificação',
        'CV' => 'Convocação'
      ]
    ];

    $dados['adesoes.tipo'] = [
      'label' => 'Tipo de Adesão',
      'values' => [
        'AD' => 'Adesão',
        'RA' => 'Recurso de Adesão',
        'RC' => 'Recurso de Classificação',
        'CV' => 'Convocação'
      ]
    ];


    // users
    $users = [];
    $allUsers = User::all();
    foreach($allUsers as $user) {
      $users[$user->id] = $user->nome;
    }

    $dados['user_id'] = [
      'label' => 'Usuário',
      'values' => $users
    ];

    // programas
    $programas = [];
    $allProgramas = Programa::all();
    foreach($allProgramas as $programa) {
      $programas[$programa->id] = $programa->nome;
    }

    $dados['programa_id'] = [
      'label' => 'Programa',
      'values' => $programas
    ];

    // sexos
    $sexos = [];
    $allSexos = Sexo::all();
    foreach($allSexos as $sexo) {
      $sexos[$sexo->id] = $sexo->nome;
    }

    $dados['sexo_id'] = [
      'label' => 'Sexo',
      'values' => $sexos
    ];

    // Unidades
    $unidades = [];
    $allUnidades = Unidade::all();
    foreach($allUnidades as $unidade) {
      $unidades[$unidade->id] = $unidade->nome;
    }

    $dados['unidade_id'] = [
      'label' => 'Unidade',
      'values' => $unidades
    ];

    // Perfis
    $perfis = [];
    $allPerfis = Perfil::all();
    foreach($allPerfis as $perfil) {
      $perfis[$perfil->id] = $perfil->nome;
    }
    
    $dados['perfil_id'] = [
      'label' => 'Perfil',
      'values' => $perfis
    ];

    // Raças
    $racas = [];
    $allRacas = Raca::all();
    foreach($allRacas as $raca) {
      $racas[$raca->id] = $raca->nome;
    }

    $dados['raca_id'] = [
      'label' => 'Raça',
      'values' => $racas
    ];

    // situação
    $dados['users.tipo'] = [
      'label' => 'Tipo',
      'values' => [
        'E' => 'Externo',
        'I' => 'Interno'
      ]
    ];

    $dados['user.tipo'] = [
      'label' => 'Tipo',
      'values' => [
        'E' => 'Externo',
        'I' => 'Interno'
      ]
    ];

    $dados['users.tipo_representacao'] = [
      'label' => 'Tipo Representação',
      'values' => [
        'A' => 'Autoridade',
        'R' => 'Representante'
      ]
    ];

    // situação
    $dados['user.situacao'] = [
      'label' => 'Situacao',
      'values' => [
        'true' => 'Ativo',
        'false' => 'Inativo'
      ]
    ];

    // tipos de perfil
    $dados['perfis.tipo_perfil_id'] = [
      'label' => 'Tipo',
      'values' => [
        '1' => 'Administrador Geral',
        '2' => 'Padrão',
        '3' => 'Solicitante'
      ]
    ];

    $dados['perfis.tipo'] = [
      'label' => 'Tipo',
      'values' => [
        '1' => 'Administrador Geral',
        '2' => 'Padrão',
        '3' => 'Solicitante'
      ]
    ];

    // situação de perfil
    $dados['perfis.status'] = [
      'label' => 'Situacao',
      'values' => [
        'true' => 'Ativo',
        'false' => 'Inativo'
      ]
    ];

    // Situações da solicitação
    $dados['solicitacoes.situacao'] = [
      'label' => 'Situacao',
      'values' => [
        'AP' => 'Aprovado',
        'RE' => 'Reprovado',
        'PE' => 'Pendente',
        'DE' => 'Devolvido'
      ]
    ];

    $dados['solicitacao.situacao'] = [
      'label' => 'Situacao',
      'values' => [
        'AP' => 'Aprovado',
        'RE' => 'Reprovado',
        'PE' => 'Pendente',
        'DE' => 'Devolvido'
      ]
    ];


    
    // ordem solicitação
    $dados['solicitacoes.order'] = [
      'label' => 'Ordenar por',
      'values' => [
        'created_at' => 'Data de Cadastro',
        'updated_at' => 'Data de Atualização'
      ]
    ];

    $dados['solicitacoes.orderBy'] = [
      'label' => 'Order',
      'values' => [
        'asc' => 'Crescente',
        'desc' => 'Decrescente'
      ]
    ];

    // situações do cronograma
    $dados['cronogramas.situacao'] = [
      'label' => 'Situacao',
      'values' => [
        'NI' => 'Não Iniciado',
        'AN' => 'Em Andamento',
        'FI' => 'Finalizado',
        'CA' => 'Cancelado'
      ]
    ];

    // fases do cronograma
    $dados['cronogramas.fase'] = [
      'label' => 'Fase',
      'values' => [
        'NI' => 'Não Iniciado',
        'PU' => 'Publicado',
        'AD' => 'Adesão e Habilitação',
        'AF' => 'Validação das Adesões',
        'DA' => 'Divulgação da Adesão',
        'RA' => 'Recurso Adesão',
        'RF' => 'Validação dos Recursos',
        'DH' => 'Divulgação de Habilitados',
        'RH' => 'Recurso de Habilitados',
        'HF' => 'Validação de Recursos de Habilitados',
        'DL' => 'Divulgação da Lista',
        'EN' => 'Encerrado',
        'CO' => 'Convocação'
      ]
    ];

    // cronogramas.publicacao
    $dados['cronogramas.publicacao'] = [
      'label' => 'Publicação',
      'values' => [
        'PU' => 'Publicado',
        'NP' => 'Não Publicado'
      ]
    ];

    $dados['cronograma.publicacao'] = [
      'label' => 'Publicação',
      'values' => [
        'PU' => 'Publicado',
        'NP' => 'Não Publicado'
      ]
    ];

    // tipos de adesao
    $dados['adesoes.tipo'] = [
      'label' => 'Tipo',
      'values' => [
        'AD' => 'Adesão',
        'RA' => 'Recurso de Adesão',
        'RC' => 'Recurso de Classificação',
        'CV' => 'Convocação'
      ]
    ];

    // situação de adesão
    $dados['adesoes.situacao'] = [
      'label' => 'Situacao',
      'values' => [
        'PE' => 'Pendente',
        'AP' => 'Aprovado',
        'DV' => 'Devolvido',
        'RE' => 'Recusado'
      ]
    ];

    // ordem adesão
    $dados['adesoes.order'] = [
      'label' => 'Ordenar por',
      'values' => [
        'created_at' => 'Data de Cadastro',
        'updated_at' => 'Data de Atualização'
      ]
    ];

    $dados['adesoes.orderBy'] = [
      'label' => 'Ordem',
      'values' => [
        'asc' => 'Crescente',
        'desc' => 'Decrescente'
      ]
    ];
  

    return $dados;
  }
}
