<template>
  <b-card>
    <b-card-header style="    top: 0;
                        left: 0;
                        width: 100%;
                        margin-top: 0;
                        position: fixed;
                        z-index: 998;
                        overflow: hidden;">
      <br><br>
      <div style="text-align: center">
      </div>
      <div>
        <h3 tabindex="0">
          <p class="header-title"><b>CIPTEA</b> - CARTEIRA DE IDENTIFICAÇÃO DA PESSOA COM TRANSTORNO DE ESPECTRO AUTISTA
          </p>
        </h3>
      </div>
    </b-card-header>
    <b-col md="12" sm="12">
      <b-card no-body class="mx-4">
        <b-card-body style="margin-top: 150px;">
          <FaleConoscoCadastrar :voltar="true"> </FaleConoscoCadastrar>
        </b-card-body>
      </b-card>
    </b-col>
  </b-card>
</template>

<script>
import FaleConoscoCadastrar from '@/pages/site/interna/faleConosco/FaleConoscoCadastrar'

export default {
  name: 'SiteTemplate',
  components: {
    FaleConoscoCadastrar
  },
  created () {
    //
  },
  data () {
    return {
      //
    }
  },
  computed: {
    //
  },
  mounted () {
    this.carregar()
  },
  methods: {
    inscricao () {
      this.$router.push('/login')
    },
    navigate (route) {
      this.$router.push(route)
    }
  }
}
</script>

<style lang="scss">
// CoreUI Icons Set
@import "@coreui/icons/css/coreui-icons.min.css";
/* Import Font Awesome Icons Set */
$fa-font-path: "~font-awesome/fonts/";
@import "~font-awesome/scss/font-awesome.scss";
/* Import Simple Line Icons Set */
$simple-line-font-path: "~simple-line-icons/fonts/";
@import "~simple-line-icons/scss/simple-line-icons.scss";
/* Import Flag Icons Set */
@import "flag-icon-css/css/flag-icon.min.css";
/* Import Bootstrap Vue Styles */
@import "bootstrap-vue/dist/bootstrap-vue.css";
// Import Main styles for this application
@import "../../assets/scss/style";
</style>

<style scoped>
p {
  font-family: Rawline, Raleway, sans-serif;
  font-size: 1.2rem;
  color: #555;
  font-weight: 400;
  line-height: 1.75;
  text-align: justify;
}

p.header-title {
  font-family: Rawline, Raleway, sans-serif;
  font-size: 1.5rem;
  color: #555;
  font-weight: 400;
  line-height: 1.75;
  text-align: justify;
  margin-top: 3rem;
}

.header-logo {
  height: 110px;
  width: 150px;
  float: left;
}

@media screen and (max-width: 1288px) {
  .card-body {
    margin-top: 40px;
  }

}

@media screen and (max-width: 786px) {
  .card-body {
    margin-top: 140px;
  }

  .header-logo {
    display: inline-block;
    float: none;
  }

  p.header-title {
    margin-top: 0rem;
  }
}

@media screen and (max-width: 620px) {
  .card-body {
    margin-top: 160px;
  }

}
</style>
