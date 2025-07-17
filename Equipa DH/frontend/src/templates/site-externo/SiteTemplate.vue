<template>
  <div class="app">
    <b-card>
      <b-card-header style="    top: 0;
                        left: 0;
                        width: 100%;
                        margin-top: 0;
                        position: fixed;
                        z-index: 998;
                        overflow: hidden;">
        <br><br>
        <div class="acesso-login">
          <label class="label-acesso-login" tabindex="0">Administradores de Estados e Municípios:</label>
          <b-button variant="info" @click="navigate('/login')" size="sm">Acesse Aqui</b-button>
        </div>
        <div style="text-align: center">
        </div>
        <div>
          <h3 tabindex="0">
            <p class="header-title"><b>CIPTEA</b> - CARTEIRA DE IDENTIFICAÇÃO DA PESSOA COM TRANSTORNO DE ESPECTRO
              AUTISTA</p>
          </h3>
        </div>
      </b-card-header>
      <b-card-body>
        <b-row class="mb-4">
          <b-col sm="12">
            <b-carousel :interval="10000" controls>
              <b-carousel-slide img-src="/static/img/banner-ciptea.png"
                img-alt="Imagem CIPTEA sistema de emissão da pessoa com transtorno do espectro autista"></b-carousel-slide>
              <!-- <b-carousel-slide img-src="https://mdbootstrap.com/img/Photos/Slides/img%20(15).jpg"></b-carousel-slide>
              <b-carousel-slide img-src="https://picsum.photos/1024/480/?image=58"></b-carousel-slide> -->
            </b-carousel>
          </b-col>
        </b-row>
        <div class="home-header">
          <b-row>
            <b-col sm="12">
              <h2 tabindex="0"><strong>Sistema de Emissão da Carteira de Identificação da Pessoa com Transtorno do
                  Espectro Autista</strong></h2>
            </b-col>
          </b-row>
          <br>
          <b-row>
            <b-col sm="12">
              <p tabindex="0">
                Sistema de Emissão da Carteira de Identificação da Pessoa com Transtorno do Espectro Autista – CIPTEA,
                possibilita geração da carteira em âmbito Nacional sendo que o documento digital facilita a
                identificação e a prioridade no atendimento em serviços públicos e privados, em especial nas áreas de
                saúde, educação e assistência social. No caso dos particulares, isso inclui supermercados, bancos,
                farmácias, bares, restaurantes e lojas em geral.
                Em 2012 foi publicada a Lei nº 12.764, de 27 de dezembro de 2012, que instituiu a Política Nacional de
                Proteção dos Direitos da Pessoa com Transtorno do Espectro Autista. Essa lei foi um grande marco para
                pessoas com TEA e seus familiares.
                Já em 2020, essa lei foi alterada pela Lei nº 13.977, de 8 de janeiro de 2020, que instituiu a Carteira
                de Identificação da Pessoa com Transtorno do Espectro Autista (Ciptea) com o objetivo de garantir
                atenção integral, pronto atendimento e prioridade no atendimento e no acesso aos serviços públicos e
                privados, em especial nas áreas de saúde, educação e assistência social para a pessoa com Transtorno do
                Espectro Autista.
                A Lei estabelece que a CIPTEA deve ser expedida pelos Estados, Distrito Federal e Municípios, e
                estabelece a necessidade de providenciar a contagem das pessoas com transtorno do espectro autista em
                todo o território nacional. Assim, a Secretaria Nacional dos Diretos da Pessoa com Deficiência lança o
                Sistema Nacional de emissão da Carteira de Identificação da Pessoa com Transtorno do Espectro Autista.
                Vale destacar que a emissão da referida carteirinha nacional não substitui a avaliação da deficiência,
                prevista na Lei Brasileira de Inclusão da Pessoa com Deficiência (Lei nº 13.146, de 6 de julho de 2015),
                artigo 2º, § 1º. Entretanto, espera-se que a emissão nacional e padronizada da CIPTEA contribua para a
                criação de um painel de Business Intelligence que contribuirá com o monitoramento e melhoria das
                políticas públicas votadas para as pessoas com TEA.
              </p>
            </b-col>
          </b-row>
        </div>
      </b-card-body>
      <b-card-footer style="text-align: center;">
        <row>
          <b-button variant="info" @click="navigate('/carteirinha/gerar')" size="lg">Emitir Carteirinha</b-button>
          <b-button variant="warning" @click="navigate('/fale-conosco/cadastrar')" size="lg">Fale Conosco</b-button>
        </row>
      </b-card-footer>
    </b-card>
  </div>
</template>

<script>

export default {
  name: 'SiteTemplate',
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
.card-body {
  padding: 0;
  margin: 0;
  margin-top: 90px;
}

.home-header {
  margin: 0 auto;
  margin-top: 5%;
  width: 80%
}

.home-nav {
  margin: 0 auto;
  width: 80%;
  margin-top: 25px;
  margin-bottom: 20px;
}

.home-header h2 {
  text-align: center;
}

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

.label-acesso-login {
  font-family: Rawline, Raleway, sans-serif;
  font-size: 0.8rem;
  color: #555;
  font-weight: 400;
  line-height: 1.75;
  text-align: end;
}

.acesso-login {
  float: right;
}

@media screen and (max-width: 1288px) {
  .card-body {
    margin-top: 110px;
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
