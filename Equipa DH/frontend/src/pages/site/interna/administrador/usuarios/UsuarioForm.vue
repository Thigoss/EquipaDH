<template>
  <span>
    <b-col md="12" sm="12">
      <b-card no-body>
        <b-card-body>
          <b-form v-on:submit.prevent="onSubmit">
            <h1 tabindex="0" v-if="!form.id"><strong>Cadastrar Usuário</strong></h1>
            <h1 tabindex="0" v-if="form.id && !visualizar"><strong>Editar Usuário</strong></h1>
            <h1 tabindex="0" v-if="visualizar"><strong>Visualizar Usuário</strong></h1>
            <br />
            <b-row>
              <b-col md="12" sm="12">
                <b-form-group label="Tipo do usuário: *" :disabled="visualizar">
                  <b-input-group>
                    <b-form-radio-group v-model="$v.form.tipo.$model" :state="chkState('tipo')"
                      :options="options_tipo"></b-form-radio-group>
                    <b-form-invalid-feedback :state="chkState('tipo')">Campo obrigatório!</b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col>
            </b-row>
            <b-row>
              <b-col cols="12">
                <strong>Dados pessoais</strong>
                <br />
              </b-col>
            </b-row>
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
                  <b-form-invalid-feedback :state="chkState('sexo_id')">Campo obrigatório!</b-form-invalid-feedback>
                </b-form-group>
              </b-col>
              <b-col md="3" sm="12" v-if="form.sexo_id === 7">
                <b-form-group tabindex="0" label="Identidade de gênero(outro): *">
                  <b-input-group class="mb-3">
                    <b-form-input type="text" class="form-control" placeholder="" v-model.trim="form.sexo_outro"
                      :state="chkState('sexo_outro')" :disabled="visualizar" />
                    <b-form-invalid-feedback :state="chkState('sexo_outro')">Campo
                      obrigatório!</b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col>
              <b-col md="3" sm="12">
                <b-form-group tabindex="0" label="Orientação sexual: *">
                  <b-input-group class="mb-3">
                    <b-form-select v-model="form.orientacao_sexual_id" placeholder="Selecione"
                      :state="chkState('orientacao_sexual_id')" :disabled="visualizar">
                      <option selected :value="null">Selecione</option>
                      <option v-for="item in orientacoesSexuais" :key="item.id" :value="item.id">{{ item.nome }}</option>
                    </b-form-select>
                    <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
                  </b-input-group>
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
                  <b-input-group class="mb-3">
                    <b-form-select v-model="form.raca_id" :state="chkState('raca_id')" :disabled="visualizar">
                      <option selected :value="null">Selecione</option>
                      <option v-for="item in racas" :key="item.id" :value="item.id">{{ item.nome }}</option>
                    </b-form-select>
                    <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col>
              <b-col md="3" sm="12">
                <b-form-group tabindex="0" label="Escolaridade: *">
                  <b-input-group class="mb-3">
                    <b-form-select v-model="form.escolaridade_id" :state="chkState('escolaridade_id')"
                      :disabled="visualizar">
                      <option selected :value="null">Selecione</option>
                      <option v-for="item in escolaridades" :key="item.id" :value="item.id">{{ item.nome }}</option>
                    </b-form-select>
                    <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col>
              <b-col md="3" sm="12">
                <b-form-group tabindex="0" label="Possui deficiência: *">
                  <b-input-group class="mb-3">
                    <b-form-select v-model="form.possui_deficiencia" :state="chkState('possui_deficiencia')"
                      :disabled="visualizar">
                      <option selected :value="null">Selecione</option>
                      <option v-for="item in options" :key="item.value" :value="item.value">{{ item.text }}</option>
                    </b-form-select>
                    <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col>
              <b-col md="3" sm="12" v-if="form.possui_deficiencia">
                <b-form-group tabindex="0" label="Qual: *">
                  <b-input-group class="mb-3">
                    <b-form-select v-model="form.deficiencia_id" :state="chkState('deficiencia_id')"
                      :disabled="visualizar">
                      <option selected :value="null">Selecione</option>
                      <option v-for="item in deficiencias" :key="item.id" :value="item.id">{{ item.nome }}</option>
                    </b-form-select>
                    <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
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
            <b-row v-if="form.tipo == 'E'">
              <b-col md="3" sm="12">
                <b-form-group tabindex="0" label="Tipo de atuação: *">
                  <b-form-select v-model="form.tipo_representacao" placeholder="Selecione" class="mb-3"
                    :state="chkState('tipo_representacao')" :disabled="visualizar">
                    <option selected :value="null">Selecione</option>
                    <option v-for="item in options_tipo_atuacao" :key="item.value" :value="item.value">{{ item.text }}
                    </option>
                  </b-form-select>
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

            <!-- combos de perfis e unidades -->
            <b-row>
              <b-col md="6" sm="12">
                <b-form-group tabindex="0" label="Perfil:">
                  <b-form-select v-model="form.perfil_id" placeholder="Selecione" class="mb-3"
                    :state="chkState('perfil_id')" :disabled="visualizar" @change="validatePerfil">
                    <option selected :value="null">Selecione</option>
                    <option v-for="item in perfis" :key="item.id" :value="item.id" :disabled="!item.ativo">{{ item.nome }}
                    </option>
                  </b-form-select>
                </b-form-group>
              </b-col>
              <b-col md="6" sm="12" v-if="form.tipo == 'I'">
                <b-form-group tabindex="0" label="Unidade:">
                  <b-form-select v-model="form.unidade_id" placeholder="Selecione" class="mb-3"
                    :state="chkState('unidade_id')" :disabled="visualizar" @change="validatePerfil">
                    <option selected :value="null">Selecione</option>
                    <option v-for="item in unidades" :key="item.id" :value="item.id" :disabled="!item.ativo">
                      {{ item.nome }}</option>
                  </b-form-select>
                </b-form-group>
              </b-col>
            </b-row>

            <b-row class="mb-3" v-if="form.tipo == 'I' && form.id">
              <b-col>
                <b>Vinculos do Usuário</b>
              </b-col>
            </b-row>
            <b-row v-if="form.tipo == 'I' && form.id">
              <b-col>
                <table class="table table-bordered table-striped table-sm text-center">
                  <thead>
                    <tr>
                      <th tabindex="0" scope="col">Perfil</th>
                      <th tabindex="0" scope="col">Unidade</th>
                      <th tabindex="0" scope="col">Situação</th>
                    </tr>
                  </thead>
                  <tbody v-if="perfisUser.length !== 0">
                    <tr class="action" v-for="item in perfisUser" :key="item.id">
                      <td tabindex="0">{{ item.perfil ? item.perfil.nome : ' - ' }}</td>
                      <td tabindex="0">{{ item.unidade ? item.unidade.nome : ' - ' }}</td>
                      <td tabindex="0">{{ item.ativo ? 'Ativo' : 'Inativo' }}</td>
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr class="action">
                      <td tabindex="0" colspan="4">Nenhum vínculo cadastrado.</td>
                    </tr>
                  </tbody>
                </table>
              </b-col>
            </b-row>

            <b-row v-if="form.tipo == 'E'">
              <b-col cols="12">
                <strong>Documentação obrigatória</strong>
                <br />
              </b-col>
            </b-row>
            <br />

            <b-row v-if="form.tipo == 'E'">
              <b-col md="6" sm="12">
                <label tabindex="0">RG/CNH: *</label>
                <span class="icon-info" v-b-popover.hover.top="'PDF, DOC, DOCX, JPG, JPEG e PNG'"
                  title="Extensões aceitas" v-if="!visualizar"></span>
                <b-form-group label-for="nome-input" v-if="!visualizar">
                  <b-form-file ref="docRg" class="fileInput" accept=".png, .jpeg, .jpg, .pdf, .docx, .doc"
                    v-on:change="uploadDocumentoRg" v-model="form.documento_rg" browse-text="Selecione"
                    placeholder="Upload" :state="chkState('documento_rg')"></b-form-file>
                  <b-form-invalid-feedback>Campo obrigatório.</b-form-invalid-feedback>
                </b-form-group>
                <b-button v-if="form.documento_rg && form.id" @click="downloadDocumento('user_rg', form.id)"
                  variant="primary" size="sm">
                  <i class="fa fa-download"></i>
                </b-button>
              </b-col>
            </b-row>

            <b-row v-if="form.tipo == 'E'">
              <b-col md="6" sm="12">
                <label tabindex="0">CPF: *</label>
                <span class="icon-info" v-b-popover.hover.top="'PDF, DOC, DOCX, JPG, JPEG e PNG'"
                  title="Extensões aceitas" v-if="!visualizar"></span>
                <b-form-group label-for="nome-input" v-if="!visualizar">
                  <b-form-file ref="docCPF" class="fileInput" accept=".png, .jpeg, .jpg, .pdf, .docx, .doc"
                    v-on:change="uploadDocumentoCpf" v-model="form.documento_cpf" browse-text="Selecione"
                    placeholder="Upload" :state="chkState('documento_cpf')"></b-form-file>
                  <b-form-invalid-feedback>Campo obrigatório.</b-form-invalid-feedback>
                </b-form-group>
                <b-button v-if="form.documento_cpf && form.id" @click="downloadDocumento('user_cpf', form.id)"
                  variant="primary" size="sm">
                  <i class="fa fa-download"></i>
                </b-button>
              </b-col>
            </b-row>

            <b-row v-if="form.tipo == 'E'">
              <b-col md="6" sm="12">
                <label tabindex="0">Ato ou declaração de investidura em cargo ou função: *</label>
                <span class="icon-info" v-b-popover.hover.top="'PDF, DOC, DOCX, JPG, JPEG e PNG'"
                  title="Extensões aceitas" v-if="!visualizar"></span>
                <b-form-group label-for="nome-input" v-if="!visualizar">
                  <b-form-file ref="docAtoPosse" class="fileInput" accept=".png, .jpeg, .jpg, .pdf, .docx, .doc"
                    v-on:change="uploadDocumentoAtoPosse" v-model="form.documento_ato_posse" browse-text="Selecione"
                    placeholder="Upload" :state="chkState('documento_ato_posse')"></b-form-file>
                  <b-form-invalid-feedback>Campo obrigatório.</b-form-invalid-feedback>
                </b-form-group>
                <b-button v-if="form.documento_ato_posse && form.id"
                  @click="downloadDocumento('user_ato_posse', form.id)" variant="primary" size="sm">
                  <i class="fa fa-download"></i>
                </b-button>
              </b-col>
            </b-row>

            <b-row v-if="form.tipo == 'E' && form.tipo_representacao == 'R'">
              <b-col md="6" sm="12">
                <label tabindex="0">Ato de delegação de competência: *</label>
                <span class="icon-info" v-b-popover.hover.top="'PDF, DOC, DOCX, JPG, JPEG e PNG'"
                  title="Extensões aceitas" v-if="!visualizar"></span>
                <b-form-group label-for="nome-input" v-if="!visualizar">
                  <b-form-file ref="docAtoDelegacao" class="fileInput" accept=".png, .jpeg, .jpg, .pdf, .docx, .doc"
                    v-on:change="uploadDocumentoAtoDelegacao" v-model="form.documento_ato_delegacao"
                    browse-text="Selecione" placeholder="Upload"
                    :state="chkState('documento_ato_delegacao')"></b-form-file>
                  <b-form-invalid-feedback>Campo obrigatório.</b-form-invalid-feedback>
                </b-form-group>
                <b-button v-if="form.documento_ato_delegacao && form.id"
                  @click="downloadDocumento('user_ato_delegacao', form.id)" variant="primary" size="sm">
                  <i class="fa fa-download"></i>
                </b-button>
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
            <b-col v-else-if="!visualizar && loading && salvarForm" md="3" sm="12">
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
  </span>
