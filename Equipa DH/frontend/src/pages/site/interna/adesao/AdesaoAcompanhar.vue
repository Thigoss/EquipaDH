<template>
  <span>
    <b-col md="12" sm="12">
      <b-card no-body>
        <b-card-body>
          <b-form v-if="cronograma">
            <h1 tabindex="0">Acompanhamento de Cronograma</h1>
            <h2 tabindex="0"><strong>Nº do Cronograma:</strong>&nbsp;{{ cronograma ? cronograma.numero : '' }}</h2>

            <b-row>
              <b-col md="12" sm="12">
                <p><strong>Política Pública: </strong>{{ cronograma ? cronograma.programa.nome : '' }}</p>
              </b-col>
            </b-row>
            <b-row>
              <b-col md="12" sm="12">
                <p><strong>Adesão:</strong></p>
              </b-col>
            </b-row>
            <b-row v-if="cronograma.data_publicacao_inicio">
              <b-col md="4" sm="12">
                <p>Publicação do Cronograma:</p>
              </b-col>
              <b-col md="4" sm="12">
                <b-form-group>
                  <b-input-group>
                    <b-form-input type="date" v-model="cronograma.data_publicacao_inicio" disabled>
                    </b-form-input>
                  </b-input-group>
                </b-form-group>
              </b-col>
            </b-row>
            <b-row v-if="cronograma.data_adesao_inicio && cronograma.data_adesao_fim">
              <b-col md="4" sm="12">
                <p>Período de Adesão:</p>
              </b-col>
              <b-col md="4" sm="12">
                <b-form-group>
                  <b-input-group>
                    <b-form-input type="date" v-model="cronograma.data_adesao_inicio" disabled>
                    </b-form-input>
                  </b-input-group>
                </b-form-group>
              </b-col>
              <b-col md="4" sm="12">
                <b-form-group>
                  <b-input-group>
                    <b-form-input type="date" v-model="cronograma.data_adesao_fim" disabled>
                    </b-form-input>
                  </b-input-group>
                </b-form-group>
              </b-col>
            </b-row>
            <b-row v-if="cronograma.data_divulgacao_adesao_inicio">
              <b-col md="4" sm="12">
                <p>Divulgação preliminar das adesões:</p>
              </b-col>
              <b-col md="4" sm="12">
                <b-form-group>
                  <b-input-group>
                    <b-form-input type="date" v-model="cronograma.data_divulgacao_adesao_inicio" disabled>
                    </b-form-input>
                  </b-input-group>
                </b-form-group>
              </b-col>
            </b-row>
            <b-row v-if="cronograma.data_recurso_adesao_inicio && cronograma.data_recurso_adesao_fim">
              <b-col md="4" sm="12">
                <p>Prazo para recurso de adesões:</p>
              </b-col>
              <b-col md="4" sm="12">
                <b-form-group>
                  <b-input-group>
                    <b-form-input type="date" v-model="cronograma.data_recurso_adesao_inicio" disabled>
                    </b-form-input>
                  </b-input-group>
                </b-form-group>
              </b-col>
              <b-col md="4" sm="12">
                <b-form-group>
                  <b-input-group>
                    <b-form-input type="date" v-model="cronograma.data_recurso_adesao_fim" disabled>
                    </b-form-input>
                  </b-input-group>
                </b-form-group>
              </b-col>
            </b-row>
            <b-row v-if="cronograma.data_divulgacao_habilitados_inicio">
              <b-col md="4" sm="12">
                <p>Divulgação preliminar da lista de habilitados e classificados:</p>
              </b-col>
              <b-col md="4" sm="12">
                <b-form-group>
                  <b-input-group>
                    <b-form-input type="date" v-model="cronograma.data_divulgacao_habilitados_inicio" disabled>
                    </b-form-input>
                  </b-input-group>
                </b-form-group>
              </b-col>
            </b-row>
            <b-row v-if="cronograma.data_recurso_habilitados_inicio && cronograma.data_recurso_habilitados_fim">
              <b-col md="4" sm="12">
                <p>Prazo para recurso de habilitados e classificados:</p>
              </b-col>
              <b-col md="4" sm="12">
                <b-form-group>
                  <b-input-group>
                    <b-form-input type="date" v-model="cronograma.data_recurso_habilitados_inicio" disabled>
                    </b-form-input>
                  </b-input-group>
                </b-form-group>
              </b-col>
              <b-col md="4" sm="12">
                <b-form-group>
                  <b-input-group>
                    <b-form-input type="date" v-model="cronograma.data_recurso_habilitados_fim" disabled>
                    </b-form-input>
                  </b-input-group>
                </b-form-group>
              </b-col>
            </b-row>
            <b-row v-if="cronograma.data_divulgacao_lista">
              <b-col md="4" sm="12">
                <p>Divulgação da lista de habilitados e classificados selecionados:</p>
              </b-col>
              <b-col md="4" sm="12">
                <b-form-group>
                  <b-input-group>
                    <b-form-input type="date" v-model="cronograma.data_divulgacao_lista" disabled>
                    </b-form-input>
                  </b-input-group>
                </b-form-group>
              </b-col>
            </b-row>

            <span v-if="apresentarHabilitados">
              <b-row>
                <b-col md="12" sm="12">
                  <b-card no-body>
                    <b-card-header>
                      <h3 class="info-name"><strong>Participantes</strong></h3>
                      <b-button class="collapse-button" ref="collapse" v-b-toggle="'collapse-participantes'">
                        <span class="when-opened">
                          <i class="fa fa-chevron-down" aria-hidden="true"></i>
                        </span>
                        <span class="when-closed">
                          <i class="fa fa-chevron-up" aria-hidden="true"></i>
                        </span>
                      </b-button>
                    </b-card-header>
                    <b-collapse id="collapse-participantes">
                      <b-card class="mb-0">
                        <b-row>
                          <b-col md="4" sm="12">
                            <p>Período de Adesão:</p>
                          </b-col>
                          <b-col md="4" sm="12">
                            <b-form-group>
                              <b-input-group>
                                <b-form-input type="date" v-model="cronograma.data_adesao_inicio" disabled>
                                </b-form-input>
                              </b-input-group>
                            </b-form-group>
                          </b-col>
                          <b-col md="4" sm="12">
                            <b-form-group>
                              <b-input-group>
                                <b-form-input type="date" v-model="cronograma.data_adesao_fim" disabled>
                                </b-form-input>
                              </b-input-group>
                            </b-form-group>
                          </b-col>
                        </b-row>
                        <b-row>
                          <b-col md="12" sm="12">
                            <table aria-describedby="my-table" aria-rowcount="4" id="my-table" aria-busy="false"
                              aria-colcount="7" class="table table-responsive-sm table-bordered table-striped table-sm">
                              <thead>
                                <tr>
                                  <th tabindex="0" scope="col">Instituição</th>
                                  <th tabindex="0" scope="col">UF</th>
                                  <th tabindex="0" scope="col">Município</th>
                                  <th tabindex="0" scope="col">Data da Solicitação</th>
                                </tr>
                              </thead>
                              <tbody v-if="habilitados && habilitados.length > 0">
                                <tr class="action" v-for="adesao in habilitados" :key="adesao.id">
                                  <td tabindex="0">{{ adesao.instituicao.razao_social }}</td>
                                  <td tabindex="0">{{ adesao.instituicao.estado.sigla }}</td>
                                  <td tabindex="0">{{ adesao.instituicao.cidade.cidade }}</td>
                                  <td tabindex="0">{{ adesao.created_at }}</td>
                                </tr>
                              </tbody>
                              <tbody v-else>
                                <tr class="action">
                                  <td tabindex="0" colspan="4">Nenhuma adesão habilitada</td>
                                </tr>
                              </tbody>
                            </table>
                            <b-row>
                              <b-col cols="8">
                                <pagination-page v-if="habilitadosId" v-show="habilitados && habilitados.length > 0"
                                  :resource="`adesao/${habilitadosId}/habilitado`" :filtros="[]" :totalPorPagina="20"
                                  :list="'habilitados'" :origin="'AdesoesHabilitados'">
                                </pagination-page>
                              </b-col>
                            </b-row>
                          </b-col>
                        </b-row>

                        <br>
                        <b-row>
                          <b-col md="3" sm="12">
                            <p>Período para recurso de adesão:</p>
                          </b-col>
                          <b-col md="3" sm="12">
                            <b-form-group>
                              <b-input-group>
                                <b-form-input type="date" v-model="cronograma.data_recurso_adesao_inicio" disabled>
                                </b-form-input>
                              </b-input-group>
                            </b-form-group>
                          </b-col>
                          <b-col md="3" sm="12">
                            <b-form-group>
                              <b-input-group>
                                <b-form-input type="date" v-model="cronograma.data_recurso_adesao_fim" disabled>
                                </b-form-input>
                              </b-input-group>
                            </b-form-group>
                          </b-col>
                          <b-col md="3" sm="12" v-if="permitirRecursoHabilitados">
                            <b-button block variant="primary" @click="modalRecursoAdesao()">
                              Solicitar Recurso de Adesão
                            </b-button>
                          </b-col>
                        </b-row>

                        <span style="display: block; background-color: #f8f9fa;" v-if="recursoAdesao">
                          <b-row>
                            <b-col md="12" sm="12">
                              <p><strong>Recurso cadastrado</strong></p>
                            </b-col>
                          </b-row>
                          <b-row>
                            <b-col md="12" sm="12">
                              <b-form-group>
                                {{ recursoAdesao ? recursoAdesao.justificativa : '' }}
                              </b-form-group>
                            </b-col>
                          </b-row>
                          <b-row>
                            <b-col md="12" sm="12">
                              <table aria-describedby="my-table" aria-rowcount="4" id="my-table" aria-busy="false"
                                aria-colcount="7"
                                class="table table-responsive-sm table-bordered table-striped table-sm">
                                <thead>
                                  <tr>
                                    <th tabindex="0" scope="col">Arquivo</th>
                                    <th tabindex="0" scope="col" style="width: 10%">Ação</th>
                                  </tr>
                                </thead>
                                <tbody v-if="recursoAdesao && recursoAdesao.arquivos.length > 0">
                                  <tr class="action" v-for="arquivo in recursoAdesao.arquivos" :key="arquivo.id">
                                    <td tabindex="0">{{ arquivo.nome }}</td>
                                    <td tabindex="0">
                                      <b-button @click="download('adesao_recurso_arquivo', arquivo.id)">
                                        <em class="fa fa-download"></em>&nbsp;
                                        Download
                                      </b-button>
                                    </td>
                                  </tr>
                                </tbody>
                                <tbody v-else>
                                  <tr class="action">
                                    <td tabindex="0" colspan="2">Nenhum arquivo inserido</td>
                                  </tr>
                                </tbody>
                              </table>
                            </b-col>
                          </b-row>
                          <b-row>
                            <b-col md="12" sm="12">
                              <p><strong>Histórico da Solicitação</strong></p>
                            </b-col>
                          </b-row>
                          <b-row>
                            <b-col md="12">
                              <table aria-describedby="my-table" aria-rowcount="4" id="my-table" aria-busy="false"
                                aria-colcount="7"
                                class="table table-responsive-sm table-bordered table-striped table-sm">
                                <thead>
                                  <tr>
                                    <th tabindex="0" scope="col">Data</th>
                                    <th tabindex="0" scope="col">Usuário</th>
                                    <th tabindex="0" scope="col">Situação</th>
                                    <th tabindex="0" scope="col">Justificativa</th>
                                  </tr>
                                </thead>
                                <tbody v-if="adesao.historico.length > 0">
                                  <tr class="action" v-for="historico in adesao.historico" :key="historico.id"
                                    v-if="historico.tipo === 'RA'">
                                    <td tabindex="0">{{ historico.created_at }}</td>
                                    <td tabindex="0">{{ historico.user.nome }}</td>
                                    <td tabindex="0">
                                      <b-badge v-if="historico.situacao === 'PE'" variant="info">Pendente</b-badge>
                                      <b-badge v-if="historico.situacao === 'AP'" variant="success">Aprovado</b-badge>
                                      <b-badge v-if="historico.situacao === 'RE'" variant="danger">Recusado</b-badge>
                                      <b-badge v-if="historico.situacao === 'DV'" variant="warning">Devolvido</b-badge>
                                    </td>
                                    <td tabindex="0">{{ historico.justificativa }}</td>
                                  </tr>
                                </tbody>
                                <tbody v-else>
                                  <tr class="action">
                                    <td tabindex="0" colspan="8">Nenhuma solicitação cadastrada</td>
                                  </tr>
                                </tbody>
                              </table>
                            </b-col>
                          </b-row>
                        </span>
                      </b-card>
                    </b-collapse>
                  </b-card>
                </b-col>
              </b-row>
            </span>

            <span v-if="apresentarClassificados">
              <b-row>
                <b-col md="12" sm="12">
                  <b-card no-body>
                    <b-card-header>
                      <h3 class="info-name"><strong>Classificação</strong></h3>
                      <b-button class="collapse-button" ref="collapse" v-b-toggle="'collapse-classificacao'">
                        <span class="when-opened">
                          <i class="fa fa-chevron-down" aria-hidden="true"></i>
                        </span>
                        <span class="when-closed">
                          <i class="fa fa-chevron-up" aria-hidden="true"></i>
                        </span>
                      </b-button>
                    </b-card-header>
                    <b-collapse id="collapse-classificacao">
                      <b-card class="mb-0">
                        <b-row>
                          <b-col md="4" sm="12">
                            <p>Divulgação preliminar da lista de habilitados e classificados:</p>
                          </b-col>
                          <b-col md="4" sm="12">
                            <b-form-group>
                              <b-input-group>
                                <b-form-input type="date" v-model="cronograma.data_divulgacao_habilitados_inicio"
                                  disabled>
                                </b-form-input>
                              </b-input-group>
                            </b-form-group>
                          </b-col>
                          <!-- <b-col md="4" sm="12">
                            <b-form-group>
                              <b-input-group>
                                <b-form-input type="date" v-model="cronograma.data_divulgacao_habilitados_fim" disabled>
                                </b-form-input>
                              </b-input-group>
                            </b-form-group>
                          </b-col> -->
                        </b-row>
                        <b-row>
                          <b-col md="12" sm="12">
                            <table aria-describedby="my-table" aria-rowcount="4" id="my-table" aria-busy="false"
                              aria-colcount="7" class="table table-responsive-sm table-bordered table-striped table-sm">
                              <thead>
                                <tr>
                                  <th tabindex="0" scope="col">Instituição</th>
                                  <th tabindex="0" scope="col">UF</th>
                                  <th tabindex="0" scope="col">Município</th>
                                  <th tabindex="0" scope="col">Data da Solicitação</th>
                                  <th tabindex="0" scope="col">Classificação</th>
                                </tr>
                              </thead>
                              <tbody v-if="classificados && classificados.length > 0">
                                <tr class="action" v-for="adesao in classificados" :key="adesao.id">
                                  <td tabindex="0">{{ adesao.instituicao.razao_social }}</td>
                                  <td tabindex="0">{{ adesao.instituicao.estado.sigla }}</td>
                                  <td tabindex="0">{{ adesao.instituicao.cidade.cidade }}</td>
                                  <td tabindex="0">{{ adesao.created_at }}</td>
                                  <td tabindex="0">{{ adesao.classificacao ? adesao.classificacao + 'º' : '-' }}</td>
                                </tr>
                              </tbody>
                              <tbody v-else>
                                <tr class="action">
                                  <td tabindex="0" colspan="5">Nenhuma adesão classificada</td>
                                </tr>
                              </tbody>
                            </table>
                            <b-row>
                              <b-col cols="8">
                                <pagination-page v-if="classificadosId"
                                  v-show="classificados && classificados.length > 0"
                                  :resource="`adesao/${classificadosId}/classificado`" :filtros="[]"
                                  :totalPorPagina="20" :list="'classificados'" :origin="'AdesoesClassificados'">
                                </pagination-page>
                              </b-col>
                            </b-row>
                          </b-col>
                        </b-row>

                        <br>
                        <b-row>
                          <b-col md="3" sm="12">
                            <p>Período para recurso de habilitação e classificação:</p>
                          </b-col>
                          <b-col md="3" sm="12">
                            <b-form-group>
                              <b-input-group>
                                <b-form-input type="date" v-model="cronograma.data_recurso_habilitados_inicio" disabled>
                                </b-form-input>
                              </b-input-group>
                            </b-form-group>
                          </b-col>
                          <b-col md="3" sm="12">
                            <b-form-group>
                              <b-input-group>
                                <b-form-input type="date" v-model="cronograma.data_recurso_habilitados_fim" disabled>
                                </b-form-input>
                              </b-input-group>
                            </b-form-group>
                          </b-col>
                          <b-col md="3" sm="12" v-if="permitirRecursoClassificacao">
                            <b-button block variant="primary" @click="modalRecursoClassificacao()">
                              Solicitar Recurso de Classificação
                            </b-button>
                          </b-col>
                        </b-row>

                        <span style="display: block; background-color: #f8f9fa;" v-if="recursoClassificacao">
                          <b-row>
                            <b-col md="12" sm="12">
                              <p><strong>Recurso cadastrado</strong></p>
                            </b-col>
                          </b-row>
                          <b-row>
                            <b-col md="12" sm="12">
                              <b-form-group>
                                {{ recursoClassificacao ? recursoClassificacao.justificativa : '' }}
                              </b-form-group>
                            </b-col>
                          </b-row>
                          <b-row>
                            <b-col md="12" sm="12">
                              <table aria-describedby="my-table" aria-rowcount="4" id="my-table" aria-busy="false"
                                aria-colcount="7"
                                class="table table-responsive-sm table-bordered table-striped table-sm">
                                <thead>
                                  <tr>
                                    <th tabindex="0" scope="col">Arquivo</th>
                                    <th tabindex="0" scope="col" style="width: 10%">Ação</th>
                                  </tr>
                                </thead>
                                <tbody
                                  v-if="recursoClassificacao && recursoClassificacao.arquivos && recursoClassificacao.arquivos.length > 0">
                                  <tr class="action" v-for="arquivo in recursoClassificacao.arquivos" :key="arquivo.id">
                                    <td tabindex="0">{{ arquivo.nome }}</td>
                                    <td tabindex="0">
                                      <b-button @click="download('adesao_recurso_arquivo', arquivo.id)">
                                        <em class="fa fa-download"></em>&nbsp;
                                        Download
                                      </b-button>
                                    </td>
                                  </tr>
                                </tbody>
                                <tbody v-else>
                                  <tr class="action">
                                    <td tabindex="0" colspan="2">Nenhum arquivo inserido</td>
                                  </tr>
                                </tbody>
                              </table>
                            </b-col>
                          </b-row>
                          <b-row>
                            <b-col md="12" sm="12">
                              <p><strong>Histórico da Solicitação</strong></p>
                            </b-col>
                          </b-row>
                          <b-row>
                            <b-col md="12">
                              <table aria-describedby="my-table" aria-rowcount="4" id="my-table" aria-busy="false"
                                aria-colcount="7"
                                class="table table-responsive-sm table-bordered table-striped table-sm">
                                <thead>
                                  <tr>
                                    <th tabindex="0" scope="col">Data</th>
                                    <th tabindex="0" scope="col">Usuário</th>
                                    <th tabindex="0" scope="col">Situação</th>
                                    <th tabindex="0" scope="col">Justificativa</th>
                                  </tr>
                                </thead>
                                <tbody v-if="adesao.historico.length > 0">
                                  <tr class="action" v-for="historico in adesao.historico" :key="historico.id"
                                    v-if="historico.tipo === 'RC'">
                                    <td tabindex="0">{{ historico.created_at }}</td>
                                    <td tabindex="0">{{ historico.user.nome }}</td>
                                    <td tabindex="0">
                                      <b-badge v-if="historico.situacao === 'PE'" variant="info">Pendente</b-badge>
                                      <b-badge v-if="historico.situacao === 'AP'" variant="success">Aprovado</b-badge>
                                      <b-badge v-if="historico.situacao === 'RE'" variant="danger">Recusado</b-badge>
                                      <b-badge v-if="historico.situacao === 'DV'" variant="warning">Devolvido</b-badge>
                                    </td>
                                    <td tabindex="0">{{ historico.justificativa }}</td>
                                  </tr>
                                </tbody>
                                <tbody v-else>
                                  <tr class="action">
                                    <td tabindex="0" colspan="8">Nenhuma solicitação cadastrada</td>
                                  </tr>
                                </tbody>
                              </table>
                            </b-col>
                          </b-row>
                        </span>
                      </b-card>
                    </b-collapse>
                  </b-card>
                </b-col>
              </b-row>
            </span>

            <span v-if="apresentarConvocados">
              <b-row>
                <b-col md="12" sm="12">
                  <b-card no-body>
                    <b-card-header>
                      <h3 class="info-name"><strong>Convocados</strong></h3>
                      <b-button class="collapse-button" ref="collapse" v-b-toggle="'collapse-convocados'">
                        <span class="when-opened">
                          <i class="fa fa-chevron-down" aria-hidden="true"></i>
                        </span>
                        <span class="when-closed">
                          <i class="fa fa-chevron-up" aria-hidden="true"></i>
                        </span>
                      </b-button>
                    </b-card-header>
                    <b-collapse id="collapse-convocados">
                      <b-card class="mb-0">
                        <b-row>
                          <b-col md="4" sm="12">
                            <p>Convocação para assinatrua do Termo de Compromisso com a Doação:</p>
                          </b-col>
                        </b-row>
                        <b-row>
                          <b-col md="12" sm="12">
                            <table aria-describedby="my-table" aria-rowcount="4" id="my-table" aria-busy="false"
                              aria-colcount="7" class="table table-responsive-sm table-bordered table-striped table-sm">
                              <thead>
                                <tr>
                                  <th tabindex="0" scope="col">Instituição</th>
                                  <th tabindex="0" scope="col">UF</th>
                                  <th tabindex="0" scope="col">Município</th>
                                  <th tabindex="0" scope="col">Data da Solicitação</th>
                                  <th tabindex="0" scope="col">Classificação</th>
                                </tr>
                              </thead>
                              <tbody v-if="convocados && convocados.length > 0">
                                <tr class="action" v-for="adesao in convocados" :key="adesao.id">
                                  <td tabindex="0">{{ adesao.instituicao.razao_social }}</td>
                                  <td tabindex="0">{{ adesao.instituicao.estado.sigla }}</td>
                                  <td tabindex="0">{{ adesao.instituicao.cidade.cidade }}</td>
                                  <td tabindex="0">{{ adesao.created_at }}</td>
                                  <td tabindex="0">{{ adesao.classificacao ? adesao.classificacao + 'º' : '-' }}</td>
                                </tr>
                              </tbody>
                              <tbody v-else>
                                <tr class="action">
                                  <td tabindex="0" colspan="5">Nenhuma adesão convocada</td>
                                </tr>
                              </tbody>
                            </table>
                            <b-row>
                              <b-col cols="8">
                                <pagination-page v-if="convocadosId" v-show="convocados && convocados.length > 0"
                                  :resource="`adesao/${convocadosId}/convocado`" :filtros="[]" :totalPorPagina="20"
                                  :list="'convocados'" :origin="'AdesoesConvocados'">
                                </pagination-page>
                              </b-col>
                            </b-row>
                          </b-col>
                        </b-row>

                        <br>
                        <b-row>
                          <b-col md="4" sm="12">
                            <p>Documento de termo de compromisso:</p>
                          </b-col>
                          <b-col md="3" sm="12">
                            <b-button block variant="primary" @click="modalTermo()"
                              v-if="adesao != null && adesao.convocado && (adesao.situacao === 'CV' || adesao.situacao === 'DV')">
                              Upload
                            </b-button>
                          </b-col>
                        </b-row>
                        <br>

                        <span style="display: block; background-color: #f8f9fa;"
                          v-if="adesao && adesao.termo_convocacao">
                          <b-row>
                            <b-col md="12" sm="12">
                              <table aria-describedby="my-table" aria-rowcount="4" id="my-table" aria-busy="false"
                                aria-colcount="7"
                                class="table table-responsive-sm table-bordered table-striped table-sm">
                                <thead>
                                  <tr>
                                    <th tabindex="0" scope="col">Arquivo</th>
                                    <th tabindex="0" scope="col">Ação</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr class="action">
                                    <td tabindex="0">Termo de Convocação</td>
                                    <td tabindex="0" style="width: 10%">
                                      <b-button @click="download('adesao_termo_convocacao', adesao.id)">
                                        <em class="fa fa-download"></em>&nbsp;
                                        Download
                                      </b-button>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </b-col>
                          </b-row>
                          <br>
                          <b-row>
                            <b-col md="12" sm="12">
                              <p><strong>Histórico da Solicitação</strong></p>
                            </b-col>
                          </b-row>
                          <b-row>
                            <b-col md="12">
                              <table aria-describedby="my-table" aria-rowcount="4" id="my-table" aria-busy="false"
                                aria-colcount="7"
                                class="table table-responsive-sm table-bordered table-striped table-sm">
                                <thead>
                                  <tr>
                                    <th tabindex="0" scope="col">Data</th>
                                    <th tabindex="0" scope="col">Usuário</th>
                                    <th tabindex="0" scope="col">Situação</th>
                                    <th tabindex="0" scope="col">Justificativa</th>
                                  </tr>
                                </thead>
                                <tbody v-if="adesao.historico.length > 0">
                                  <tr class="action" v-for="historico in adesao.historico" :key="historico.id"
                                    v-if="historico.tipo === 'CV'">
                                    <td tabindex="0">{{ historico.created_at }}</td>
                                    <td tabindex="0">{{ historico.user.nome }}</td>
                                    <td tabindex="0">
                                      <b-badge v-if="historico.situacao === 'PE'" variant="info">Pendente</b-badge>
                                      <b-badge v-if="historico.situacao === 'AP'" variant="success">Aprovado</b-badge>
                                      <b-badge v-if="historico.situacao === 'RE'" variant="danger">Recusado</b-badge>
                                      <b-badge v-if="historico.situacao === 'DV'" variant="warning">Devolvido</b-badge>
                                      <b-badge v-if="historico.situacao === 'CV'" variant="warning">Convocado</b-badge>
                                    </td>
                                    <td tabindex="0">{{ historico.justificativa }}</td>
                                  </tr>
                                </tbody>
                                <tbody v-else>
                                  <tr class="action">
                                    <td tabindex="0" colspan="4">Nenhum histórico solicitado</td>
                                  </tr>
                                </tbody>
                              </table>
                            </b-col>
                          </b-row>
                        </span>
                      </b-card>
                    </b-collapse>
                  </b-card>
                </b-col>
              </b-row>
            </span>
          </b-form>
        </b-card-body>
        <b-card-footer class="p-12">
          <b-row>
            <b-col md="3" sm="12">
              <b-button block variant="secondary" :disabled="disable_buttons" @click="voltar">
                <em class="fa fa-mail-reply-all"></em>&nbsp;&nbsp;<span>Voltar</span>
              </b-button>
            </b-col>
          </b-row>
        </b-card-footer>
      </b-card>

      <b-modal @cancel="limparModalRecurso" :hide-header-close="true" no-close-on-backdrop no-close-on-esc
        :hide-footer="true" cancel-title="Voltar" centered v-model="modalRecurso" size="lg" :title="titleModalRecurso">
        <b-container fluid>
          <b-row>
            <b-col cols="6">
              <strong>Número do cronograma: </strong>{{ cronograma ? cronograma.numero : '' }}
            </b-col>
          </b-row>
          <br>

          <b-row>
            <b-col cols="12">
              <b-form-group tabindex="0" label="Justificativa:">
                <b-form-textarea v-model="form_recurso.justificativa" :rows="5" :max-rows="5"></b-form-textarea>
              </b-form-group>
            </b-col>
          </b-row>
          <br>
          <b-row>
            <b-col md="8" sm="12">
              <label tabindex="0">Inserir documento para recurso: *</label>
              <span class="icon-info" v-b-popover.hover.top="'DOC, DOCX e PDF'" title="Extensões aceitas"></span>
              <b-form-group label-for="nome-input">
                <b-form-file ref="docRecurso" class="fileInput" accept=".doc, .docx, .pdf" v-on:change="uploadRecurso"
                  v-model="form_recurso.arquivo" browse-text="Selecione" placeholder="Upload"></b-form-file>
              </b-form-group>
            </b-col>
          </b-row>
          <b-row>
            <b-col md="12" sm="12">
              <table aria-describedby="my-table" aria-rowcount="4" id="my-table" aria-busy="false" aria-colcount="7"
                class="table table-responsive-sm table-bordered table-striped table-sm">
                <thead>
                  <tr>
                    <th tabindex="0" scope="col">Arquivo</th>
                    <th tabindex="0" scope="col" style="width: 10%">Ação</th>
                  </tr>
                </thead>
                <tbody v-if="form_recurso.arquivos.length > 0">
                  <tr class="action" v-for="arquivo in form_recurso.arquivos" :key="arquivo.id">
                    <td tabindex="0">{{ arquivo.nome }}</td>
                    <td tabindex="0">
                      <b-button @click="downloadBase64(arquivo.arquivo)">
                        <em class="fa fa-download"></em>&nbsp;
                        Download
                      </b-button>
                    </td>
                  </tr>
                </tbody>
                <tbody v-else>
                  <tr class="action">
                    <td tabindex="0" colspan="2">Nenhum arquivo inserido</td>
                  </tr>
                </tbody>
              </table>
            </b-col>
          </b-row>

          <hr>
          <b-row>
            <b-col cols="4">
              <b-button block variant="primary" :disabled="disable_buttons" @click="enviarRecurso">
                <em class="fa fa-plus"></em>&nbsp;
                <span>Salvar</span>
              </b-button>
            </b-col>
            <b-col cols="4">
              <b-button block variant="secondary" :disabled="disable_buttons" @click="limparModalRecurso">
                <em class="fa fa-close"></em>&nbsp;
                <span>Fechar</span>
              </b-button>
            </b-col>
          </b-row>
        </b-container>
      </b-modal>

      <b-modal @cancel="limparModalTermoConvocacao" :hide-header-close="true" no-close-on-backdrop no-close-on-esc
        :hide-footer="true" cancel-title="Voltar" centered v-model="modalTermoConvocacao" size="lg"
        :title="titleModalTermoConvocacao">
        <b-container fluid>
          <b-row>
            <b-col cols="6">
              <strong>Número do cronograma: </strong>{{ cronograma ? cronograma.numero : '' }}
            </b-col>
          </b-row>
          <br>

          <b-row>
            <b-col md="8" sm="12">
              <label tabindex="0">Inserir documento para recurso: *</label>
              <span class="icon-info" v-b-popover.hover.top="'DOC, DOCX e PDF'" title="Extensões aceitas"></span>
              <b-form-group label-for="nome-input">
                <b-form-file ref="docRecurso" class="fileInput" accept=".doc, .docx, .pdf"
                  v-on:change="uploadTermoConvocacao" v-model="form_termo_convocacao.termo_convocacao"
                  browse-text="Selecione" placeholder="Upload"></b-form-file>
              </b-form-group>
            </b-col>
          </b-row>

          <hr>
          <b-row>
            <b-col cols="4">
              <b-button block variant="primary" :disabled="disable_buttons" @click="enviarTermoConvocacao">
                <em class="fa fa-plus"></em>&nbsp;
                <span>Salvar</span>
              </b-button>
            </b-col>
            <b-col cols="4">
              <b-button block variant="secondary" :disabled="disable_buttons" @click="limparModalTermoConvocacao">
                <em class="fa fa-close"></em>&nbsp;
                <span>Fechar</span>
              </b-button>
            </b-col>
          </b-row>
        </b-container>
      </b-modal>

    </b-col>
  </span>
