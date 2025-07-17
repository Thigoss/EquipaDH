<template>
  <AppHeaderDropdown right no-caret>
    <template slot="header" v-if="avatar != null">
      <img :src="avatar" width="35" height="35" class="img-avatar" alt="" />
    </template>\
    <template slot="dropdown">
      <b-dropdown-header tag="div" class="text-center"><strong>Conta</strong></b-dropdown-header>
      <b-dropdown-item v-on:click="redirect"><em class="fa fa-user" /> Meu Perfil</b-dropdown-item>
      <b-dropdown-divider />
      <b-dropdown-item v-on:click="logout"><em class="fa fa-lock" /> Sair</b-dropdown-item>
    </template>
  </AppHeaderDropdown>
</template>

<script>
import { HeaderDropdown as AppHeaderDropdown } from '@coreui/vue'
import SessionStorage from '@/services/session-storage'
export default {
  name: 'DefaultHeaderDropdownAccnt',
  components: {
    AppHeaderDropdown
  },
  created () {
    this.nome = SessionStorage.get('nome')
    this.avatar = 'https://cdn-icons-png.flaticon.com/512/3541/3541871.png' // SessionStorage.get('avatar') ? SessionStorage.get('avatar') : 'https://cdn-icons-png.flaticon.com/512/149/149071.png'
  },
  data: () => {
    return {
      itemsCount: 42,
      url: '/perfil',
      nome: null,
      avatar: null
    }
  },
  computed: {
    usuario () {
      return this.$store.state.usuario.usuarioEditar
    }
  },
  methods: {
    redirect () {
      this.$router.push(this.url)
    },
    logout () {
      sessionStorage.clear()
      this.$router.push('/login')
      location.href = process.env.URL_LOGOUT
    },
    meuPerfil () {
      this.$router.push('/perfil')
    }
  }
}
</script>