</template>

<script>
import Vue from 'vue'
import VueSelect from 'vue-select'
import { validationMixin } from 'vuelidate'
import { required, requiredIf, minLength, email } from 'vuelidate/lib/validators'
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
  name: 'UsuarioForm',
  components: {
    VueSelect,
    Multiselect,
    Datepicker
  },
  mixins: [validationMixin],
  validations: {
    form: {
      tipo: {
        required
      },
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
      },
      tipo_representacao: {
        requiredIf: requiredIf(function () {
          return this.form.tipo === 'E'
        })
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
      perfil_id: {},
      unidade_id: {},
      documento_rg: {
        required: requiredIf(function () {
          return this.form.tipo === 'E'
        })
      },
      documento_cpf: {
        required: requiredIf(function () {
          return this.form.tipo === 'E'
        })
      },
      documento_ato_posse: {
        required: requiredIf(function () {
          return this.form.tipo === 'E'
        })
      },
      documento_ato_delegacao: {
        required: requiredIf(function () {
          return this.form.tipo === 'E' && this.form.tipo_representacao === 'R'
        })
      },
      // autoridade_rg: {
      //   required: requiredIf(function () {
      //     return this.form.tipo === 'E' && this.form.tipo_representacao === 'R'
      //   })
      // },
      // autoridade_cpf: {
      //   required: requiredIf(function () {
      //     return this.form.tipo === 'E' && this.form.tipo_representacao === 'R'
      //   })
      // },
      // autoridade_ato_posse: {
      //   required: requiredIf(function () {
      //     return this.form.tipo === 'E' && this.form.tipo_representacao === 'R'
      //   })
      // }
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
    this.$store.dispatch('getPerfis')
    this.$store.dispatch('getUnidades')
  },
  data () {
    return {
      editar: false,
      editarCep: 1,
      loading: false,
      form: {
        id: null,
        tipo: null,
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
        // autoridade_rg: null,
        // autoridade_cpf: null,
        // autoridade_ato_posse: null,
        perfil_id: null,
        unidade_id: null
      },
      perfisUser: [],
      options: [
        { value: true, text: 'Sim' },
        { value: false, text: 'Não' }
      ],
      options_tipo: [
        { value: 'E', text: 'Externo' },
        { value: 'I', text: 'Interno' }
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
    perfis () {
      return this.$store.state.perfil.lista
    },
    perfisAtivos () {
      let perfis = this.$store.state.perfil.lista

      perfis = perfis.filter(perfil => perfil.ativo === true)

      return perfis
    },
    unidades () {
      return this.$store.state.unidade.lista
    },
    esferaOptions () {
      return esferaOptions
    },
    isValid () {
      return !this.$v.form.$anyError
    }
  },
  watch: {
    'form.tipo': function () {
      if (this.form.tipo == 'I') {
        this.form.tipo_representacao = null
        this.form.documento_rg = null
        this.form.documento_cpf = null
        this.form.documento_ato_posse = null
        this.form.documento_ato_delegacao = null
      } else {
        this.form.unidade_id = null
      }
    },
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
    validatePerfil () {
      if (!this.loading) {
        if (this.form.tipo == 'I') {
          console.log(this.form.unidade_id)
          if (this.form.unidade_id && this.form.perfil_id) {
            let perfil = this.perfisUser.find(perfil => perfil.unidade_id == this.form.unidade_id)
            if (perfil) {
              this.erro('Usuário já vinculado a esta unidade!')
              this.form.unidade_id = null
              this.form.perfil_id = null
            }
          }
        } else {
          let perfil = this.perfisUser.find(perfil => perfil.perfil_id == this.form.perfil_id)
          if (perfil) {
            this.erro('Este perfil já está vinculado ao usuário!')
            this.form.perfil_id = null
          }
        }
      }
    },
    can (permissao) {
      return this.$store.getters.validaPermissao(permissao)
    },
    load (id) {
      this.loading = true
      Vue.http.get('api/user/' + id).then(response => {
        let data = response.body.data

        this.form.id = data.id
        this.form.tipo = data.tipo
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
        // this.form.autoridade_rg = data.autoridade_rg
        // this.form.autoridade_cpf = data.autoridade_cpf
        // this.form.autoridade_ato_posse = data.autoridade_ato_posse
        this.form.situacao = data.situacao

        if (data.perfis && data.perfis[0] !== undefined) {
          this.form.perfil_id = data.perfis[0].perfil_id
          this.form.unidade_id = data.perfis[0].unidade_id
        }

        this.perfisUser = data.perfis

        this.$nextTick(() => {
          this.loading = false
        })
      })
    },
    salvar () {
      let data = {
        id: this.form.id,
        tipo: this.form.tipo,
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
        // autoridade_rg: this.form.autoridade_rg,
        // autoridade_cpf: this.form.autoridade_cpf,
        // autoridade_ato_posse: this.form.autoridade_ato_posse,
        situacao: this.form.situacao,
        perfil_id: this.form.perfil_id,
        unidade_id: this.form.unidade_id
      }

      this.loading = true
      if (data.id) {
        Vue.http.put('api/user/' + data.id, data).then(response => {
          if (response.body.success) {
            this.sucesso(response.body.msg)
            this.loading = false
            let t = this
            setTimeout(function () {
              t.navigate('/usuarios')
            }, 3000)
          } else {
            this.erro(response.body.msg)
            this.loading = false
          }
        }).catch(e => {
          this.erro('Erro ao salvar usuário!')
          this.loading = false
          console.log('Servidor está fora! ' + e)
        })
      } else {
        Vue.http.post('api/user', data).then(response => {
          if (response.body.success) {
            this.sucesso(response.body.msg)

            let t = this
            setTimeout(function () {
              t.navigate('/usuarios')
            }, 3000)
          } else {
            this.erro(response.body.msg)
            this.loading = false
          }
        }).catch(e => {
          this.erro('Erro ao salvar usuário!')
          this.loading = false
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
    // Upload de documentos
    uploadDocumentoCpf (evento) {
      let arquivo = evento.target.files || evento.dataTransfer.files
      if (!arquivo.length) {
        return false
      }
      if (arquivo[0].size > 15728640) {
        this.erro('Tamanho do arquivo maior que 15MB.')
        this.$refs.docCpf.reset()
        return false
      }
      if (!this.$validMimeType(arquivo[0].type, acceptedTypes)) {
        this.erro('Extensão do arquivo inválida.')
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
    downloadDocumento (origem, id) {
      this.$abrirDocumento(origem, id)
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
      })
    }
  }
}
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
