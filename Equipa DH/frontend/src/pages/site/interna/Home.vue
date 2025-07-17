<template>
  <span>
    <h3>Seja bem-vindo(a) {{ usuario }}!</h3>
  </span>
</template>
<script>
import miniToastr from 'mini-toastr'
import SessionStorage from '@/services/session-storage'

miniToastr.init()

export default {
  name: 'HelloWorld',
  data () {
    return {
      usuario: SessionStorage.get('nome')
    }
  },
  created () {
    if (SessionStorage.get('acompanhar-credenciamento') !== undefined) {
      SessionStorage.remove('acompanhar-credenciamento')
      this.$router.push('/minhas-adesoes')
    }

    if (SessionStorage.get('solicitar-adesao') !== undefined) {
      let id = SessionStorage.get('solicitar-adesao')
      SessionStorage.remove('solicitar-adesao')
      console.log('===============')
      this.$router.push('/adesao/cadastrar/' + id)
    }
  }
}
</script>