</template>

<script>
import Vue from 'vue'
import VueSelect from 'vue-select'
import miniToastr from 'mini-toastr'
import Multiselect from 'vue-multiselect'
import Datepicker from 'vuejs-datepicker'
import { ptBR } from 'vuejs-datepicker/dist/locale'
import PaginationPage from '@/components/shared/PaginationPage.vue'

miniToastr.init()

export default {
  name: 'AdesaoAcompanhar',
  components: {
    VueSelect,
    Multiselect,
    Datepicker,
    PaginationPage
  },
  data () {
    return {
      loading: false,
      cronograma: null,
      // habilitados: [],
      // classificados: [],
      // convocados: [],
      habilitadosId: null,
      classificadosId: null,
      convocadosId: null,
      recursoAdesao: null,
      recursoClassificacao: null,
      adesao: null,
      titleModalRecurso: '',
      modalRecurso: false,
      form_recurso: {
        tipo: null,
        adesao_id: null,
        justificativa: null,
        arquivo: null,
        arquivos: []
      },
      modalTermoConvocacao: false,
      titleModalTermoConvocacao: null,
      form_termo_convocacao: {
        adesao_id: null,
        termo_convocacao: null
      },
      visualizar: false,
      disable_buttons: false,
      salvarForm: false,
      ptBR: ptBR
    }
  },
  computed: {
    habilitados () {
      let result = this.$store.state.pagination.lists.habilitados
      return result !== undefined ? result.data : []
    },
    classificados () {
      let result = this.$store.state.pagination.lists.classificados
      return result !== undefined ? result.data : []
    },
    convocados () {
      let result = this.$store.state.pagination.lists.convocados
      return result !== undefined ? result.data : []
    },
    apresentarHabilitados () {
      if (this.cronograma === null) {
        return false
      }

      if (this.cronograma.data_divulgacao_adesao_inicio === null) {
        return false
      }

      let dataAtual = new Date()
      let dataDivulgacao = new Date(this.cronograma.data_divulgacao_adesao_inicio)

      return dataAtual >= dataDivulgacao
    },
    permitirRecursoHabilitados () {
      if (!this.apresentarHabilitados) {
        return false
      }

      if (this.cronograma.data_recurso_adesao_inicio === null) {
        return false
      }

      let dataAtual = new Date()
      let dataInicio = new Date(this.cronograma.data_recurso_adesao_inicio)
      let comparacao = null

      if (this.cronograma.data_recurso_adesao_fim === null) {
        comparacao = dataAtual >= dataInicio
      } else {
        let dataFim = new Date(this.cronograma.data_recurso_adesao_fim)
        comparacao = dataAtual >= dataInicio && dataAtual <= dataFim
      }

      if (!comparacao) {
        return false
      }

      if (this.recursoAdesao !== null && this.adesao !== null && this.adesao !== undefined) {
        return (this.adesao.situacao === 'DV' && this.adesao.tipo === 'RA') || (this.adesao.situacao === 'RE' && this.adesao.tipo === 'AD')
      } else {
        return this.adesao !== null && this.adesao.tipo === 'AD' && this.adesao.situacao === 'RE'
      }
    },
    apresentarClassificados () {
      if (this.cronograma === null) {
        return false
      }

      if (this.cronograma.data_divulgacao_habilitados_inicio === null) {
        return false
      }

      let dataAtual = new Date()
      let dataDivulgacao = new Date(this.cronograma.data_divulgacao_habilitados_inicio)

      return dataAtual >= dataDivulgacao
    },
    permitirRecursoClassificacao () {
      if (!this.apresentarClassificados) {
        return false
      }

      if (this.cronograma.data_recurso_habilitados_inicio === null) {
        return false
      }

      let dataAtual = new Date()
      let dataInicio = new Date(this.cronograma.data_recurso_habilitados_inicio)
      let comparacao = null

      if (this.cronograma.data_recurso_habilitados_fim === null) {
        comparacao = dataAtual >= dataInicio
      } else {
        let dataFim = new Date(this.cronograma.data_recurso_habilitados_fim)
        comparacao = dataAtual >= dataInicio && dataAtual <= dataFim
      }

      if (!comparacao) {
        return false
      }

      if (this.recursoClassificacao !== null && this.adesao !== null && this.adesao !== undefined) {
        return (this.adesao.situacao === 'DV' && this.adesao.tipo === 'RC') || (this.adesao.situacao === 'AP' && (this.adesao.tipo === 'AD' || this.adesao.tipo === 'RA'))
      } else {
        return this.adesao !== null && this.adesao.situacao === 'AP'
      }
    },
    apresentarConvocados () {
      if (this.cronograma === null) {
        return false
      }

      if (this.cronograma.data_divulgacao_lista === null) {
        return false
      }

      let dataAtual = new Date()
      let dataDivulgacao = new Date(this.cronograma.data_divulgacao_lista)

      return dataAtual >= dataDivulgacao
    },
    isValid () {
      return !this.$v.form.$anyError
    }
  },
  created () {
    this.loadAdesao(this.$route.params.id)
  },
  mounted () {
    this.loadCronograma(this.$route.params.cronograma)
  },
  methods: {
    can (permissao) {
      return this.$store.getters.validaPermissao(permissao)
    },
    loadAdesao (id) {
      Vue.http.get('api/adesao/' + id).then(response => {
        let data = response.body.data
        this.adesao = data

        this.loadRecursoAdesao(data.id)
        this.loadRecursoClassificacao(data.id)
      })
    },
    loadCronograma (id) {
      Vue.http.get('api/cronograma/' + id).then(response => {
        let data = response.body.data
        this.cronograma = data

        this.loadHabilitados(data.id)
        this.loadClassificados(data.id)
        this.loadConvocados(data.id)
      })
    },
    loadHabilitados (id) {
      this.habilitadosId = id
      // Vue.http.get('api/adesao/' + id + '/habilitado').then(response => {
      //   let data = response.body.data
      //   this.habilitados = data
      // })
    },
    loadClassificados (id) {
      this.classificadosId = id
      // Vue.http.get('api/adesao/' + id + '/classificado').then(response => {
      //   let data = response.body.data
      //   this.classificados = data
      // })
    },
    loadConvocados (id) {
      this.convocadosId = id
      // Vue.http.get('api/adesao/' + id + '/convocado').then(response => {
      //   let data = response.body.data
      //   this.convocados = data
      // })
    },
    loadRecursoAdesao (id) {
      Vue.http.get('api/adesao/' + id + '/recurso-adesao').then(response => {
        let data = response.body.data
        this.recursoAdesao = data.length != 0 ? data : null
      })
    },
    loadRecursoClassificacao (id) {
      Vue.http.get('api/adesao/' + id + '/recurso-classificado').then(response => {
        let data = response.body.data
        this.recursoClassificacao = data.length != 0 ? data : null
      })
    },
    modalRecursoAdesao () {
      this.modalRecurso = true
      this.titleModalRecurso = 'Incluir Recurso'
      this.form_recurso.adesao_id = this.adesao.id
      this.form_recurso.tipo = 'RA'
      this.form_recurso.justificativa = null
      this.form_recurso.arquivos = []
    },
    modalRecursoClassificacao () {
      this.modalRecurso = true
      this.titleModalRecurso = 'Incluir Recurso'
      this.form_recurso.adesao_id = this.adesao.id
      this.form_recurso.tipo = 'RC'
      this.form_recurso.justificativa = null
      this.form_recurso.arquivos = []
    },
    modalTermo () {
      this.modalTermoConvocacao = true
      this.titleModalTermoConvocacao = 'Incluir Termo de Convocação'
      this.form_termo_convocacao.adesao_id = this.adesao.id
      this.form_termo_convocacao.termo_convocacao = null
    },
    enviarRecurso () {
      this.$bvModal.msgBoxConfirm('Tem certeza que deseja continuar?', {
        okTitle: 'Confirmar',
        cancelTitle: 'Cancelar'
      }).then(valor => {
        if (valor) {
          let url = ''
          if (this.form_recurso.tipo === 'RA') {
            url = 'api/adesao/' + this.form_recurso.adesao_id + '/recurso-adesao'
          }

          if (this.form_recurso.tipo === 'RC') {
            url = 'api/adesao/' + this.form_recurso.adesao_id + '/recurso-classificado'
          }

          let data = {
            justificativa: this.form_recurso.justificativa,
            arquivos: JSON.stringify(this.form_recurso.arquivos)
          }

          Vue.http.post(url, data).then(response => {
            if (response.body.success) {
              this.modalRecurso = false
              this.sucesso(response.body.msg)
              this.loadAdesao(this.adesao.id)
            } else {
              this.erro(response.body.msg)
            }
          })
        }
      }).catch(erro => {
        console.log(erro)
      })
    },
    enviarTermoConvocacao () {
      this.$bvModal.msgBoxConfirm('Tem certeza que deseja continuar?', {
        okTitle: 'Confirmar',
        cancelTitle: 'Cancelar'
      }).then(valor => {
        if (valor) {
          let url = 'api/adesao/' + this.form_termo_convocacao.adesao_id + '/termo'
          let data = {
            arquivo: this.form_termo_convocacao.termo_convocacao
          }

          Vue.http.post(url, data).then(response => {
            if (response.body.success) {
              this.modalTermoConvocacao = false
              this.sucesso(response.body.msg)
              this.loadAdesao(this.adesao.id)
            } else {
              this.erro(response.body.msg)
            }
          })
        }
      }).catch(erro => {
        console.log(erro)
      })
    },
    limparModalRecurso () {
      this.modalRecurso = false
      this.form_recurso.tipo = null
      this.form_recurso.adesao_id = null
      this.form_recurso.justificativa = null
      this.form_recurso.arquivos = []
    },
    limparModalTermoConvocacao () {
      this.modalTermoConvocacao = false
      this.form_termo_convocacao.adesao_id = null
      this.form_termo_convocacao.termo_convocacao = null
    },
    uploadRecurso (evento) {
      let arquivo = evento.target.files || evento.dataTransfer.files
      if (!arquivo.length) {
        return false
      }
      if (arquivo[0].size > 15728640) {
        this.erro('Tamanho do arquivo maior que 15MB.')
        this.$refs.docRecurso.reset()
        return false
      }
      let reader = new FileReader()
      let nome = arquivo[0].name
      reader.onload = (evento) => {
        this.form_recurso.arquivos.push({
          nome: nome,
          arquivo: evento.target.result
        })
      }

      console.log(this.form_recurso.arquivos)

      reader.readAsDataURL(arquivo[0])
    },
    uploadTermoConvocacao (evento) {
      let arquivo = evento.target.files || evento.dataTransfer.files
      if (!arquivo.length) {
        return false
      }
      if (arquivo[0].size > 15728640) {
        this.erro('Tamanho do arquivo maior que 15MB.')
        this.$refs.docTermo.reset()
        return false
      }
      let reader = new FileReader()
      let nome = arquivo[0].name
      reader.onload = (evento) => {
        this.form_termo_convocacao.termo_convocacao = evento.target.result
      }

      console.log(this.form_recurso.arquivos)

      reader.readAsDataURL(arquivo[0])
    },
    download (origem, id) {
      this.$abrirDocumento(origem, id)
    },
    downloadBase64 (nome, arquivo) {
      let link = document.createElement('a')
      link.href = arquivo
      link.download = nome
      link.click()
      link.remove()
    },
    navigate (route) {
      this.$router.push(route)
    },
    voltar () {
      this.$router.go(-1)
    },
    sucesso (mensagem) {
      this.$bvToast.toast(mensagem, {
        title: 'Sucesso',
        autoHideDelay: 10000,
        appendToast: true,
        variant: 'info'
      })
    },
    erro (mensagem) {
      this.$bvToast.toast(mensagem, {
        title: 'Erro',
        autoHideDelay: 10000,
        appendToast: true,
        variant: 'danger'
      })
    },
  }
}
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style>
.info-name {
  display: inline-block;
  width: fit-content;
  margin-left: 0;
  margin-right: auto;
}

.collapsed>.when-closed,
:not(.collapsed)>.when-opened {
  display: none;
}

.collapse-button {
  display: block;
  float: right;
}
</style>
