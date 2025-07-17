<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

# Rotas de estado e cidade
Route::get('/estado', 'EstadoController@index');
Route::get('/cidade', 'CidadeController@index');
Route::get('/cidade/{id}', 'CidadeController@getByEstadoId');

# Rotas de autenticação
Route::get('/auth/login/uri', 'AuthController@login');
Route::get('/auth/logout/uri', 'AuthController@logout');
Route::post('/auth/refresh', 'AuthController@refresh');
Route::post('/auth/{code}', 'AuthController@auth');

# Rotas das combos
Route::get('/deficiencia/combo', 'DeficienciaController@combo');
Route::get('/orientacao-sexual/combo', 'OrientacaoSexualController@combo');
Route::get('/sexo/combo', 'SexoController@combo');
Route::get('/raca/combo', 'RacaController@combo');
Route::get('/escolaridade/combo', 'EscolaridadeController@combo');
Route::get('/instituicao/combo', 'InstituicaoController@combo');
Route::get('/unidade/combo', 'UnidadeController@combo');
Route::get('/programa/combo', 'ProgramaController@combo');
Route::get('/cronograma/combo', 'CronogramaController@combo');

# Usuários
Route::post('/user-external', 'UserController@store');

# Solicitações > Cadastro
Route::post('/solicitacao', 'SolicitacaoController@store');

# Rotas de programas públicos
Route::get('/programa/ativo', 'ProgramaController@listarAtivos');
Route::get('/programa/{id}/ativo', 'ProgramaController@visualizarAtivo');

# Rotas de cronograma públicos
Route::get('/cronograma/{id}', 'CronogramaController@show');

# Configuração geral
Route::get('/configuracao-geral', 'ConfiguracaoGeralController@show');

# Rota auxiliar de auditoria
Route::get('/audit/aux', 'AuxAuditController@comboLabels');

