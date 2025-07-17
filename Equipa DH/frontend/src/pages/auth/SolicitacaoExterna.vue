<template>
  <span>
    <br>
    <b-col md="8" offset="2">
      <b-card no-body>
        <b-card-body>
          <b-form v-on:submit.prevent="onSubmit">
            <h1 tabindex="0"><strong>Solicitação de Credenciamento</strong></h1>
            <b-row>
              <b-col cols="12">
                <strong>Dados pessoais</strong>
                <br />
              </b-col>
            </b-row>
            <br />

            <b-row>
              <b-col md="6" sm="12">
                <b-form-group tabindex="0" label="Nome completo: *">
                  <b-input-group class="mb-3">
                    <b-form-input type="text" class="form-control" placeholder="" v-model.trim="form.nome"
                      :state="chkState('nome')" :disabled="visualizar" />
                    <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col>
              <b-col md="6" sm="12">
                <b-form-group tabindex="0" label="Nome social:">
                  <b-input-group class="mb-3">
                    <b-form-input type="text" class="form-control" placeholder="" v-model.trim="form.nome_social"
                      :state="chkState('nome_social')" :disabled="visualizar" />
                    <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col>
            </b-row>
            <b-row>
              <b-col md="6" sm="12">
                <b-form-group tabindex="0" label="CPF: *">
                  <b-input-group class="mb-3">
                    <b-form-input v-mask="'###.###.###-##'" type="text" class="form-control"
                      placeholder="___.___.___-__" autocomplete="cpf" v-model="form.cpf" :state="chkState('cpf')"
                      :disabled="visualizar" />
                    <b-form-invalid-feedback v-if="!$v.form.cpf.required">
                      Campo obrigatório!
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-if="!$v.form.cpf.validCPF">
                      CPF inválido!
                    </b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col>
              <b-col md="6" sm="12">
                <b-form-group label="Data de Nascimento: *">
                  <b-input-group>
                    <b-form-input id="type-date" :disabled="visualizar" :state="chkState('data_nascimento')" type="date"
                      v-model="$v.form.data_nascimento.$model">
                    </b-form-input>
                    <b-form-invalid-feedback v-if="!$v.form.data_nascimento.required">
                      Campo obrigatório!
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-if="!$v.form.data_nascimento.validDate">
                      Data inválida!
                    </b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col>
            </b-row>

            <b-row>
              <b-col lg="3" md="4" sm="12">
                <b-form-group tabindex="0" label="Identidade de gênero: *">
                  <b-form-select v-model="form.sexo_id" placeholder="Selecione" :state="chkState('sexo_id')"
                    :disabled="visualizar">
                    <option selected :value="null">Selecione</option>
                    <option v-for="item in sexos" :key="item.id" :value="item.id">{{ item.nome }}</option>
                  </b-form-select>
                  <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
                </b-form-group>
              </b-col>
              <b-col lg="3" md="4" sm="12" v-if="form.sexo_id === 7">
                <b-form-group tabindex="0" label="Identidade de gênero(outro): *">
                  <b-input-group class="mb-3">
                    <b-form-input type="text" class="form-control" placeholder="" v-model.trim="form.sexo_outro"
                      :state="chkState('sexo_outro')" :disabled="visualizar" />
                    <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col>
              <b-col lg="3" md="4" sm="12">
                <b-form-group tabindex="0" label="Orientação sexual: *">
                  <b-form-select v-model="form.orientacao_sexual_id" placeholder="Selecione"
                    :state="chkState('orientacao_sexual_id')" :disabled="visualizar">
                    <option selected :value="null">Selecione</option>
                    <option v-for="item in orientacoesSexuais" :key="item.id" :value="item.id">{{ item.nome }}</option>
                  </b-form-select>
                  <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
                </b-form-group>
              </b-col>
              <b-col lg="3" md="4" sm="12" v-if="form.orientacao_sexual_id === 6">
                <b-form-group tabindex="0" label="Orientação sexual (outro): *">
                  <b-input-group class="mb-3">
                    <b-form-input type="text" class="form-control" placeholder=""
                      v-model.trim="form.orientacao_sexual_outro" :state="chkState('orientacao_sexual_outro')"
                      :disabled="visualizar" />
                    <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col>
            </b-row>

            <b-row>
              <b-col md="3" sm="12">
                <b-form-group tabindex="0" label="Raça/etnia: *">
                  <b-form-select v-model="form.raca_id" placeholder="Selecione" :state="chkState('raca_id')"
                    :disabled="visualizar">
                    <option selected :value="null">Selecione</option>
                    <option v-for="item in racas" :key="item.id" :value="item.id">{{ item.nome }}</option>
                  </b-form-select>
                  <b-form-invalid-feedback :state="chkState('raca_id')">
                    Campo obrigatório!
                  </b-form-invalid-feedback>
                </b-form-group>
              </b-col>
              <b-col md="3" sm="12" v-if="form.raca_id === 9">
                <b-form-group tabindex="0" label="Raça/etnia(outro): *">
                  <b-input-group class="mb-3">
                    <b-form-input type="text" class="form-control" placeholder="" v-model.trim="form.raca_outro"
                      :state="chkState('raca_outro')" :disabled="visualizar" />
                    <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col>
              <b-col md="3" sm="12">
                <b-form-group tabindex="0" label="Escolaridade: *">
                  <b-form-select v-model="form.escolaridade_id" placeholder="Selecione"
                    :state="chkState('escolaridade_id')" :disabled="visualizar">
                    <option selected :value="null">Selecione</option>
                    <option v-for="item in escolaridades" :key="item.id" :value="item.id">{{ item.nome }}</option>
                  </b-form-select>
                  <b-form-invalid-feedback :state="chkState('escolaridade_id')">
                    Campo obrigatório!
                  </b-form-invalid-feedback>
                </b-form-group>
              </b-col>
              <b-col md="3" sm="12">
                <b-form-group tabindex="0" label="Possui deficiência: *">
                  <b-form-select v-model="form.possui_deficiencia" placeholder="Selecione"
                    :state="chkState('possui_deficiencia')" :disabled="visualizar">
                    <option selected :value="null">Selecione</option>
                    <option v-for="item in options" :key="item.value" :value="item.value">{{ item.text }}</option>
                  </b-form-select>
                  <b-form-invalid-feedback :state="chkState('possui_deficiencia')">
                    Campo obrigatório!
                  </b-form-invalid-feedback>
                </b-form-group>
              </b-col>
              <b-col md="3" sm="12" v-if="form.possui_deficiencia">
                <b-form-group tabindex="0" label="Qual: *">
                  <b-input-group class="mb-3">
                    <b-form-select v-model="form.deficiencia_id" placeholder="Selecione"
                      :state="chkState('deficiencia_id')" :disabled="visualizar">
                      <option selected :value="null">Selecione</option>
                      <option v-for="item in deficiencias" :key="item.id" :value="item.id">{{ item.nome }}</option>
                    </b-form-select>
                    <b-form-invalid-feedback :state="chkState('deficiencia_id')">
                      Campo obrigatório!
                    </b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col>
            </b-row>

            <b-row>
              <b-col cols="12">
                <strong>Meus dados funcionais</strong>
                <br />
              </b-col>
            </b-row>
            <br />
            <b-row>
              <b-col md="3" sm="12">
                <b-form-group tabindex="0" label="Tipo de atuação: *">
                  <b-form-select v-model="form.tipo_representacao" placeholder="Selecione"
                    :state="chkState('tipo_representacao')" :disabled="visualizar">
                    <option selected :value="null">Selecione</option>
                    <option v-for="item in options_tipo_atuacao" :key="item.value" :value="item.value">{{ item.text }}
                    </option>
                  </b-form-select>
                  <b-form-invalid-feedback :state="chkState('tipo_representacao')">
                    Campo obrigatório!
                  </b-form-invalid-feedback>
                </b-form-group>
              </b-col>
            </b-row>
            <b-row>
              <b-col md="3" sm="12">
                <b-form-group tabindex="0" label="Cargo / Função: *">
                  <b-input-group class="mb-3">
                    <b-form-input type="text" class="form-control" v-model.trim="form.cargo" :state="chkState('cargo')"
                      :disabled="visualizar" />
                    <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col>
              <b-col md="3" sm="12">
                <b-form-group tabindex="0" label="Telefone funcional: *">
                  <b-input-group class="mb-3">
                    <b-form-input type="text" class="form-control"
                      v-mask="['+55 (##) ####-####', '+55 (##) #####-####']"
                      v-model.trim="$v.form.telefone_funcional.$model" :state="chkState('telefone_funcional')"
                      :disabled="visualizar" />
                    <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col>
              <b-col md="3" sm="12">
                <b-form-group tabindex="0" label="Celular: *">
                  <b-input-group class="mb-3">
                    <b-form-input type="text" class="form-control"
                      v-mask="['+55 (##) ####-####', '+55 (##) #####-####']" v-model.trim="$v.form.celular.$model"
                      :state="chkState('celular')" :disabled="visualizar" />
                    <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col>
              <b-col md="3" sm="12">
                <b-form-group tabindex="0" label="E-mail funcional: *">
                  <b-input-group class="mb-3">
                    <b-form-input type="text" class="form-control" v-model.trim="form.email_funcional"
                      :state="chkState('email_funcional')" :disabled="visualizar" />
                    <b-form-invalid-feedback v-if="!$v.form.email_funcional.required">
                      Campo obrigatório!
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-if="!$v.form.email_funcional.email">
                      E-mail inválido!
                    </b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col>
            </b-row>

            <b-row>
              <b-col cols="12">
                <strong>Dados da Instituição de Atuação </strong>
                <br />
              </b-col>
            </b-row>
            <br />

            <b-row>
              <b-col md="6" sm="12">
                <b-form-group tabindex="0" label="Instituição: ">
                  <b-input-group>
                    <multiselect v-model="selectedInstituicao" selectLabel="Selecionar" selectedLabel="Selecionada"
                      deselect-label="Remover" placeholder="Digite CNPJ ou Nome da Instituição" track-by="id"
                      label="razao_cnpj" :multiple="false" :options="instituicoes" :disabled="visualizar">
                      <template v-slot:noOptions>
                        Nenhuma instituição encontrada.
                      </template>
                    </multiselect>
                    <label style="font-size: 13px;" class="form-text text-muted">Caso não encontre sua instituição,
                      pesquise por "<b>Outra
                        instituição</b>" para habilitar a inserção manual de sua instituição!</label>
                    <b-button v-if="selectedInstituicao && selectedInstituicao.id !== 'outra'" block variant="primary"
                      @click="visualizarInstituicao(null, selectedInstituicao)">
                      <em class="fa fa-search"></em>&nbsp;
                      <span>Visualizar Dados da Instituição</span>
                    </b-button>
                  </b-input-group>
                </b-form-group>
              </b-col>
            </b-row>
            <b-row v-if="exibirOutraInstituicao">
              <b-col md="4" sm="12" v-if="!visualizar && form.instituicoes.length < 1">
                <b-button block variant="primary" :disabled="visualizar" @click="abrirModalInstituicao">
                  <em class="fa fa-plus"></em>&nbsp;
                  <span>Incluir Dados da Instituição</span>
                </b-button>
              </b-col>
            </b-row>
            <br />

            <b-row v-if="exibirOutraInstituicao">
              <b-col cols="12">
                <table aria-rowcount="4" id="my-table" aria-busy="false" aria-colcount="7"
                  class="table table-responsive-sm table-bordered table-striped table-sm">
                  <thead>
                    <tr>
                      <th tabindex="0" scope="col">Nome da Instituição</th>
                      <th tabindex="0" scope="col">CNPJ</th>
                      <th tabindex="0" scope="col">Ações</th>
                    </tr>
                  </thead>
                  <tbody v-if="form.instituicoes.length">
                    <tr class="action" v-for="(item, index) in form.instituicoes" :key="item.cnpj">
                      <td tabindex="0">{{ item.razao_social }}</td>
                      <td tabindex="0">{{ item.cnpj }}</td>
                      <td style="width: 1%; white-space: nowrap;">
                        <a v-if="!visualizar" class="btn btn-sm btn-secondary action" title="Editar"
                          @click="editarInstituicao(index)">
                          <i class="fa fa-pencil"></i>
                        </a>
                        <a v-if="!visualizar" class="btn btn-sm btn-secondary action" title="Excluir"
                          @click="excluirInstituicao(index)">
                          <i class="fa fa-trash"></i>
                        </a>
                        <a v-if="visualizar" class="btn btn-sm btn-secondary action" title="Visualizar"
                          @click="visualizarInstituicao(index)">
                          <i class="fa fa-search"></i>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr class="action">
                      <td tabindex="0" colspan="3">Nenhuma instituição cadastrada.</td>
                    </tr>
                  </tbody>
                </table>
              </b-col>
            </b-row>
            <br v-if="exibirOutraInstituicao">

            <b-row v-if="form.tipo_representacao">
              <b-col cols="12">
                <strong>Documentação obrigatória</strong>
                <br />
              </b-col>
            </b-row>
            <br />

            <b-row v-if="form.tipo_representacao">
              <b-col md="8" sm="12">
                <label tabindex="0">RG/CNH: *</label>
                <span class="icon-info" v-b-popover.hover.top="'PDF, DOC, DOCX, JPG, JPEG e PNG'"
                  title="Extensões aceitas" v-if="!visualizar"></span>
                <b-form-group label-for="nome-input" v-if="!visualizar">
                  <b-form-file ref="docRg" class="fileInput" accept=".png, .jpeg, .jpg, .pdf, .docx, .doc"
                    v-on:change="uploadDocumentoRg" v-model="form.documento_rg" browse-text="Selecione"
                    placeholder="Upload" :state="chkState('documento_rg')"></b-form-file>
                  <b-form-invalid-feedback :state="chkState('documento_rg')">Campo
                    obrigatório.</b-form-invalid-feedback>
                </b-form-group>
                <b-button v-if="form.documento_rg && form.id" @click="downloadDocumento('solicitacao_rg', form.id)"
                  variant="primary" size="sm">
                  <i class="fa fa-download"></i>
                </b-button>
              </b-col>
            </b-row>

            <b-row v-if="form.tipo_representacao">
              <b-col md="8" sm="12">
                <label tabindex="0">CPF: *</label>
                <span class="icon-info" v-b-popover.hover.top="'PDF, DOC, DOCX, JPG, JPEG e PNG'"
                  title="Extensões aceitas" v-if="!visualizar"></span>
                <b-form-group label-for="nome-input" v-if="!visualizar">
                  <b-form-file ref="docCPF" class="fileInput" accept=".png, .jpeg, .jpg, .pdf, .docx, .doc"
                    v-on:change="uploadDocumentoCpf" v-model="form.documento_cpf" browse-text="Selecione"
                    placeholder="Upload" :state="chkState('documento_cpf')"></b-form-file>
                  <b-form-invalid-feedback :state="chkState('documento_cpf')">Campo
                    obrigatório.</b-form-invalid-feedback>
                </b-form-group>
                <b-button v-if="form.documento_cpf && form.id" @click="downloadDocumento('solicitacao_cpf', form.id)"
                  variant="primary" size="sm">
                  <i class="fa fa-download"></i>
                </b-button>
              </b-col>
            </b-row>

            <b-row v-if="form.tipo_representacao">
              <b-col lg="8" md="10" sm="12">
                <label tabindex="0">Ato ou declaração de investidura em cargo ou função: *</label>
                <span class="icon-info" v-b-popover.hover.top="'PDF, DOC, DOCX, JPG, JPEG e PNG'"
                  title="Extensões aceitas" v-if="!visualizar"></span>
                <b-form-group label-for="nome-input" v-if="!visualizar">
                  <b-input-group>
                    <b-form-file ref="docAtoPosse" class="fileInput" accept=".png, .jpeg, .jpg, .pdf, .docx, .doc"
                      v-on:change="uploadDocumentoAtoPosse" v-model="form.documento_ato_posse" browse-text="Selecione"
                      placeholder="Upload" :state="chkState('documento_ato_posse')"></b-form-file>
                    <!-- <b-input-group-append v-if="configuracao && configuracao.arquivo_ato_declaracao">
                      <b-button block variant="primary" @click="downloadDocumento(configuracao.arquivo_ato_declaracao)">
                        <em class="fa fa-download"></em>&nbsp;
                        Modelo do Documento
                      </b-button>
                    </b-input-group-append> -->
                  </b-input-group>
                  <b-form-invalid-feedback :state="chkState('documento_ato_posse')">Campo
                    obrigatório.</b-form-invalid-feedback>
                </b-form-group>
                <b-button v-if="form.documento_ato_posse && form.id"
                  @click="downloadDocumento('solicitacao_ato_posse', form.id)" variant="primary" size="sm">
                  <i class="fa fa-download"></i>
                </b-button>
              </b-col>
            </b-row>

            <b-row v-if="form.tipo_representacao">
              <b-col lg="8" md="10" sm="12">
                <label tabindex="0">Ato de delegação de competência <span
                    v-if="form.tipo_representacao === 'A'">(Opcional)</span>:
                  <span v-if="form.tipo_representacao === 'R'">*</span></label>
                <span class="icon-info" v-b-popover.hover.top="'PDF, DOC, DOCX, JPG, JPEG e PNG'"
                  title="Extensões aceitas" v-if="!visualizar"></span>
                <b-form-group label-for="nome-input" v-if="!visualizar">
                  <b-input-group>
                    <b-form-file ref="docAtoDelegacao" class="fileInput" accept=".png, .jpeg, .jpg, .pdf, .docx, .doc"
                      v-on:change="uploadDocumentoAtoDelegacao" v-model="form.documento_ato_delegacao"
                      browse-text="Selecione" placeholder="Upload"
                      :state="chkState('documento_ato_delegacao')"></b-form-file>
                    <b-input-group-append v-if="configuracao && configuracao.arquivo_ato_delegacao">
                      <b-button block variant="primary" @click="download(configuracao.arquivo_ato_delegacao)">
                        <em class="fa fa-download"></em>&nbsp;
                        Modelo do Documento
                      </b-button>
                    </b-input-group-append>
                  </b-input-group>
                  <b-form-invalid-feedback :state="chkState('documento_ato_delegacao')">Campo
                    obrigatório.</b-form-invalid-feedback>
                </b-form-group>
                <b-button v-if="form.documento_ato_delegacao && form.id"
                  @click="downloadDocumento('solicitacao_ato_delegacao', form.documento_ato_delegacao)"
                  variant="primary" size="sm">
                  <i class="fa fa-download"></i>
                </b-button>
              </b-col>
            </b-row>

            <b-row class="mt-4" v-if="form.tipo_representacao === 'R'">
              <b-col class="mb-3" cols="12">
                <strong>Documentação da Autoridade Máxima:</strong>
                <br />
              </b-col>

              <b-col md="8" sm="12">
                <label tabindex="0">RG/CNH: *</label>
                <span class="icon-info" v-b-popover.hover.top="'PDF, DOC, DOCX, JPG, JPEG e PNG'"
                  title="Extensões aceitas" v-if="!visualizar"></span>
                <b-form-group label-for="nome-input" v-if="!visualizar">
                  <b-form-file ref="docRgAutoridade" class="fileInput" accept=".png, .jpeg, .jpg, .pdf, .docx, .doc"
                    v-on:change="uploadDocumentoRgAutoridade" v-model="form.autoridade_rg" browse-text="Selecione"
                    placeholder="Upload" :state="chkState('autoridade_rg')">
                  </b-form-file>
                  <b-form-invalid-feedback :state="chkState('autoridade_rg')">Campo
                    obrigatório.</b-form-invalid-feedback>
                </b-form-group>
                <b-button v-if="form.autoridade_rg && form.id"
                  @click="downloadDocumento('solicitacao_auto_rg', form.id)" variant="primary" size="sm">
                  <i class="fa fa-download"></i>
                </b-button>
              </b-col>

              <b-col md="8" sm="12">
                <label tabindex="0">CPF: *</label>
                <span class="icon-info" v-b-popover.hover.top="'PDF, DOC, DOCX, JPG, JPEG e PNG'"
                  title="Extensões aceitas" v-if="!visualizar"></span>
                <b-form-group label-for="nome-input" v-if="!visualizar">
                  <b-form-file ref="docCPFAutoridade" class="fileInput" accept=".png, .jpeg, .jpg, .pdf, .docx, .doc"
                    v-on:change="uploadDocumentoCpfAutoridade" v-model="form.autoridade_cpf" browse-text="Selecione"
                    placeholder="Upload" :state="chkState('autoridade_cpf')">
                  </b-form-file>
                  <b-form-invalid-feedback :state="chkState('autoridade_cpf')">Campo
                    obrigatório.</b-form-invalid-feedback>
                </b-form-group>
                <b-button v-if="form.autoridade_cpf && form.id"
                  @click="downloadDocumento('solicitacao_auto_cpf', form.id)" variant="primary" size="sm">
                  <i class="fa fa-download"></i>
                </b-button>
              </b-col>

              <b-col lg="8" md="10" sm="12">
                <label tabindex="0">Ato ou declaração de investidura em cargo ou função: *</label>
                <span class="icon-info" v-b-popover.hover.top="'PDF, DOC, DOCX, JPG, JPEG e PNG'"
                  title="Extensões aceitas" v-if="!visualizar"></span>
                <b-form-group label-for="nome-input" v-if="!visualizar">
                  <b-input-group>
                    <b-form-file ref="docAtoPosseAutoridade" class="fileInput"
                      accept=".png, .jpeg, .jpg, .pdf, .docx, .doc" v-on:change="uploadDocumentoAtoPosseAutoridade"
                      v-model="form.autoridade_ato_posse" browse-text="Selecione" placeholder="Upload"
                      :state="chkState('autoridade_ato_posse')">
                    </b-form-file>
                  </b-input-group>
                  <b-form-invalid-feedback :state="chkState('autoridade_ato_posse')">Campo
                    obrigatório.</b-form-invalid-feedback>
                </b-form-group>
                <b-button v-if="form.autoridade_ato_posse && form.id"
                  @click="downloadDocumento('solicitacao_auto_ato_posse', form.id)" variant="primary" size="sm">
                  <i class="fa fa-download"></i>
                </b-button>
              </b-col>

              <b-col lg="8" md="10" sm="12">
                <label tabindex="0">Ato de delegação de competência (Opcional): </label>
                <span class="icon-info" v-b-popover.hover.top="'PDF, DOC, DOCX, JPG, JPEG e PNG'"
                  title="Extensões aceitas" v-if="!visualizar"></span>
                <b-form-group label-for="nome-input" v-if="!visualizar">
                  <b-input-group>
                    <b-form-file ref="docAtoDelegacaoAutoridade" class="fileInput"
                      accept=".png, .jpeg, .jpg, .pdf, .docx, .doc" v-on:change="uploadDocumentoAtoDelegacaoAutoridade"
                      v-model="form.autoridade_ato_delegacao" browse-text="Selecione" placeholder="Upload"
                      :state="chkState('autoridade_ato_delegacao')"></b-form-file>
                    <b-input-group-append v-if="configuracao && configuracao.arquivo_ato_delegacao">
                      <b-button block variant="primary" @click="download(configuracao.arquivo_ato_delegacao)">
                        <em class="fa fa-download"></em>&nbsp;
                        Modelo do Documento
                      </b-button>
                    </b-input-group-append>
                  </b-input-group>
                  <b-form-invalid-feedback :state="chkState('autoridade_ato_delegacao')">Campo
                    obrigatório.</b-form-invalid-feedback>
                </b-form-group>
                <b-button v-if="form.autoridade_ato_delegacao && form.id"
                  @click="downloadDocumento('solicitacao_auto_ato_delegacao', form.id)" variant="primary" size="sm">
                  <i class="fa fa-download"></i>
                </b-button>
              </b-col>
            </b-row>

            <b-row class="text-center mt-3">
              <b-col sm="12">
                <b-link @click="modalAviso = true">
                  AVISO DE PRIVACIDADE
                </b-link>
              </b-col>
              <b-col sm="12">
                <b-form-group>
                  <b-form-checkbox v-model="form.termo_aceite" :disabled="visualizar" :state="chkState('termo_aceite')">
                    Tenho ciência e declaro que li o aviso de privacidade.
                  </b-form-checkbox>
                  <b-form-invalid-feedback :state="chkState('termo_aceite')">
                    Campo obrigatório.
                  </b-form-invalid-feedback>
                </b-form-group>
              </b-col>
            </b-row>

          </b-form>
        </b-card-body>
        <b-card-footer class="p-12">
          <b-row>
            <b-col v-if="!loading && !visualizar" md="3" sm="12" key="new_p1">
              <b-button block variant="primary" :disabled="disable_buttons" v-on:click="onSubmit">
                <em class="fa fa-plus"></em>&nbsp;
                <span>Salvar</span>
              </b-button>
            </b-col>
            <b-col v-else-if="!visualizar" md="3" sm="12">
              <b-button block variant="primary" :disabled="true" v-on:click="onSubmit">
                <b-spinner small type="grow"></b-spinner>Salvando...
              </b-button>
            </b-col>
            <b-col md="3" sm="12">
              <b-button block variant="secondary" :disabled="disable_buttons" @click="voltar">
                <em class="fa fa-mail-reply-all"></em>&nbsp;&nbsp;<span>Voltar</span>
              </b-button>
            </b-col>
          </b-row>
        </b-card-footer>
      </b-card>
    </b-col>

    <b-modal @cancel="limparModalInstituicao" :hide-header-close="true" no-close-on-backdrop no-close-on-esc
      :hide-footer="true" cancel-title="Voltar" :ok-title="!visualizar ? 'Salvar' : 'Fechar'" centered
      v-model="modalInstituicao" size="lg" :title="titleModalInstituicao">
      <b-container fluid>
        <b-row>
          <b-col cols="6">
            <b-form-group tabindex="0" label="Razão Social: *">
              <b-form-input v-model="form_instituicao.razao_social" type="text"
                :disabled="visualizar || form_instituicao.visualizar"></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="6">
            <b-form-group tabindex="0" label="CNPJ: *">
              <b-form-input v-model="form_instituicao.cnpj" type="text" v-mask="'##.###.###/####-##'"
                :disabled="visualizar || form_instituicao.visualizar"></b-form-input>
            </b-form-group>
          </b-col>
        </b-row>
        <b-row>
          <b-col md="5" sm="12">
            <b-form-group tabindex="0" label="Esfera federativa: *">
              <b-form-select :options="esferaOptions" v-model="form_instituicao.esfera"
                :disabled="visualizar || form_instituicao.visualizar">
                <option selected :value="null">Selecione</option>
              </b-form-select>
            </b-form-group>
          </b-col>
        </b-row>
        <b-row>
          <b-col cols="12">
            <strong>Endereço:</strong>
          </b-col>
        </b-row>
        <b-row>
          <b-col cols="3">
            <b-form-group tabindex="0" label="CEP: *">
              <b-form-input v-model="form_instituicao.cep" type="text" v-mask="'#####-###'"
                :disabled="visualizar || form_instituicao.visualizar"></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="6">
            <b-form-group tabindex="0" label="Endereço: *">
              <b-form-input v-model="form_instituicao.endereco" type="text"
                :disabled="visualizar || form_instituicao.visualizar"></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="3">
            <b-form-group tabindex="0" label="Número: *">
              <b-form-input v-model="form_instituicao.numero" type="text"
                :disabled="visualizar || form_instituicao.visualizar"></b-form-input>
            </b-form-group>
          </b-col>
        </b-row>
        <b-row>
          <b-col cols="4">
            <b-form-group tabindex="0" label="Bairro: *">
              <b-form-input v-model="form_instituicao.bairro" type="text"
                :disabled="visualizar || form_instituicao.visualizar"></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="8">
            <b-form-group tabindex="0" label="Complemento:">
              <b-form-input v-model="form_instituicao.complemento" type="text"
                :disabled="visualizar || form_instituicao.visualizar"></b-form-input>
            </b-form-group>
          </b-col>
        </b-row>
        <b-row>
          <b-col cols="4">
            <b-form-group tabindex="0" label="UF: *">
              <b-form-select v-model="form_instituicao.estado_id" class="mb-3"
                :disabled="visualizar || form_instituicao.visualizar">
                <option selected :value="''"> Selecione </option>
                <option v-for="estado in estados" :key="estado.id" :value="estado.id"> {{ estado.estado }}</option>
              </b-form-select>
            </b-form-group>
          </b-col>
          <b-col cols="4">
            <b-form-group tabindex="0" label="Cidade: *">
              <b-form-select v-model="form_instituicao.cidade_id" class="mb-3"
                :disabled="visualizar || form_instituicao.visualizar">
                <option selected :value="''"> Selecione </option>
                <option v-for="cidade in cidades" :value="cidade.id" :key="cidade.id"> {{ cidade.cidade }} </option>
              </b-form-select>
            </b-form-group>
          </b-col>
        </b-row>
        <hr>
        <b-row>
          <b-col cols="4" v-if="!visualizar && !form_instituicao.visualizar">
            <b-button block variant="primary" :disabled="disable_buttons" @click="salvarInstituicao">
              <em class="fa fa-plus"></em>&nbsp;
              <span>Salvar</span>
            </b-button>
          </b-col>
          <b-col cols="4">
            <b-button block variant="secondary" :disabled="disable_buttons" @click="limparModalInstituicao">
              <em class="fa fa-close"></em>&nbsp;
              <span>Fechar</span>
            </b-button>
          </b-col>
        </b-row>
      </b-container>
    </b-modal>

    <b-modal size="lg" title="Aviso de privacidade" hide-header ok-only ok-title="Fechar" v-model="modalAviso">
      <b-card-text>
        <h4 class="text-center"><b>AVISO DE PRIVACIDADE</b></h4>

        <p>Este aviso de privacidade tem o objetivo de informar, de maneira objetiva e transparente, a você, titular de
          dados, como o Ministério dos Direitos Humanos e Cidadania – MDHC tratará seus dados pessoais no Programa de
          Equipagem, de Modernização da Infraestrutura e de Apoio ao Funcionamento dos Órgãos, das Entidades e das
          Instâncias Colegiadas Atuantes na Promoção e na Defesa dos Direitos Humanos – EquipaDH+.</p>

        <p><b>Que tipo de dados tratamentos?</b></p>

        <p>Trataremos dados pessoais e dados pessoais sensíveis como: identificação, idade, nacionalidade, escolaridade,
          dados de saúde, sexual e raça.</p>

        <p><b>Por que tratamos esses dados?</b></p>

        <p>A finalidade do tratamento desses dados destina-se ao cumprimento das atividades do EquipaDH+ determinadas no
          Decreto nº 11.919, de 14 de fevereiro de 2024. São as atividades destinadas:</p>

        <p>
        <ol>
          <li>Destinação adequada de equipamentos e recursos;</li>
          <li>Gerar dados estatísticos para direcionamento de recursos para projetos e desenvolvimento de novas
            políticas
            públicas.</li>
        </ol>
        </p>

        <p><b>Seus direitos</b></p>

        <p>Seus principais direitos enquanto titular de dados pessoais neste tratamento são: </p>

        <p>Confirmação da existência de tratamento; Acesso aos dados; Correção de dados incompletos, inexatos ou
          desatualizados; Anonimização, bloqueio ou eliminação de dados desnecessários, excessivos ou tratados em
          desconformidade com a LGPD; Obtenção de informações sobre as entidades públicas ou privadas com as quais a
          SNDPD
          tenha compartilhado seus dados; (Art. 18, LGPD). </p>

        <p><b>Contate o encarregado</b></p>

        <p>Para mais informações contate o Encarregado pelo Tratamento de Dados Pessoais pelo e-mail <a
            href="mailto:dadospessoais@mdh.gov.br">dadospessoais@mdh.gov.br</a></p>
      </b-card-text>
    </b-modal>

  </span>
