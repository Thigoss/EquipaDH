<template>
  <span>
    <b-col md="12" sm="12">
      <b-card no-body>
        <b-card-body>
          <b-form v-on:submit.prevent="onSubmit">
            <h1 tabindex="0" v-if="!form.id"><strong>Cadastrar Instituição</strong></h1>
            <h1 tabindex="0" v-if="form.id && !visualizar"><strong>Editar Instituição</strong></h1>
            <h1 tabindex="0" v-if="visualizar"><strong>Visualizar Instituição</strong></h1>
            <b-row>
              <b-col cols="12">
                <strong>Dados pessoais</strong>
                <br />
              </b-col>
            </b-row>
            <br />

            <b-row>
              <b-col md="8" sm="12">
                <b-form-group tabindex="0" label="Razão Social: *">
                  <b-input-group class="mb-3">
                    <b-form-input type="text" class="form-control" placeholder="" v-model.trim="form.razao_social"
                      :state="chkState('razao_social')" :disabled="visualizar" />
                    <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col>
              <b-col md="4" sm="12">
                <b-form-group tabindex="0" label="CNPJ: *">
                  <b-input-group class="mb-3">
                    <b-form-input type="text" class="form-control" placeholder="" v-model.trim="form.cnpj"
                      v-mask="'##.###.###/####-##'" :state="chkState('cnpj')" :disabled="visualizar" />
                    <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col>
            </b-row>
            <b-row>
              <b-col md="5" sm="12">
                <b-form-group tabindex="0" label="Esfera federativa: *">
                  <b-form-select :options="esferaOptions" v-model="form.esfera" :state="chkState('esfera')"
                    :disabled="visualizar">
                    <option selected :value="null">Selecione</option>
                  </b-form-select>
                  <b-form-invalid-feedback :state="chkState('esfera')">Campo obrigatório!</b-form-invalid-feedback>
                </b-form-group>
              </b-col>
            </b-row>
            <b-row>
              <b-col cols="3">
                <b-form-group tabindex="0" label="CEP: *">
                  <b-form-input v-model="form.cep" type="text" v-mask="'#####-###'" :state="chkState('cep')"
                    :disabled="visualizar"></b-form-input>
                  <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
                </b-form-group>
              </b-col>
              <b-col cols="6">
                <b-form-group tabindex="0" label="Endereço: *">
                  <b-form-input v-model="form.endereco" type="text" :state="chkState('endereco')"
                    :disabled="visualizar"></b-form-input>
                  <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
                </b-form-group>
              </b-col>
              <b-col cols="3">
                <b-form-group tabindex="0" label="Número: *">
                  <b-form-input v-model="form.numero" type="text" :state="chkState('numero')"
                    :disabled="visualizar"></b-form-input>
                  <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
                </b-form-group>
              </b-col>
            </b-row>
            <b-row>
              <b-col cols="4">
                <b-form-group tabindex="0" label="Bairro: *">
                  <b-form-input v-model="form.bairro" type="text" :state="chkState('bairro')"
                    :disabled="visualizar"></b-form-input>
                  <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
                </b-form-group>
              </b-col>
              <b-col cols="8">
                <b-form-group tabindex="0" label="Complemento:">
                  <b-form-input v-model="form.complemento" type="text" :state="chkState('complemento')"
                    :disabled="visualizar"></b-form-input>
                  <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
                </b-form-group>
              </b-col>
            </b-row>
            <b-row>
              <b-col cols="4">
                <b-form-group tabindex="0" label="UF: *">
                  <b-form-select v-model="form.estado_id" :state="chkState('estado_id')" :disabled="visualizar">
                    <option selected :value="''"> Selecione </option>
                    <option v-for="estado in estados" :key="estado.id" :value="estado.id"> {{ estado.estado }}</option>
                  </b-form-select>
                  <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
                </b-form-group>
              </b-col>
              <b-col cols="4">
                <b-form-group tabindex="0" label="Cidade: *">
                  <b-form-select v-model="form.cidade_id" :state="chkState('cidade_id')" :disabled="visualizar">
                    <option selected :value="''"> Selecione </option>
                    <option v-for="cidade in cidades" :value="cidade.id" :key="cidade.id"> {{ cidade.cidade }} </option>
                  </b-form-select>
                  <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
                </b-form-group>
              </b-col>
            </b-row>
            <hr>

            <b-row>
              <b-col cols="8">
                <strong>Documentação da Autoridade Máxima</strong>
                <br />
              </b-col>
            </b-row>
            <br>

            <b-row>
              <b-col md="8" sm="12">
                <label tabindex="0">RG/CNH: </label>
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
                  @click="downloadDocumento('instituicao_auto_rg', form.id)" variant="primary" size="sm">
                  <i class="fa fa-download"></i>
                </b-button>
                <span v-if="!form.autoridade_rg && visualizar">
                  Nenhum arquivo enviado.
                </span>
              </b-col>

              <b-col md="8" sm="12">
                <label tabindex="0">CPF: </label>
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
                  @click="downloadDocumento('instituicao_auto_cpf', form.id)" variant="primary" size="sm">
                  <i class="fa fa-download"></i>
                </b-button>
                <span v-if="!form.autoridade_cpf && visualizar">
                  Nenhum arquivo enviado.
                </span>
              </b-col>

              <b-col lg="8" md="10" sm="12">
                <label tabindex="0">Ato ou declaração de investidura em cargo ou função: </label>
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
                  @click="downloadDocumento('instituicao_auto_ato_posse', form.id)" variant="primary" size="sm">
                  <i class="fa fa-download"></i>
                </b-button>
                <span v-if="!form.autoridade_ato_posse && visualizar">
                  Nenhum arquivo enviado.
                </span>
              </b-col>

              <b-col lg="8" md="10" sm="12">
                <label tabindex="0">Ato de delegação de competência: </label>
                <span class="icon-info" v-b-popover.hover.top="'PDF, DOC, DOCX, JPG, JPEG e PNG'"
                  title="Extensões aceitas" v-if="!visualizar"></span>
                <b-form-group label-for="nome-input" v-if="!visualizar">
                  <b-input-group>
                    <b-form-file ref="docAtoDelegacaoAutoridade" class="fileInput"
                      accept=".png, .jpeg, .jpg, .pdf, .docx, .doc" v-on:change="uploadDocumentoAtoDelegacaoAutoridade"
                      v-model="form.autoridade_ato_delegacao" browse-text="Selecione" placeholder="Upload"
                      :state="chkState('autoridade_ato_delegacao')"></b-form-file>
                  </b-input-group>
                  <b-form-invalid-feedback :state="chkState('autoridade_ato_delegacao')">Campo
                    obrigatório.</b-form-invalid-feedback>
                </b-form-group>
                <b-button v-if="form.autoridade_ato_delegacao && form.id"
                  @click="downloadDocumento('instituicao_auto_ato_delegacao', form.id)" variant="primary" size="sm">
                  <i class="fa fa-download"></i>
                </b-button>
                <span v-if="!form.autoridade_ato_delegacao && visualizar">
                  Nenhum arquivo enviado.
                </span>
              </b-col>
            </b-row>

            <hr>

            <b-row>
              <b-col cols="8">
                <strong>Usuários da Instituição</strong>
                <br />
              </b-col>
            </b-row>
            <br>

            <!-- <b-row>
              <b-col md="4" sm="12" v-if="!visualizar">
                <b-button block variant="primary" :disabled="visualizar" @click="abrirModalResponsavel">
                  <em class="fa fa-plus"></em>&nbsp;
                  <span>Incluir</span>
                </b-button>
              </b-col>
            </b-row>
            <br/> -->

            <b-row>
              <b-col cols="12">
                <table aria-rowcount="4" id="my-table" aria-busy="false" aria-colcount="7"
                  class="table table-responsive-sm table-bordered table-striped table-sm">
                  <thead>
                    <tr>
                      <th tabindex="0" scope="col">Tipo de responsável</th>
                      <th tabindex="0" scope="col">Nome do responsável</th>
                      <th tabindex="0" scope="col">Telefones</th>
                      <th tabindex="0" scope="col">E-mail</th>
                      <!-- <th tabindex="0" scope="col">Ações</th> -->
                    </tr>
                  </thead>
                  <tbody v-if="form.users.length">
                    <tr class="action" v-for="(item) in form.users" :key="item.cnpj">
                      <td tabindex="0">{{ item.tipo_representacao == 'A' ? 'Autoridade máxima' : 'Representante' }}</td>
                      <td tabindex="0">{{ item.nome }}</td>
                      <td tabindex="0">
                        <b-badge pill variant="info">
                          Funcional
                        </b-badge>
                        {{ item.telefone_funcional }}
                        <br>
                        <b-badge pill variant="info">
                          Celular
                        </b-badge>
                        {{ item.celular }}
                      </td>
                      <td tabindex="0">
                        {{ item.email }}
                      </td>
                      <!-- <td style="width: 1%; white-space: nowrap;">
                        <a v-if="!visualizar" class="btn btn-sm btn-secondary action" title="Excluir" @click="excluirResponsavel(index)">
                          <i class="fa fa-trash"></i>
                        </a>
                      </td> -->
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr class="action">
                      <td tabindex="0" colspan="5">Nenhum responsável pela instituição cadastrado.</td>
                    </tr>
                  </tbody>
                </table>
              </b-col>
            </b-row>

            <!-- <b-row>
              <b-col cols="12">
                <table aria-rowcount="4" id="my-table" aria-busy="false" aria-colcount="7" class="table table-responsive-sm table-bordered table-striped table-sm">
                  <thead>
                    <tr>
                      <th tabindex="0" scope="col">Tipo de responsável</th>
                      <th tabindex="0" scope="col">Nome do responsável</th>
                      <th tabindex="0" scope="col">Telefone</th>
                      <th tabindex="0" scope="col">E-mail</th>
                      <th tabindex="0" scope="col">Ações</th>
                    </tr>
                  </thead>
                  <tbody v-if="form.responsaveis.length">
                    <tr class="action" v-for="(item, index) in form.responsaveis" :key="item.cnpj">
                      <td tabindex="0">{{ item.tipo == 1 ? 'Autoridade máxima' : 'Representante' }}</td>
                      <td tabindex="0">{{ item.nome }}</td>
                      <td tabindex="0">{{ item.telefone }}</td>
                      <td tabindex="0">{{ item.email }}</td>
                      <td style="width: 1%; white-space: nowrap;">
                        <a v-if="!visualizar" class="btn btn-sm btn-secondary action" title="Editar" @click="editarResponsavel(index)">
                          <i class="fa fa-pencil"></i>
                        </a>
                        <a v-if="!visualizar" class="btn btn-sm btn-secondary action" title="Excluir" @click="excluirResponsavel(index)">
                          <i class="fa fa-trash"></i>
                        </a>
                        <a v-if="visualizar" class="btn btn-sm btn-secondary action" title="Visualizar" @click="visualizarResponsavel(index)">
                          <i class="fa fa-search"></i>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr class="action">
                      <td tabindex="0" colspan="5">Nenhum responsável pela instituição cadastrado.</td>
                    </tr>
                  </tbody>
                </table>
              </b-col>
            </b-row> -->
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

    <b-modal @cancel="limparModalResponsavel" :hide-header-close="true" no-close-on-backdrop no-close-on-esc
      :hide-footer="true" cancel-title="Voltar" :ok-title="!visualizar ? 'Salvar' : 'Fechar'" centered
      v-model="modalResponsavel" size="lg" :title="titleModalResponsavel">
      <b-container fluid>
        <b-row>
          <b-col cols="6">
            <b-form-group tabindex="0" label="Tipo de responsável: *">
              <b-form-select v-model="form_responsavel.tipo" class="mb-3" :disabled="visualizar">
                <option selected :value="1">Autoridade máxima</option>
                <option :value="2">Representante</option>
              </b-form-select>
            </b-form-group>
          </b-col>
        </b-row>
        <b-row>
          <b-col cols="6">
            <b-form-group tabindex="0" label="Nome do responsável: *">
              <b-form-input v-model="form_responsavel.nome" type="text" :disabled="visualizar"></b-form-input>
            </b-form-group>
          </b-col>
        </b-row>
        <b-row>
          <b-col cols="6">
            <b-form-group tabindex="0" label="Telefone: *">
              <b-form-input v-model="form_responsavel.telefone" type="text"
                v-mask="['+55 (##) ####-####', '+55 (##) #####-####']" :disabled="visualizar"></b-form-input>
            </b-form-group>
          </b-col>
        </b-row>
        <b-row>
          <b-col cols="6">
            <b-form-group tabindex="0" label="E-mail: *">
              <b-form-input v-model="form_responsavel.email" type="text" :disabled="visualizar"></b-form-input>
            </b-form-group>
          </b-col>
        </b-row>
        <hr>
        <b-row>
          <b-col cols="4" v-if="!visualizar">
            <b-button block variant="primary" :disabled="disable_buttons" @click="salvarResponsavel">
              <em class="fa fa-plus"></em>&nbsp;
              <span>Salvar</span>
            </b-button>
          </b-col>
          <b-col cols="4">
            <b-button block variant="secondary" :disabled="disable_buttons" @click="limparModalResponsavel">
              <em class="fa fa-close"></em>&nbsp;
              <span>Fechar</span>
            </b-button>
          </b-col>
        </b-row>
      </b-container>
    </b-modal>
  </span>
