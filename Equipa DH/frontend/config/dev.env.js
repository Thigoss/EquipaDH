'use strict'
const merge = require('webpack-merge')
const prodEnv = require('./prod.env')

module.exports = merge(prodEnv, {
  SERVER: '"http://localhost:81"',
  NODE_ENV: '"development"',
  KEY_RECAPTCHA: '"6LeWYaUUAAAAAJ_0S4Yt8nm9Kov4kyhBYMxLbmXq"',
  ROOT_API1: '"http://localhost:81/"',
  URL_PORTAL: '"http://google.com.br"',
  URL_AUTH: '"https://api-autenticador-dev.hlog.mdh.gov.br/api"',
  URL_LOGIN: '"https://autenticador-dev.hlog.mdh.gov.br/login-externo/participacao_PC_ROBSON"',
  URL_LOGOUT: '"https://sso.staging.acesso.gov.br/logout?post_logout_redirect_uri=http://ciptea.hlog.mdh.gov.br/logout"'
})