</template>

<script>
import Vue from 'vue'
import VueSelect from 'vue-select'
import { required, minLength, email, requiredIf } from 'vuelidate/lib/validators'
import { validationMixin } from 'vuelidate'
import miniToastr from 'mini-toastr'
import Multiselect from 'vue-multiselect'
import Datepicker from 'vuejs-datepicker'
import { ptBR } from 'vuejs-datepicker/dist/locale'
import { esferaOptions } from '@/constants/solicitacao'

miniToastr.init()

function validDate (value) { return this.$validDate(value, true) }
function validCPF (value) { return this.$validCPF(value, true) }
function validCNPJ (value) { return this.$validCNPJ(value, true) }

const acceptedTypes = [
  'image/jpeg',
  'image/jpg',
  'image/png',
  'application/pdf',
  'application/msword',
  'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
]

export default {
  name: 'SolicitacaoExterna',
  components: {
    VueSelect,
    Multiselect,
    Datepicker
  },
  mixins: [validationMixin],
  validations: {
    form: {
      cpf: {
        required,
        validCPF
      },
      nome: {
        required
      },
      nome_social: {},
      data_nascimento: {
        required,
        validDate
      },
      sexo_id: {
        required
      },
      sexo_outro: {
        required: requiredIf(function () {
          return this.form.sexo_id === 7
        })
      },
      orientacao_sexual_id: {
        required
      },
      orientacao_sexual_outro: {
        required: requiredIf(function () {
          return this.form.orientacao_sexual_id === 6
        })
      },
      raca_id: {
        required
      },
      raca_outro: {
        required: requiredIf(function () {
          return this.form.raca_id === 9
        })
      },
      escolaridade_id: {
        required
      },
      possui_deficiencia: {
        required
      },
      deficiencia_id: {
        requiredIf: requiredIf(function () {
          return this.form.possui_deficiencia
        })
      },
      tipo_representacao: {
        required
      },
      cargo: {
        required,
        minLength: minLength(4)
      },
      telefone_funcional: {
        required,
        minLength: minLength(18)
      },
      celular: {
        required,
        minLength: minLength(18)
      },
      email_funcional: {
        required,
        email
      },
      documento_rg: {
        required
      },
      documento_cpf: {
        required
      },
      documento_ato_posse: {
        required
      },
      documento_ato_delegacao: {
        requiredIf: requiredIf(function () {
          return this.form.tipo_representacao === 'R'
        })
      },
      autoridade_rg: {
        requiredIf: requiredIf(function () {
          return this.form.tipo_representacao === 'R'
        })
      },
      autoridade_cpf: {
        requiredIf: requiredIf(function () {
          return this.form.tipo_representacao === 'R'
        })
      },
      autoridade_ato_posse: {
        requiredIf: requiredIf(function () {
          return this.form.tipo_representacao === 'R'
        })
      },
      autoridade_ato_delegacao: {

      },
      termo_aceite: {
        required,
        isChecked: function (value) {
          return value === true
        }
      }
    }
  },
  created () {
    this.$store.dispatch('buscarEstados')
    this.$store.dispatch('getSexo')
    this.$store.dispatch('getOrientacaoSexual')
    this.$store.dispatch('getEscolaridades')
    this.$store.dispatch('getDeficiencias')
    this.$store.dispatch('getRacas')
    this.$store.dispatch('getInstituicoes')

    this.$http.get('api/configuracao-geral').then((result) => {
      this.configuracao = result.body.data
    })
  },
  data () {
    return {
      editar: false,
      editarCep: 1,
      loading: false,
      modalAviso: false,
      selectedInstituicao: null,
      exibirOutraInstituicao: false,
      configuracao: null,
      form: {
        id: null,
        cpf: null,
        email: null,
        nome: null,
        nome_social: null,
        data_nascimento: null,
        sexo_id: null,
        sexo_outro: null,
        orientacao_sexual_id: null,
        orientacao_sexual_outro: null,
        raca_id: null,
        raca_outro: null,
        escolaridade_id: null,
        possui_deficiencia: null,
        deficiencia_id: null,
        tipo_representacao: null,
        cargo: null,
        telefone_funcional: null,
        celular: null,
        email_funcional: null,
        documento_rg: null,
        documento_cpf: null,
        documento_ato_posse: null,
        documento_ato_delegacao: null,
        autoridade_rg: null,
        autoridade_cpf: null,
        autoridade_ato_posse: null,
        termo_aceite: false,
        instituicoes: []
      },
      modalInstituicao: false,
      titleModalInstituicao: '',
      formInstValidCep: false,
      form_instituicao: {
        id: null,
        razao_social: null,
        cnpj: null,
        esfera: null,
        email: null,
        telefone: null,
        estado_id: null,
        cidade_id: null,
        cep: null,
        endereco: null,
        bairro: null,
        numero: null,
        complemento: null,
        visualizar: false
      },
      form_instituicao_index: null,
      options: [
        { value: true, text: 'Sim' },
        { value: false, text: 'Não' }
      ],
      options_tipo_atuacao: [
        { value: 'A', text: 'Autoridade Máxima' },
        { value: 'R', text: 'Representante' }
      ],
      visualizar: false,
      disable_buttons: false,
      salvarForm: false,
      ptBR: ptBR
    }
  },
  computed: {
    sexos () {
      return this.$store.state.sexo.lista
    },
    orientacoesSexuais () {
      return this.$store.state.orientacaoSexual.lista
    },
    racas () {
      return this.$store.state.raca.lista
    },
    escolaridades () {
      return this.$store.state.escolaridade.lista
    },
    deficiencias () {
      return this.$store.state.deficiencia.lista
    },
    instituicoes () {
      let instituicoes = [
        {
          id: 'outra',
          cnpj: null,
          razao_social: 'Outra instituição',
          razao_cnpj: 'Outra instituição'
        },
        ...this.$store.state.instituicao.lista
      ]

      if (instituicoes.length) {
        instituicoes = instituicoes.map(instituicao => {
          return {
            id: instituicao.id,
            razao_social: instituicao.razao_social,
            cnpj: instituicao.cnpj,
            razao_cnpj: instituicao.cnpj ? `${instituicao.cnpj} - ${instituicao.razao_social} - ${instituicao.estado.sigla}` : instituicao.razao_social,
            esfera: instituicao.esfera,
            email: instituicao.email,
            telefone: instituicao.telefone,
            estado_id: instituicao.estado_id,
            cidade_id: instituicao.cidade_id,
            cep: instituicao.cep,
            endereco: instituicao.endereco,
            bairro: instituicao.bairro,
            numero: instituicao.numero,
            complemento: instituicao.complemento
          }
        })
      }

      return instituicoes
    },
    instituicoesInativas () {
      return this.$store.state.instituicao.listaInativos
    },
    estados () {
      return this.$store.state.estado.listaEstados
    },
    cidades () {
      return this.$store.state.cidade.listaCidadesPorEstado
    },
    esferaOptions () {
      return esferaOptions
    },
    isValid () {
      return !this.$v.form.$anyError
    }
  },
  watch: {
    'form_instituicao.estado_id': function () {
      this.$store.dispatch('buscarCidadesPorEstado', this.form_instituicao.estado_id)
    },
    'form_instituicao.cep': function () {
      if (this.form_instituicao.cep != null) {
        let cep = this.form_instituicao.cep.replace(/[^\d]+/g, '')
        console.log(cep)
        if (cep.length > 7) {
          this.buscarCep(cep)
        }
      }
    },
    'form.cpf': function () {
      if (this.form.cpf !== null && this.form.cpf.length === 14) {
        if (this.$validCPF(this.form.cpf) === false) {
          this.erro('CPF inválido')
          this.form.cpf = null
        }
      }
    },
    'form_instituicao.cnpj': function () {
      if (this.form_instituicao.cnpj !== null && this.form_instituicao.cnpj.length === 18 && !(this.form_instituicao.visualizar)) {
        if (this.$validCNPJ(this.form_instituicao.cnpj) === false) {
          this.erro('CNPJ inválido')
          this.form_instituicao.cnpj = null
        } else {
          let cnpj = this.form_instituicao.cnpj
          let instituicao = this.instituicoes.find(instituicao => instituicao.cnpj === cnpj)
          if (instituicao) {
            this.erro('Instituição já cadastrada na lista de instituições, procure pelo CNPJ no campo e selecione a existente.')
            this.form_instituicao.cnpj = null
            this.modalInstituicao = false
          }

          let instituicaoInativa = this.instituicoesInativas.find(instituicao => instituicao.cnpj === cnpj)
          if (instituicaoInativa) {
            this.erro('Instituição não está ativa e não podem ser realizadas solicitações na instituição.')
            this.form_instituicao.cnpj = null
            this.modalInstituicao = false
          }
        }
      }
    },
    'form.tipo_representacao': function (val) {
      if (val === 'A') {
        this.form.autoridade_rg = null
        this.form.autoridade_cpf = null
        this.form.autoridade_ato_posse = null
        this.form.autoridade_ato_delegacao = null
      }
    },
    'selectedInstituicao': function (val) {
      if (val && val.id === 'outra') {
        this.form.instituicao_id = null
        this.exibirOutraInstituicao = true
      } else {
        this.form.instituicao_id = val && val.id ? val.id : null
        this.form.instituicoes = []
        this.exibirOutraInstituicao = false
      }
    }
  },
  methods: {
    salvar () {
      let data = {
        id: this.form.id,
        instituicao_id: this.form.instituicao_id,
        cpf: this.form.cpf,
        email: this.form.email_funcional,
        nome: this.form.nome,
        nome_social: this.form.nome_social,
        data_nascimento: this.form.data_nascimento,
        sexo_id: this.form.sexo_id,
        sexo_outro: this.form.sexo_outro,
        orientacao_sexual_id: this.form.orientacao_sexual_id,
        orientacao_sexual_outro: this.form.orientacao_sexual_outro,
        raca_id: this.form.raca_id,
        raca_outro: this.form.raca_outro,
        escolaridade_id: this.form.escolaridade_id,
        possui_deficiencia: this.form.possui_deficiencia,
        deficiencia_id: this.form.deficiencia_id,
        tipo_representacao: this.form.tipo_representacao,
        cargo: this.form.cargo,
        telefone_funcional: this.form.telefone_funcional,
        celular: this.form.celular,
        email_funcional: this.form.email_funcional,
        documento_rg: this.form.documento_rg,
        documento_cpf: this.form.documento_cpf,
        documento_ato_posse: this.form.documento_ato_posse,
        documento_ato_delegacao: this.form.documento_ato_delegacao,
        situacao: this.form.situacao,
        termo_aceite: this.form.termo_aceite,
        autoridade_rg: this.form.autoridade_rg,
        autoridade_cpf: this.form.autoridade_cpf,
        autoridade_ato_posse: this.form.autoridade_ato_posse,
        autoridade_ato_delegacao: this.form.autoridade_ato_delegacao
      }

      if (this.form.instituicoes.length > 0) {
        let instituicao = this.form.instituicoes[0]
        data.ins_razao_social = instituicao.razao_social
        data.ins_cnpj = instituicao.cnpj
        data.ins_esfera = instituicao.esfera
        data.ins_email = instituicao.email
        data.ins_telefone = instituicao.telefone
        data.ins_estado_id = instituicao.estado_id
        data.ins_cidade_id = instituicao.cidade_id
        data.ins_cep = instituicao.cep
        data.ins_endereco = instituicao.endereco
        data.ins_bairro = instituicao.bairro
        data.ins_numero = instituicao.numero
        data.ins_complemento = instituicao.complemento
      }

      this.loading = true
      Vue.http.post('api/solicitacao', data).then(response => {
        if (response.body.success) {
          this.sucesso(response.body.msg)

          let t = this
          setTimeout(function () {
            t.navigate('/login')
          }, 3000)
        } else {
          this.erro(response.body.msg)
          this.loading = false
        }
      }).catch(e => {
        if (e.body && e.body.msg !== undefined) this.erro(e.body.msg); else this.erro('Erro ao salvar solicitação.')

        this.loading = false
        console.log('Servidor está fora! ' + e)
      })
    },
    confirmar () {
      this.$bvModal.msgBoxConfirm('Tem certeza que deseja continuar?', {
        okTitle: 'Confirmar',
        cancelTitle: 'Cancelar'
      }).then(valor => {
        if (valor) {
          this.salvar()
        }
      }).catch(erro => {
        console.log(erro)
      })
    },
    download (arquivo) {
      window.open(process.env.ROOT_API1 + arquivo, '_blank')
    },
    // Upload de documentos
    uploadDocumentoCpf (evento) {
      let arquivo = evento.target.files || evento.dataTransfer.files
      if (!arquivo.length) {
        return false
      }

      if (!this.$validMimeType(arquivo[0].type, acceptedTypes)) {
        this.erro('Extensão do arquivo inválida.')
        this.$refs.docCPF.reset()
        return false
      }

      if (arquivo[0].size > 15728640) {
        this.erro('Tamanho do arquivo maior que 15MB.')
        this.$refs.docCPF.reset()
        return false
      }
      let reader = new FileReader()
      reader.onload = (evento) => {
        this.form.documento_cpf = evento.target.result
      }
      reader.readAsDataURL(arquivo[0])
    },
    uploadDocumentoRg (evento) {
      let arquivo = evento.target.files || evento.dataTransfer.files
      if (!arquivo.length) {
        return false
      }
      if (arquivo[0].size > 15728640) {
        this.erro('Tamanho do arquivo maior que 15MB.')
        this.$refs.docRg.reset()
        return false
      }

      if (!this.$validMimeType(arquivo[0].type, acceptedTypes)) {
        this.erro('Extensão do arquivo inválida.')
        this.$refs.docRg.reset()
        return false
      }

      let reader = new FileReader()
      reader.onload = (evento) => {
        this.form.documento_rg = evento.target.result
      }
      reader.readAsDataURL(arquivo[0])
    },
    uploadDocumentoAtoPosse (evento) {
      let arquivo = evento.target.files || evento.dataTransfer.files
      if (!arquivo.length) {
        return false
      }
      if (arquivo[0].size > 15728640) {
        this.erro('Tamanho do arquivo maior que 15MB.')
        this.$refs.docAtoPosse.reset()
        return false
      }

      if (!this.$validMimeType(arquivo[0].type, acceptedTypes)) {
        this.erro('Extensão do arquivo inválida.')
        this.$refs.docAtoPosse.reset()
        return false
      }

      let reader = new FileReader()
      reader.onload = (evento) => {
        this.form.documento_ato_posse = evento.target.result
      }
      reader.readAsDataURL(arquivo[0])
    },
    uploadDocumentoAtoDelegacao (evento) {
      let arquivo = evento.target.files || evento.dataTransfer.files
      if (!arquivo.length) {
        return false
      }
      if (arquivo[0].size > 15728640) {
        this.erro('Tamanho do arquivo maior que 15MB.')
        this.$refs.docAtoDelegacao.reset()
        return false
      }

      if (!this.$validMimeType(arquivo[0].type, acceptedTypes)) {
        this.erro('Extensão do arquivo inválida.')
        this.$refs.docAtoDelegacao.reset()
        return false
      }

      let reader = new FileReader()
      reader.onload = (evento) => {
        this.form.documento_ato_delegacao = evento.target.result
      }
      reader.readAsDataURL(arquivo[0])
    },
    uploadDocumentoCpfAutoridade (evento) {
      let arquivo = evento.target.files || evento.dataTransfer.files
      if (!arquivo.length) {
        return false
      }

      if (!this.$validMimeType(arquivo[0].type, acceptedTypes)) {
        this.erro('Extensão do arquivo inválida.')
        this.$refs.docCPFAutoridade.reset()
        return false
      }

      if (arquivo[0].size > 15728640) {
        this.erro('Tamanho do arquivo maior que 15MB.')
        this.$refs.docCPFAutoridade.reset()
        return false
      }
      let reader = new FileReader()
      reader.onload = (evento) => {
        this.form.autoridade_cpf = evento.target.result
      }
      reader.readAsDataURL(arquivo[0])
    },
    uploadDocumentoRgAutoridade (evento) {
      let arquivo = evento.target.files || evento.dataTransfer.files
      if (!arquivo.length) {
        return false
      }

      if (arquivo[0].size > 15728640) {
        this.erro('Tamanho do arquivo maior que 15MB.')
        this.$refs.docRgAutoridade.reset()
        return false
      }

      if (!this.$validMimeType(arquivo[0].type, acceptedTypes)) {
        this.erro('Extensão do arquivo inválida.')
        this.$refs.docRgAutoridade.reset()
        return false
      }

      let reader = new FileReader()
      reader.onload = (evento) => {
        this.form.autoridade_rg = evento.target.result
      }
      reader.readAsDataURL(arquivo[0])
    },
    uploadDocumentoAtoPosseAutoridade (evento) {
      let arquivo = evento.target.files || evento.dataTransfer.files
      if (!arquivo.length) {
        return false
      }
      if (arquivo[0].size > 15728640) {
        this.erro('Tamanho do arquivo maior que 15MB.')
        this.$refs.docAtoPosseAutoridade.reset()
        return false
      }

      if (!this.$validMimeType(arquivo[0].type, acceptedTypes)) {
        this.erro('Extensão do arquivo inválida.')
        this.$refs.docAtoPosseAutoridade.reset()
        return false
      }

      let reader = new FileReader()
      reader.onload = (evento) => {
        this.form.autoridade_ato_posse = evento.target.result
      }
      reader.readAsDataURL(arquivo[0])
    },
    uploadDocumentoAtoDelegacaoAutoridade (evento) {
      let arquivo = evento.target.files || evento.dataTransfer.files
      if (!arquivo.length) {
        return false
      }
      if (arquivo[0].size > 15728640) {
        this.erro('Tamanho do arquivo maior que 15MB.')
        this.$refs.docAtoDelegacaoAutoridade.reset()
        return false
      }

      if (!this.$validMimeType(arquivo[0].type, acceptedTypes)) {
        this.erro('Extensão do arquivo inválida.')
        this.$refs.docAtoDelegacaoAutoridade.reset()
        return false
      }

      let reader = new FileReader()
      reader.onload = (evento) => {
        this.form.autoridade_ato_delegacao = evento.target.result
      }
      reader.readAsDataURL(arquivo[0])
    },
    downloadDocumento (origem, id) {
      this.$abrirDocumento(origem, id)
    },
    // Modal de instituição
    abrirModalInstituicao () {
      this.modalInstituicao = true
      this.titleModalInstituicao = 'Incluir Instituição'
    },
    editarInstituicao (index) {
      this.modalInstituicao = true
      this.titleModalInstituicao = 'Editar Instituição'
      this.form_instituicao_index = index
      this.form_instituicao.id = this.form.instituicoes[index].id
      this.form_instituicao.razao_social = this.form.instituicoes[index].razao_social
      this.form_instituicao.cnpj = this.form.instituicoes[index].cnpj
      this.form_instituicao.esfera = this.form.instituicoes[index].esfera
      this.form_instituicao.email = this.form.instituicoes[index].email
      this.form_instituicao.telefone = this.form.instituicoes[index].telefone
      this.form_instituicao.estado_id = this.form.instituicoes[index].estado_id
      this.form_instituicao.cidade_id = this.form.instituicoes[index].cidade_id
      this.form_instituicao.cep = this.form.instituicoes[index].cep
      this.form_instituicao.endereco = this.form.instituicoes[index].endereco
      this.form_instituicao.bairro = this.form.instituicoes[index].bairro
      this.form_instituicao.numero = this.form.instituicoes[index].numero
      this.form_instituicao.complemento = this.form.instituicoes[index].complemento
    },
    visualizarInstituicao (index, dados = null) {
      this.modalInstituicao = true
      this.titleModalInstituicao = 'Visualizar Instituição'

      let inst = null
      if (index !== null) {
        this.form_instituicao_index = index
        inst = this.form.instituicoes[index]
      }

      if (dados) {
        inst = dados
      }

      this.form_instituicao.visualizar = true
      this.form_instituicao.id = inst.id
      this.form_instituicao.razao_social = inst.razao_social
      this.form_instituicao.cnpj = inst.cnpj
      this.form_instituicao.esfera = inst.esfera
      this.form_instituicao.email = inst.email
      this.form_instituicao.telefone = inst.telefone
      this.form_instituicao.estado_id = inst.estado_id
      this.form_instituicao.cidade_id = inst.cidade_id
      this.form_instituicao.cep = inst.cep
      this.form_instituicao.endereco = inst.endereco
      this.form_instituicao.bairro = inst.bairro
      this.form_instituicao.numero = inst.numero
      this.form_instituicao.complemento = inst.complemento
    },
    excluirInstituicao (index) {
      this.form.instituicoes.splice(index, 1)
    },
    salvarInstituicao () {
      if (this.visualizar === true) {
        this.modalInstituicao = false
      } else {
        if (this.form_instituicao_index === null) {
          this.incluirInstituicao()
        } else {
          this.alterarInstituicao()
        }
      }
    },
    validInstituicao () {
      if (this.form_instituicao.razao_social === null || this.form_instituicao.razao_social === '') {
        this.erro('Razão Social é obrigatório.')
        return false
      }

      if (this.form_instituicao.razao_social.length < 5) {
        this.erro('Mínimo de 5 caracteres para a razão social.')
        return false
      }

      if (this.form_instituicao.cnpj === null || this.form_instituicao.cnpj === '') {
        this.erro('CNPJ é obrigatório.')
        return false
      }

      if (!this.$validCNPJ(this.form_instituicao.cnpj)) {
        this.erro('CNPJ inválido.')
        return false
      }

      if (this.form_instituicao.cep === null || this.form_instituicao.cep === '') {
        this.erro('CEP é obrigatório.')
        return false
      }

      // if (this.form_instituicao.cep.length < 9 || !this.formInstValidCep) {
      if (this.form_instituicao.cep.length < 9) {
        this.erro('CEP inválido.')
        return false
      }

      if (this.form_instituicao.endereco === null || this.form_instituicao.endereco === '') {
        this.erro('Endereço é obrigatório.')
        return false
      }

      if (this.form_instituicao.endereco.length < 5) {
        this.erro('Mínimo de 5 caracteres para o endereço.')
        return false
      }

      if (this.form_instituicao.numero === null || this.form_instituicao.numero === '') {
        this.erro('Número é obrigatório.')
        return false
      }

      if (this.form_instituicao.bairro === null || this.form_instituicao.bairro === '') {
        this.erro('Bairro é obrigatório.')
        return false
      }

      if (this.form_instituicao.bairro.length < 3) {
        this.erro('Mínimo de 3 caracteres para o bairro.')
        return false
      }

      if (this.form_instituicao.estado_id === null || this.form_instituicao.estado_id === '') {
        this.erro('Estado é obrigatório.')
        return false
      }

      if (this.form_instituicao.cidade_id === null || this.form_instituicao.cidade_id === '') {
        this.erro('Cidade é obrigatório.')
        return false
      }

      if (this.form_instituicao.esfera === null || this.form_instituicao.esfera === '') {
        this.erro('Esfera federativa é obrigatório.')
        return false
      }

      return true
    },
    incluirInstituicao () {
      if (this.validInstituicao()) {
        this.form.instituicoes.push({
          id: this.form_instituicao.id,
          razao_social: this.form_instituicao.razao_social,
          cnpj: this.form_instituicao.cnpj,
          esfera: this.form_instituicao.esfera,
          email: this.form_instituicao.email,
          telefone: this.form_instituicao.telefone,
          estado_id: this.form_instituicao.estado_id,
          cidade_id: this.form_instituicao.cidade_id,
          cep: this.form_instituicao.cep,
          endereco: this.form_instituicao.endereco,
          bairro: this.form_instituicao.bairro,
          numero: this.form_instituicao.numero,
          complemento: this.form_instituicao.complemento
        })

        setTimeout(() => {
          this.limparModalInstituicao()
        }, 1000)
      }
    },
    alterarInstituicao () {
      if (this.validInstituicao()) {
        this.form.instituicoes[this.form_instituicao_index] = {
          id: this.form_instituicao.id,
          razao_social: this.form_instituicao.razao_social,
          cnpj: this.form_instituicao.cnpj,
          esfera: this.form_instituicao.esfera,
          email: this.form_instituicao.email,
          telefone: this.form_instituicao.telefone,
          estado_id: this.form_instituicao.estado_id,
          cidade_id: this.form_instituicao.cidade_id,
          cep: this.form_instituicao.cep,
          endereco: this.form_instituicao.endereco,
          bairro: this.form_instituicao.bairro,
          numero: this.form_instituicao.numero,
          complemento: this.form_instituicao.complemento
        }
        this.limparModalInstituicao()
      }
    },
    limparModalInstituicao () {
      this.form_instituicao_index = null
      this.form_instituicao.id = null
      this.form_instituicao.razao_social = null
      this.form_instituicao.cnpj = null
      this.form_instituicao.esfera = null
      this.form_instituicao.email = null
      this.form_instituicao.telefone = null
      this.form_instituicao.estado_id = null
      this.form_instituicao.cidade_id = null
      this.form_instituicao.cep = null
      this.form_instituicao.endereco = null
      this.form_instituicao.bairro = null
      this.form_instituicao.numero = null
      this.form_instituicao.complemento = null
      this.form_instituicao.visualizar = false

      this.modalInstituicao = false
    },
    onSubmit () {
      this.salvarForm = true
      if (this.validate()) {
        this.confirmar()
      }
    },
    chkState (val) {
      const field = this.$v.form[val]
      if (!field.$model && !this.salvarForm) {
        return null
      }
      return !field.$dirty || !field.$invalid
    },
    findFirstError (component = this) {
      if (component.state === false) {
        if (component.$refs.input) {
          component.$refs.input.focus()
          return true
        }
        if (component.$refs.check) {
          component.$refs.check.focus()
          return true
        }
      }
      let focused = false
      component.$children.some((child) => {
        focused = this.findFirstError(child)
        return focused
      })
      return focused
    },
    validate () {
      this.$v.$touch()
      this.$nextTick(() => this.findFirstError())

      if (!this.form.instituicao_id && !this.form.instituicoes.length) {
        this.erro('Instituição é obrigatória.')
        return false
      }

      return this.isValid
    },
    navigate (route) {
      this.$router.push(route)
    },
    voltar () {
      if (!this.visualizar) {
        this.$bvModal.msgBoxConfirm('Deseja retornar para tela de pesquisa? É possível que dados informados não sejam salvos.', {
          okTitle: 'Confirmar',
          cancelTitle: 'Cancelar'
        }).then(valor => {
          if (valor) {
            this.$router.go(-1)
          }
        })
      } else {
        this.$router.go(-1)
      }
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
    buscarCep (cep) {
      if (cep.length < 8) {
        return
      }
      this.$viaCep.buscarCep(cep).then((obj) => {
        if (obj.erro === true) {
          this.formInstValidCep = false
          return this.erro('O CEP digitado é inválido, digite os dados do endereço!')
        }

        this.form_instituicao.bairro = obj.bairro
        this.form_instituicao.endereco = obj.logradouro
        this.form_instituicao.complemento = obj.complemento

        for (let index in this.estados) {
          let estado = this.estados[index]
          if (estado.sigla === obj.uf) {
            this.form_instituicao.estado_id = estado.id
          }
        }

        Vue.http.get('api/cidade/' + this.form_instituicao.estado_id).then(response => {
          let cidades = response.data.data
          for (let index in cidades) {
            let cidade = cidades[index]
            if (cidade.cidade === obj.localidade) {
              this.form_instituicao.cidade_id = cidade.id
            }
          }
        })

        this.formInstValidCep = true
      })
    }
  }
}
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