Route::group(['middleware' => ['jwt.auth']], function () {

    # Rota de contexto de login
    Route::post('/switch-context/{contextId}', 'AuthController@switchContext')->name('switch-context');

    # Rotas de usuários
    Route::get('/user', 'UserController@index')->middleware('audit.query');
    Route::get('/user/{id}', 'UserController@show');
    Route::post('/user', 'UserController@store');
    Route::post('/user-vinculos', 'UserController@vincularUser');
    Route::put('/user/{id}', 'UserController@update');
    Route::delete('/user/{id}', 'UserController@destroy');
    Route::put('/user/{id}/inativo', 'UserController@inativar');
    Route::put('/user/{id}/ativo', 'UserController@ativar');
    Route::put('/user/{id}/instituicao', 'UserController@atualizarInstituicaoUser');
    Route::get('/users-unidade/{id}', 'UserController@getByUnidade');
    Route::get('/users-instituicao/{id}', 'UserController@getByInstituicao');
    Route::put('/users-unidade/alterar-vinculo/{id}', 'UserController@alterarVinculo');
    Route::post('/user/export/xls', 'UserController@excel');
    Route::post('/user/export/pdf', 'UserController@pdf');

    // Route::get('/user/{id}/historico', 'UserController@listarHistorico');

    # Rotas de perfis
    Route::get('/perfil/store-external', 'PerfilController@storeFrame');
    Route::get('/perfil/update-external/{codigo}', 'PerfilController@updateFrame');
    Route::get('/perfil/show-external/{codigo}', 'PerfilController@showFrame');
    Route::post('/perfil/tipo-perfil', 'PerfilController@tipoPerfil');
    Route::get('/perfil/combo', 'PerfilController@combo');
    Route::get('/perfil/{id}', 'PerfilController@show');
    Route::put('/perfil/{id}/inativo', 'PerfilController@inativar');
    Route::put('/perfil/{id}/ativo', 'PerfilController@ativar');
    Route::get('/perfil', 'PerfilController@index')->middleware('audit.query');

    # Rotas de solicitações
    Route::get('/solicitacao', 'SolicitacaoController@index')->middleware('audit.query');
    Route::get('/minhas-solicitacoes', 'SolicitacaoController@minhasSolicitacoes')->middleware('audit.query');
    Route::get('/solicitacao/{id}', 'SolicitacaoController@show');
    Route::get('/solicitacao/{id}/historico', 'SolicitacaoController@historico');
    Route::put('/solicitacao/{id}', 'SolicitacaoController@update');
    Route::delete('/solicitacao/{id}', 'SolicitacaoController@destroy');
    Route::put('/solicitacao/{id}/aprovada', 'SolicitacaoController@aprovar');
    Route::put('/solicitacao/{id}/devolvida', 'SolicitacaoController@devolver');
    Route::put('/solicitacao/{id}/reprovada', 'SolicitacaoController@reprovar');
    Route::put('/solicitacao/{id}/justificativa', 'SolicitacaoController@justificar');
    Route::post('/solicitacao/export/xls', 'SolicitacaoController@excel');
    Route::post('/solicitacao/export/pdf', 'SolicitacaoController@pdf');

    # Rotas de unidades
    Route::get('/unidade', 'UnidadeController@index')->middleware('audit.query');
    Route::get('/unidade/{id}', 'UnidadeController@show');
    Route::post('/unidade', 'UnidadeController@store');
    Route::put('/unidade/{id}', 'UnidadeController@update');
    Route::delete('/unidade/{id}', 'UnidadeController@destroy');
    Route::put('/unidade/{id}/inativo', 'UnidadeController@inativar');
    Route::put('/unidade/{id}/ativo', 'UnidadeController@ativar');
    Route::post('/unidade/export/xls', 'UnidadeController@excel');
    Route::post('/unidade/export/pdf', 'UnidadeController@pdf');

    # Rotas de instituições
    Route::get('/instituicao', 'InstituicaoController@index')->middleware('audit.query');
    Route::get('/instituicao/{id}', 'InstituicaoController@show');
    Route::post('/instituicao', 'InstituicaoController@store');
    Route::put('/instituicao/{id}', 'InstituicaoController@update');
    Route::delete('/instituicao/{id}', 'InstituicaoController@destroy');
    Route::put('/instituicao/{id}/inativo', 'InstituicaoController@inativar');
    Route::put('/instituicao/{id}/ativo', 'InstituicaoController@ativar');
    Route::post('/instituicao/export/xls', 'InstituicaoController@excel');
    Route::post('/instituicao/export/pdf', 'InstituicaoController@pdf');

    # Rotas de programas
    Route::get('/programa', 'ProgramaController@index')->middleware('audit.query');
    Route::get('/programa/{id}', 'ProgramaController@show');
    Route::post('/programa', 'ProgramaController@store');
    Route::put('/programa/{id}', 'ProgramaController@update');
    Route::delete('/programa/{id}', 'ProgramaController@destroy');
    Route::put('/programa/{id}/inativo', 'ProgramaController@inativar');
    Route::put('/programa/{id}/ativo', 'ProgramaController@ativar');
    Route::get('/programa-historico/{id}', 'ProgramaController@getHistorico');

    # Rotas de cronogramas
    Route::get('/cronograma', 'CronogramaController@index')->middleware('audit.query');
    Route::post('/cronograma', 'CronogramaController@store');
    Route::put('/cronograma/{id}', 'CronogramaController@update');
    Route::delete('/cronograma/{id}', 'CronogramaController@destroy');
    Route::put('/cronograma/{id}/cancelado', 'CronogramaController@cancelar');
    Route::get('/cronograma-verificar/{id}', 'AdesaoController@verificar');

    # Rotas de configuração geral
    Route::post('/configuracao-geral', 'ConfiguracaoGeralController@store');
    Route::put('/configuracao-geral/{id}', 'ConfiguracaoGeralController@update');

    # Rotas de adesões
    Route::get('/adesao', 'AdesaoController@index')->middleware('audit.query');
    Route::get('/adesao/instituicoes-habilitadas', 'AdesaoController@instituicoesHabilitadas');
    Route::get('/adesao/instituicoes-habilitadas/export/excel', 'AdesaoController@exportHabilitadasToExcel');
    Route::get('/minhas-adesoes', 'AdesaoController@minhasAdesoes')->middleware('audit.query');
    Route::get('/adesao/{id}', 'AdesaoController@show');
    Route::get('/adesao/{id}/historico', 'AdesaoController@historico');
    Route::post('/adesao', 'AdesaoController@store');
    Route::put('/adesao/{id}', 'AdesaoController@update');
    Route::delete('/adesao/{id}', 'AdesaoController@destroy');
    Route::put('/adesao/{id}/aprovada', 'AdesaoController@aprovar');
    Route::put('/adesao/{id}/devolvida', 'AdesaoController@devolver');
    Route::put('/adesao/{id}/recusada', 'AdesaoController@recusar');
    Route::post('/adesao/export/xls', 'AdesaoController@excel');
    Route::post('/adesao/export/pdf', 'AdesaoController@pdf');
    Route::put('/adesao-mudar-instituicao/{id}/{instituicao}', 'AdesaoController@mudarInstituicao');

    # Rotas de adesões > Habilitação
    Route::get('/adesao/{cronograma}/habilitado', 'AdesaoHabilitacaoController@listarHabilitados')->middleware('audit.query');
    Route::get('/adesao/{id}/recurso-adesao', 'AdesaoHabilitacaoController@visualizarRecursoAdesao');
    Route::post('/adesao/{id}/recurso-adesao', 'AdesaoHabilitacaoController@enviarRecursoAdesao');

    # Rotas de adesões > Classificação
    Route::get('/adesao/{cronograma}/classificado', 'AdesaoClassificacaoController@listarClassificados')->middleware('audit.query');
    Route::post('/adesao/{cronograma}/classificado/estadual/pdf', 'AdesaoClassificacaoController@exportarClassificadosEstadualPdf');
    Route::post('/adesao/{cronograma}/classificado/municipal/pdf', 'AdesaoClassificacaoController@exportarClassificadosMunicipalPdf');

    Route::post('/adesao/{cronograma}/classificado', 'AdesaoClassificacaoController@classificar');
    Route::get('/adesao/{id}/recurso-classificado', 'AdesaoClassificacaoController@visualizarRecursoClassificacao');
    Route::post('/adesao/{id}/recurso-classificado', 'AdesaoClassificacaoController@enviarRecursoClassificacao');

    # Rotas de adesões > Convocação
    Route::get('/adesao/{cronograma}/convocado', 'AdesaoConvocacaoController@listarConvocados')->middleware('audit.query');
    Route::post('/adesao/{id}/convocado', 'AdesaoConvocacaoController@convocar');
    Route::post('/adesao/{id}/termo', 'AdesaoConvocacaoController@enviarTermo');

    # Rotas de arquivos de classificação
    Route::get('/arquivos-classificacao/{tipo}/{cronograma}', 'CronogramaArquivoController@index');
    Route::get('/arquivo-classificacao/{tipo}/{id}', 'CronogramaArquivoController@show');
    Route::post('/arquivo-classificacao/{tipo}', 'CronogramaArquivoController@store');
    Route::put('/arquivo-classificacao/{tipo}/{id}', 'CronogramaArquivoController@update');
    Route::put('/arquivo-classificacao-status/{tipo}/{id}', 'CronogramaArquivoController@switchStatus');
    Route::delete('/arquivo-classificacao/{tipo}/{id}', 'CronogramaArquivoController@destroy');

    # Rota de download
    Route::get('/download/{origin}/{id}', 'DownloadController@download');

    # Rota Bens e equipamentos
    Route::get('/bens-equipamentos', 'BensEquipamentosController@index');          // listar com filtros e paginação
    Route::post('/bens-equipamentos', 'BensEquipamentosController@store');         // criar novo
    Route::get('/bens-equipamentos/{id}', 'BensEquipamentosController@show');      // buscar para edição
    Route::put('/bens-equipamentos/{id}', 'BensEquipamentosController@update');    // atualizar
    Route::delete('/bens-equipamentos/{id}', 'BensEquipamentosController@destroy'); // excluir
    Route::get('/bens-equipamentos/export/excel', 'BensEquipamentosController@exportBensToExcel');
    Route::put('/bens-equipamentos/{id}/inativo', 'BensEquipamentosController@inativar');
    Route::put('/bens-equipamentos/{id}/ativo', 'BensEquipamentosController@ativar');
});
