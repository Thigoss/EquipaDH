<template>
  <b-card no-body>
    <b-card-body>
      <b-form v-on:submit.prevent="onSubmit">
        <h1 tabindex="0"><strong>{{ $route.name }}</strong></h1>
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
                  :disabled="true" />
                <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
              </b-input-group>
            </b-form-group>
          </b-col>
          <b-col md="6" sm="12">
            <b-form-group tabindex="0" label="Nome social">
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
                <b-form-input ref="cpfInput" v-mask="'###.###.###-##'" type="text" class="form-control"
                  v-model="form.cpf" :disabled="cpfDisabled" />
              </b-input-group>
            </b-form-group>
          </b-col>
          <b-col md="6" sm="12">
            <b-form-group label="Data de Nascimento: *">
              <b-input-group>
                <b-form-input id="type-date" :disabled="visualizar" :state="chkState('data_nascimento')" type="date"
                  v-model.lazy.trim="$v.form.data_nascimento.$model">
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
          <b-col md="3" sm="12">
            <b-form-group tabindex="0" label="Identidade de gênero: *">
              <b-form-select v-model="form.sexo_id" placeholder="Selecione" :state="chkState('sexo_id')"
                :disabled="visualizar">
                <option selected :value="null">Selecione</option>
                <option v-for="item in sexos" :key="item.id" :value="item.id">{{ item.nome }}</option>
              </b-form-select>
            </b-form-group>
          </b-col>
          <b-col md="3" sm="12" v-if="form.sexo_id === 7">
            <b-form-group tabindex="0" label="Identidade de gênero(outro): *">
              <b-input-group class="mb-3">
                <b-form-input type="text" class="form-control" placeholder="" v-model.trim="form.sexo_outro"
                  :state="chkState('sexo_outro')" :disabled="visualizar" />
                <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
              </b-input-group>
            </b-form-group>
          </b-col>
          <b-col md="3" sm="12">
            <b-form-group tabindex="0" label="Orientação sexual: *">
              <b-form-select v-model="form.orientacao_sexual_id" placeholder="Selecione"
                :state="chkState('orientacao_sexual_id')" :disabled="visualizar">
                <option selected :value="null">Selecione</option>
                <option v-for="item in orientacoesSexuais" :key="item.id" :value="item.id">{{ item.nome }}</option>
              </b-form-select>
            </b-form-group>
          </b-col>
          <b-col md="3" sm="12" v-if="form.orientacao_sexual_id === 6">
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
            </b-form-group>
          </b-col>
          <b-col md="3" sm="12">
            <b-form-group tabindex="0" label="Escolaridade: *">
              <b-form-select v-model="form.escolaridade_id" placeholder="Selecione" :state="chkState('escolaridade_id')"
                :disabled="visualizar">
                <option selected :value="null">Selecione</option>
                <option v-for="item in escolaridades" :key="item.id" :value="item.id">{{ item.nome }}</option>
              </b-form-select>
            </b-form-group>
          </b-col>
          <b-col md="3" sm="12">
            <b-form-group tabindex="0" label="Possui deficiência: *">
              <b-form-select v-model="form.possui_deficiencia" placeholder="Selecione"
                :state="chkState('possui_deficiencia')" :disabled="visualizar">
                <option selected :value="null">Selecione</option>
                <option v-for="item in options" :key="item.value" :value="item.value">{{ item.text }}</option>
              </b-form-select>
            </b-form-group>
          </b-col>
          <b-col md="3" sm="12" v-if="form.possui_deficiencia">
            <b-form-group tabindex="0" label="Qual: *">
              <b-input-group class="mb-3">
                <b-form-select v-model="form.deficiencia_id" placeholder="Selecione" :state="chkState('deficiencia_id')"
                  :disabled="visualizar">
                  <option selected :value="null">Selecione</option>
                  <option v-for="item in deficiencias" :key="item.id" :value="item.id">{{ item.nome }}</option>
                </b-form-select>
              </b-input-group>
            </b-form-group>
          </b-col>
        </b-row>

        <!-- <b-row>
          <b-col cols="12">
              <strong>Meus dados funcionais</strong>
              <br/>
          </b-col>
        </b-row>
        <br/>
        <b-row>
          <b-col md="3" sm="12">
            <b-form-group tabindex="0" label="Tipo de atuação: *">
              <b-form-select v-model="form.tipo_representacao" placeholder="Selecione" :disabled="true">
                <option selected :value="null">Selecione</option>
                <option v-for="item in options_tipo_atuacao" :key="item.value" :value="item.value">{{item.text}}</option>
              </b-form-select>
            </b-form-group>
          </b-col>
        </b-row>
        <b-row>
          <b-col md="3" sm="12">
            <b-form-group tabindex="0" label="Cargo / Função: *">
              <b-input-group class="mb-3">
                <b-form-input type="text" class="form-control" v-model.trim="form.cargo" :disabled="true" />
                <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
              </b-input-group>
            </b-form-group>
          </b-col>
          <b-col md="3" sm="12">
            <b-form-group tabindex="0" label="Telefone funcional: *">
              <b-input-group class="mb-3">
                <b-form-input type="text" class="form-control" v-mask="['+55 (##) ####-####', '+55 (##) #####-####']" v-model.trim="$v.form.telefone_funcional.$model" :state="chkState('telefone_funcional')" :disabled="visualizar" />
                <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
              </b-input-group>
            </b-form-group>
          </b-col>
          <b-col md="3" sm="12">
            <b-form-group tabindex="0" label="Celular: *">
              <b-input-group class="mb-3">
                <b-form-input type="text" class="form-control" v-mask="['+55 (##) ####-####', '+55 (##) #####-####']" v-model.trim="$v.form.celular.$model" :state="chkState('celular')" :disabled="visualizar" />
                <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
              </b-input-group>
            </b-form-group>
          </b-col>
          <b-col md="3" sm="12">
            <b-form-group tabindex="0" label="E-mail funcional: *">
              <b-input-group class="mb-3">
                <b-form-input type="text" class="form-control" v-model.trim="form.email_funcional" :state="chkState('email_funcional')" :disabled="visualizar" />
                <b-form-invalid-feedback v-if="!$v.form.email_funcional.required">
                  Campo obrigatório!
                </b-form-invalid-feedback>
                <b-form-invalid-feedback v-if="!$v.form.email_funcional.email">
                  E-mail inválido!
                </b-form-invalid-feedback>
              </b-input-group>
            </b-form-group>
          </b-col>
        </b-row> -->

        <!-- <b-row>
          <b-col cols="12">
              <strong>Dados do órgão de atuação</strong>
              <br/>
          </b-col>
        </b-row>
        <br/>

        <b-row>
          <b-col md="3" sm="12">
            <b-form-group tabindex="0" label="Órgão de atuação: *">
              <b-form-select v-model="form.instituicao_id" placeholder="Selecione" :disabled="true">
                <option selected :value="null">Selecione</option>
                <option v-for="(item, index) in instituicoes" :key="item.id" :value="item.id">{{item.razao_social}}</option>
              </b-form-select>
            </b-form-group>
          </b-col>
        </b-row> -->

        <!-- <br/> -->

        <!-- <b-row>
          <b-col cols="12">
            <table aria-rowcount="4" id="my-table" aria-busy="false" aria-colcount="7" class="table table-responsive-sm table-bordered table-striped table-sm">
              <thead>
                <tr>
                  <th tabindex="0" scope="col">Nome do órgão</th>
                  <th tabindex="0" scope="col">CNPJ</th>
                </tr>
              </thead>
              <tbody v-if="form.instituicoes.length">
                <tr class="action" v-for="(item, index) in form.instituicoes" :key="item.cnpj">
                  <td tabindex="0">{{ item.razao_social }}</td>
                  <td tabindex="0">{{ item.cnpj }}</td>
                </tr>
              </tbody>
              <tbody v-else>
                <tr class="action">
                  <td tabindex="0" colspan="3">Nenhum órgão de atuação cadastrado</td>
                </tr>
              </tbody>
            </table>
          </b-col>
        </b-row>
        <br> -->

        <!-- <b-row>
          <b-col cols="12">
              <strong>Documentação obrigatória</strong>
              <br/>
          </b-col>
        </b-row>
        <br/>

        <b-row>
          <b-col md="6" sm="12">
            <label tabindex="0">RG/CNH: *</label>
            <span class="icon-info" v-b-popover.hover.top="'PDF, DOC, DOCX, JPG, JPEG e PNG'" title="Extensões aceitas" v-if="!visualizar"></span>
            <b-form-group label-for="nome-input" v-if="!visualizar">
              <b-form-file ref="upload-documento_rg" class="fileInput" accept=".png, .jpeg, .jpg, .pdf, .docx, .doc" v-on:change="uploadDocumento($event, 'documento_rg')" v-model="form.documento_rg" browse-text="Selecione" placeholder="Upload" :state="chkState('documento_rg')"></b-form-file>
              <b-form-invalid-feedback>Campo obrigatório.</b-form-invalid-feedback>
            </b-form-group>
            <b-button v-if="form.documento_rg && form.id" @click="downloadDocumento(form.documento_rg)" variant="primary" size="sm">
              <i class="fa fa-download"></i>
            </b-button>
          </b-col>
        </b-row>

        <b-row>
          <b-col md="6" sm="12">
            <label tabindex="0">CPF: *</label>
            <span class="icon-info" v-b-popover.hover.top="'PDF, DOC, DOCX, JPG, JPEG e PNG'" title="Extensões aceitas" v-if="!visualizar"></span>
            <b-form-group label-for="nome-input" v-if="!visualizar">
              <b-form-file ref="upload-documento_cpf" class="fileInput" accept=".png, .jpeg, .jpg, .pdf, .docx, .doc" v-on:change="uploadDocumento($event, 'documento_cpf')" v-model="form.documento_cpf" browse-text="Selecione" placeholder="Upload" :state="chkState('documento_cpf')"></b-form-file>
              <b-form-invalid-feedback>Campo obrigatório.</b-form-invalid-feedback>
            </b-form-group>
            <b-button v-if="form.documento_cpf && form.id" @click="downloadDocumento(form.documento_cpf)" variant="primary" size="sm">
              <i class="fa fa-download"></i>
            </b-button>
          </b-col>
        </b-row>

        <b-row>
          <b-col md="6" sm="12">
            <label tabindex="0">Ato ou declaração de investidura em cargo ou função: *</label>
            <span class="icon-info" v-b-popover.hover.top="'PDF, DOC, DOCX, JPG, JPEG e PNG'" title="Extensões aceitas" v-if="!visualizar"></span>
            <b-form-group label-for="nome-input" v-if="!visualizar">
              <b-form-file ref="upload-documento_ato_posse" class="fileInput" accept=".png, .jpeg, .jpg, .pdf, .docx, .doc" v-on:change="uploadDocumento($event, 'documento_ato_posse')" v-model="form.documento_ato_posse" browse-text="Selecione" placeholder="Upload" :state="chkState('documento_ato_posse')"></b-form-file>
              <b-form-invalid-feedback>Campo obrigatório.</b-form-invalid-feedback>
            </b-form-group>
            <b-button v-if="form.documento_ato_posse && form.id" @click="downloadDocumento(form.documento_ato_posse)" variant="primary" size="sm">
              <i class="fa fa-download"></i>
            </b-button>
          </b-col>
        </b-row>

        <b-row>
          <b-col md="6" sm="12">
            <label tabindex="0">Ato de delegação de competência: *</label>
            <span class="icon-info" v-b-popover.hover.top="'PDF, DOC, DOCX, JPG, JPEG e PNG'" title="Extensões aceitas" v-if="!visualizar"></span>
            <b-form-group label-for="nome-input" v-if="!visualizar">
              <b-form-file ref="upload-documento_ato_delegacao" class="fileInput" accept=".png, .jpeg, .jpg, .pdf, .docx, .doc" v-on:change="uploadDocumento($event, 'documento_ato_delegacao')" v-model="form.documento_ato_delegacao" browse-text="Selecione" placeholder="Upload" :state="chkState('documento_ato_delegacao')"></b-form-file>
              <b-form-invalid-feedback>Campo obrigatório.</b-form-invalid-feedback>
            </b-form-group>
            <b-button v-if="form.documento_ato_delegacao && form.id" @click="downloadDocumento(form.documento_ato_delegacao)" variant="primary" size="sm">
              <i class="fa fa-download"></i>
            </b-button>
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
        <b-col v-if="visualizar && can('solicitacao.aprovar')" md="3" sm="12" key="new_p2">
          <b-button block variant="success" v-on:click="modalAprovar">
            <em class="fa fa-check"></em>&nbsp;
            <span>Aprovar</span>
          </b-button>
        </b-col>
        <b-col v-if="visualizar && can('solicitacao.devolver')" md="3" sm="12" key="new_p3">
          <b-button block variant="warning" v-on:click="modalDevolver">
            <em class="fa fa-ban"></em>&nbsp;
            <span>Devolver</span>
          </b-button>
        </b-col>
        <b-col v-if="visualizar && can('solicitacao.reprovar')" md="3" sm="12" key="new_p3">
          <b-button block variant="danger" v-on:click="modalReprovar">
            <em class="fa fa-ban"></em>&nbsp;
            <span>Reprovar</span>
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
</template>