</template>

<script>
import Vue from 'vue'
import VueSelect from 'vue-select'
import { validationMixin } from 'vuelidate'
import { required, minLength } from 'vuelidate/lib/validators'
import Multiselect from 'vue-multiselect'
import Datepicker from 'vuejs-datepicker'
import { ptBR } from 'vuejs-datepicker/dist/locale'
import { esferaOptions } from '@/constants/solicitacao'

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
  name: 'InstituicaoForm',
  components: {
    VueSelect,
    Multiselect,
    Datepicker
  },
  mixins: [validationMixin],
  validations: {
    form: {
      razao_social: {
        required,
        minLength: minLength(3)
      },
      cnpj: {
        required,
        validCNPJ: validCNPJ
      },
      esfera: {
        required
      },
      estado_id: {
        required
      },
      cidade_id: {
        required
      },
      cep: {
        required,
        validCep: function (value) {
          return this.validCep
        }
      },
      endereco: {
        required,
        minLength: minLength(5)
      },
      bairro: {
        required,
        minLength: minLength(3)
      },
      numero: {
        required
      },
      complemento: {},
      autoridade_rg: {},
      autoridade_cpf: {},
      autoridade_ato_posse: {},
      autoridade_ato_delegacao: {}
    }
  },
  created () {
    this.$store.dispatch('buscarEstados')
  },
  data () {
    return {
      editar: false,
      editarCep: 1,
      loading: false,
      validCep: false,
      form: {
        id: null,
        razao_social: null,
        cnpj: null,
        estado_id: null,
        cidade_id: null,
        cep: null,
        esfera: null,
        endereco: null,
        bairro: null,
        numero: null,
        complemento: null,
        autoridade_rg: null,
        autoridade_cpf: null,
        autoridade_ato_posse: null,
        autoridade_ato_delegacao: null,
        responsaveis: [],
        users: []
      },
      modalResponsavel: false,
      titleModalResponsavel: '',
      form_responsavel: {
        id: null,
        tipo: null,
        nome: null,
        telefone: null,
        email: null
      },
      form_responsavel_index: null,
      visualizar: false,
      disable_buttons: false,
      salvarForm: false,
      ptBR: ptBR
    }
  },
  computed: {
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
    'form.estado_id': function () {
      this.$store.dispatch('buscarCidadesPorEstado', this.form.estado_id)
    },
    'form.cep': function () {
      if (this.form.cep != null) {
        let cep = this.form.cep.replace(/[^\d]+/g, '')
        if (cep.length > 7) {
          if (!this.editar && !this.visualizar) {
            this.buscarCep(cep)
          } else if (this.editarCep > 2) {
            this.buscarCep(cep)
          }
        }
        this.editarCep++
      }
    }
  },
  mounted () {
    if (this.$route.params.id !== undefined && this.$route.params.visualizar !== undefined) {
      this.visualizar = true
      this.form.id = this.$route.params.id
      this.load(this.$route.params.id)
    }

    if (this.$route.params.id !== undefined && this.$route.params.visualizar === undefined) {
      this.editar = true
      this.form.id = this.$route.params.id
      this.load(this.$route.params.id)
    }
  },
  methods: {
    can (permissao) {
      return this.$store.getters.validaPermissao(permissao)
    },
    downloadDocumento (origem, id) {
      this.$abrirDocumento(origem, id)
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
    load (id) {
      Vue.http.get('api/instituicao/' + id).then(response => {
        let data = response.body.data

        this.form.id = data.id
        this.form.razao_social = data.razao_social
        this.form.cnpj = data.cnpj
        this.form.estado_id = data.estado_id
        this.form.cidade_id = data.cidade_id
        this.form.cep = data.cep
        this.form.esfera = data.esfera
        this.validCep = true
        this.form.endereco = data.endereco
        this.form.bairro = data.bairro
        this.form.numero = data.numero
        this.form.complemento = data.complemento

        this.form.autoridade_rg = data.autoridade_rg
        this.form.autoridade_cpf = data.autoridade_cpf
        this.form.autoridade_ato_posse = data.autoridade_ato_posse
        this.form.autoridade_ato_delegacao = data.autoridade_ato_delegacao

        this.form.users = data.users

        for (let x in data.representantes) {
          let responsavel = data.representantes[x]
          this.form.responsaveis.push({
            id: responsavel.id,
            tipo: responsavel.tipo,
            nome: responsavel.nome,
            telefone: responsavel.telefone,
            email: responsavel.email
          })
        }
      })
    },
    salvar () {
      let data = {
        id: this.form.id,
        razao_social: this.form.razao_social,
        cnpj: this.form.cnpj,
        estado_id: this.form.estado_id,
        cidade_id: this.form.cidade_id,
        cep: this.form.cep,
        esfera: this.form.esfera,
        endereco: this.form.endereco,
        bairro: this.form.bairro,
        numero: this.form.numero,
        complemento: this.form.complemento,
        responsaveis: JSON.stringify(this.form.responsaveis),
        autoridade_rg: this.form.autoridade_rg,
        autoridade_cpf: this.form.autoridade_cpf,
        autoridade_ato_posse: this.form.autoridade_ato_posse,
        autoridade_ato_delegacao: this.form.autoridade_ato_delegacao
      }

      this.loading = true
      if (data.id) {
        Vue.http.put('api/instituicao/' + data.id, data).then(response => {
          if (response.body.success) {
            this.sucesso(response.body.msg)
            this.loading = false
            let t = this
            setTimeout(function () {
              t.navigate('/instituicoes')
            }, 3000)
          } else {
            this.erro(response.body.msg)
            this.loading = false
          }
        }).catch(e => {
          console.log('Servidor está fora! ' + e)
        })
      } else {
        Vue.http.post('api/instituicao', data).then(response => {
          if (response.body.success) {
            this.sucesso(response.body.msg)

            let t = this
            setTimeout(function () {
              t.navigate('/instituicoes')
            }, 3000)
          } else {
            this.erro(response.body.msg)
            this.loading = false
          }
        }).catch(e => {
          console.log('Servidor está fora! ' + e)
        })
      }
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
    // Modal de responsável
    abrirModalResponsavel () {
      this.modalResponsavel = true
      this.titleModalResponsavel = 'Incluir Responsável'
    },
    editarResponsavel (index) {
      this.modalResponsavel = true
      this.titleModalResponsavel = 'Editar Responsável'
      this.form_responsavel_index = index
      this.form_responsavel.id = this.form.responsaveis[index].id
      this.form_responsavel.tipo = this.form.responsaveis[index].tipo
      this.form_responsavel.nome = this.form.responsaveis[index].nome
      this.form_responsavel.telefone = this.form.responsaveis[index].telefone
      this.form_responsavel.email = this.form.responsaveis[index].email
    },
    visualizarResponsavel (index) {
      this.modalResponsavel = true
      this.titleModalResponsavel = 'Visualizar Responsável'
      this.form_responsavel_index = index
      this.form_responsavel.id = this.form.responsaveis[index].id
      this.form_responsavel.tipo = this.form.responsaveis[index].tipo
      this.form_responsavel.nome = this.form.responsaveis[index].nome
      this.form_responsavel.telefone = this.form.responsaveis[index].telefone
      this.form_responsavel.email = this.form.responsaveis[index].email
    },
    excluirResponsavel (index) {
      this.form.responsaveis.splice(index, 1)
    },
    salvarResponsavel () {
      if (this.visualizar) {
        this.modalResponsavel = false
      } else {
        if (!this.form_responsavel_index) {
          this.incluirResponsavel()
        } else {
          this.alterarResponsavel()
        }
      }
    },
    validResponsavel () {
      if (!this.form_responsavel.nome) {
        this.erro('Nome é obrigatório.')
        return false
      }

      if (!this.form_responsavel.telefone) {
        this.erro('Telefone é obrigatório.')
        return false
      }

      if (!(this.form_responsavel.telefone.length >= 18)) {
        this.erro('Telefone inválido.')
        return false
      }

      if (!this.form_responsavel.email) {
        this.erro('E-mail é obrigatório.')
        return false
      }

      if (!this.form_responsavel.email.match(/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/)) {
        this.erro('E-mail inválido.')
        return false
      }

      return true
    },
    incluirResponsavel () {
      if (this.validResponsavel()) {
        this.form.responsaveis.push({
          id: this.form_responsavel.id,
          tipo: this.form_responsavel.tipo,
          nome: this.form_responsavel.nome,
          telefone: this.form_responsavel.telefone,
          email: this.form_responsavel.email
        })
        this.limparModalResponsavel()
      }
    },
    alterarResponsavel () {
      if (this.validResponsavel()) {
        this.form.responsaveis[this.form_responsavel_index] = {
          id: this.form_responsavel.id,
          tipo: this.form_responsavel.tipo,
          nome: this.form_responsavel.nome,
          email: this.form_responsavel.email,
          telefone: this.form_responsavel.telefone
        }
        this.limparModalResponsavel()
      }
    },
    limparModalResponsavel () {
      this.form_responsavel_index = null
      this.form_responsavel.id = null
      this.form_responsavel.tipo = null
      this.form_responsavel.nome = null
      this.form_responsavel.email = null
      this.form_responsavel.telefone = null
      this.modalResponsavel = false
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
      this.$viaCep.buscarCep(cep).then((obj) => {
        if (obj.erro === true) {
          this.validCep = false
          return this.erro('O CEP digitado é inválido!')
        } else {
          this.validCep = true
        }

        this.form.bairro = obj.bairro
        this.form.endereco = obj.logradouro
        this.form.complemento = obj.complemento

        for (let index in this.estados) {
          let estado = this.estados[index]
          if (estado.sigla === obj.uf) {
            this.form.estado_id = estado.id
          }
        }

        Vue.http.get('api/cidade/' + this.form.estado_id).then(response => {
          let cidades = response.data.data
          for (let index in cidades) {
            let cidade = cidades[index]
            if (Number(cidade.codigo_ibge) === Number(obj.ibge)) {
              this.form.cidade_id = cidade.id
            }
          }
        })
      })
    }
  }
}
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