<script>
import Vue from 'vue'
import { validationMixin } from 'vuelidate'
import { required, minLength, email, requiredIf } from 'vuelidate/lib/validators'
import Datepicker from 'vuejs-datepicker'
import { ptBR } from 'vuejs-datepicker/dist/locale'
import SessionStorage from '@/services/session-storage'
import { esferaOptions } from '@/constants/solicitacao'

function validDate (value) { return this.$validDate(value, true) }
function validCPF (value) { return this.$validCPF(value, true) }

const acceptedTypes = [
  'image/jpeg',
  'image/jpg',
  'image/png',
  'application/pdf',
  'application/msword',
  'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
]

export default {
  name: 'MeuPerfil',
  components: {
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
  },
  data () {
    return {
      editar: false,
      editarCep: 1,
      loading: false,
      cpfDisabled: false,
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
        instituicoes: []
      },
      modalInstituicao: false,
      titleModalInstituicao: '',
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
        complemento: null
      },
      form_instituicao_index: null,
      titleModalAvaliacao: '',
      modalAvaliacao: false,
      form_avaliacao: {
        id: null,
        situacao: null,
        observacao: null
      },
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
      return this.$store.state.instituicao.lista
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
    let user = SessionStorage.get('usuario')
    this.load(user)
  },
  methods: {
    can (permissao) {
      return this.$store.getters.validaPermissao(permissao)
    },
    load (id) {
      Vue.http.get('api/user/' + id).then(response => {
        let data = response.body.data

        this.form.id = data.id
        this.form.instituicao_id = data.instituicao_id
        this.form.cpf = data.cpf
        this.form.email = data.email
        this.form.nome = data.nome
        this.form.nome_social = data.nome_social
        this.form.data_nascimento = data.data_nascimento
        this.form.sexo_id = data.sexo_id
        this.form.sexo_outro = data.sexo_outro
        this.form.orientacao_sexual_id = data.orientacao_sexual_id
        this.form.orientacao_sexual_outro = data.orientacao_sexual_outro
        this.form.raca_id = data.raca_id
        this.form.escolaridade_id = data.escolaridade_id
        this.form.possui_deficiencia = data.possui_deficiencia
        this.form.deficiencia_id = data.deficiencia_id
        this.form.tipo_representacao = data.tipo_representacao
        this.form.cargo = data.cargo
        this.form.telefone_funcional = data.telefone_funcional
        this.form.celular = data.celular
        this.form.email_funcional = data.email_funcional
        this.form.documento_rg = data.documento_rg
        this.form.documento_cpf = data.documento_cpf
        this.form.documento_ato_posse = data.documento_ato_posse
        this.form.documento_ato_delegacao = data.documento_ato_delegacao
        this.form.situacao = data.situacao

        this.$nextTick(() => {
          this.$refs.cpfInput.$el.dispatchEvent(new Event('input'))
          this.cpfDisabled = true
        })
      })
    },
    salvar () {
      let data = {
        id: this.form.id,
        email: this.form.email_funcional,
        cpf: this.form.cpf,
        nome: this.form.nome,
        nome_social: this.form.nome_social,
        data_nascimento: this.form.data_nascimento,
        sexo_id: this.form.sexo_id,
        sexo_outro: this.form.sexo_outro,
        orientacao_sexual_id: this.form.orientacao_sexual_id,
        orientacao_sexual_outro: this.form.orientacao_sexual_outro,
        raca_id: this.form.raca_id,
        escolaridade_id: this.form.escolaridade_id,
        possui_deficiencia: this.form.possui_deficiencia,
        deficiencia_id: this.form.deficiencia_id,
        telefone_funcional: this.form.telefone_funcional,
        celular: this.form.celular,
        email_funcional: this.form.email_funcional,
        documento_rg: this.form.documento_rg,
        documento_cpf: this.form.documento_cpf,
        documento_ato_posse: this.form.documento_ato_posse,
        documento_ato_delegacao: this.form.documento_ato_delegacao
      }

      this.loading = true
      if (data.id) {
        Vue.http.put('api/user/' + data.id, data).then(response => {
          if (response.body.success) {
            this.sucesso(response.body.msg)
            this.loading = false
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
    uploadDocumento (event, field) {
      let arquivo = event.target.files || event.dataTransfer.files
      if (!arquivo.length) {
        return false
      }

      if (arquivo[0].size > 15728640) {
        this.erro('Tamanho do arquivo maior que 15MB.')
        this.$refs[`upload-${field}`].reset()
        return false
      }

      if (!this.$validMimeType(arquivo[0].type, acceptedTypes)) {
        this.erro('Extensão do arquivo inválida.')
        this.$refs[`upload-${field}`].reset()
        return false
      }

      let reader = new FileReader()
      reader.onload = (evento) => {
        this.form[field] = evento.target.result
      }

      reader.readAsDataURL(arquivo[0])
    },
    downloadDocumento (documento) {
      window.open(process.env.ROOT_API1 + documento, '_blank')
    },
    // Modal de instituição
    abrirModalInstituicao () {
      this.modalInstituicao = true
      this.titleModalInstituicao = 'Incluir Instituição de Atuação'
    },
    editarInstituicao (index) {
      this.modalInstituicao = true
      this.titleModalInstituicao = 'Editar Instituição de Atuação'
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
    visualizarInstituicao (index) {
      this.modalInstituicao = true
      this.titleModalInstituicao = 'Visualizar Instituição de Atuação'
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
    excluirInstituicao (index) {
      this.form.instituicoes.splice(index, 1)
    },
    salvarInstituicao () {
      if (this.visualizar) {
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
      if (this.form_instituicao.razao_social == null || this.form_instituicao.razao_social == '') {
        this.erro('Razão Social é obrigatório.')
        return false
      }

      if (this.form_instituicao.razao_social.length < 5) {
        this.erro('Mínimo de 5 caracteres para a razão social.')
        return false
      }

      if (this.form_instituicao.cnpj == null || this.form_instituicao.cnpj == '') {
        this.erro('CNPJ é obrigatório.')
        return false
      }

      if (!this.$validCNPJ(this.form_instituicao.cnpj)) {
        this.erro('CNPJ inválido.')
        return false
      }

      if (this.form_instituicao.cep == null || this.form_instituicao.cep == '') {
        this.erro('CEP é obrigatório.')
        return false
      }

      if (this.form_instituicao.cep.length < 9) {
        this.erro('CEP inválido.')
        return false
      }

      if (this.form_instituicao.endereco == null || this.form_instituicao.endereco == '') {
        this.erro('Endereço é obrigatório.')
        return false
      }

      if (this.form_instituicao.endereco.length < 5) {
        this.erro('Mínimo de 5 caracteres para o endereço.')
        return false
      }

      if (this.form_instituicao.numero == null || this.form_instituicao.numero == '') {
        this.erro('Número é obrigatório.')
        return false
      }

      if (this.form_instituicao.bairro == null || this.form_instituicao.bairro == '') {
        this.erro('Bairro é obrigatório.')
        return false
      }

      if (this.form_instituicao.bairro.length < 5) {
        this.erro('Mínimo de 5 caracteres para o bairro.')
        return false
      }

      if (this.form_instituicao.estado_id == null || this.form_instituicao.estado_id == '') {
        this.erro('Estado é obrigatório.')
        return false
      }

      if (this.form_instituicao.cidade_id == null || this.form_instituicao.cidade_id == '') {
        this.erro('Cidade é obrigatório.')
        return false
      }

      if (this.form_instituicao.esfera == null || this.form_instituicao.esfera == '') {
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
        this.limparModalInstituicao()
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
      return this.isValid
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
    buscarCep (cep) {
      this.$viaCep.buscarCep(cep).then((obj) => {

        if (obj.erro === true) {
          return this.erro('O CEP digitado é inválido!')
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
            if (Number(cidade.codigo_ibge) === Number(obj.ibge)) {
              this.form_instituicao.cidade_id = cidade.id
            }
          }
        })

      });
    },
  }
}
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
