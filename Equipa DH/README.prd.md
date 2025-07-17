## 01. Introdução 

Instruções de implantação do EquipaDH

## 02. Build das aplicações do equipadh 

### 02.1 Build do backend

Executar build da imagem do backend

```bash
docker build -t mdh/equipadh-app:latest -f ./Dockerfile-backend .
```
### 02.2 Build do frontend

Realizar a alteração das variáveis de configuração do frontend

```bash
nano frontend/config/prod.env.js

'use strict'
module.exports = {
  SERVER: '"https://api-equipadh.mdh.gov.br"',
  NODE_ENV: '"production"',
  KEY_RECAPTCHA: '"6Lfk1_IUAAAAAJBFL5NauzAw4HiaoWfYirtz53z2"',
  ROOT_API1: '"https://api-equipadh.mdh.gov.br/"',
  URL_PORTAL: '"http://google.com.br"',
  URL_LOGIN: '"https://autenticador.mdh.gov.br/login-externo/equipadh"',
  URL_LOGOUT: '"https://sso.acesso.gov.br/logout?post_logout_redirect_uri=https://equipadh.mdh.gov.br/logout"'
}

```

Realizar da imagem do frontend 

```bash
docker build -t mdh/equipadh-web:latest -f ./Dockerfile-front .
```

## 03 Configuração das variáveis de ambiente caso as mesmas não estejam configuradas no ambiente

**deploy/dev/backend/compose/secrets.env**

```
DB_HOST=**HOST DO BANCO DE DADOS DO EQUIPADH (ESSE BANCO DEVERÁ SER CRIADO NO POSTGRESQL)**
DH_HOST=**LISTA DE NÓS DE LEITURA DO BANCO DE DADOS SEPARADOS POR VÍRGULA**
DB_PORT=**PORTA DE CONEXÃO DO BANCO DE DADOS DO EQUIPADH**
DB_DATABASE=**NOME DO BANCO DE DADOS DO EQUIPADH**
DB_USERNAME=**USERNAME DO BANCO DE DADOS DO EQUIPADH**
DB_PASSWORD=**SENHA CONEXÃO DO BANCO DE DADOS DO EQUIPADH**
DB_POOL_ENABLED=true
DB_POOL_MAX_CONNECTIONS=**NÚMERO MÁXIMO DE CONEXÕES DO POOL DE ACORDO COM A DEMANDA DO SISTEMA**
DB_POOL_TIMEOUT=30
MAIL_DRIVER=smtp
MAIL_HOST=relay.mdh.gov.br
MAIL_PORT=25
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=
MAIL_FROM_ADDRESS=equipadh@mdh.gov.br
MAIL_FROM_NAME=equipadh@mdh.gov.br
URL_FRONT=https://equipadh.mdh.gov.br
AUTH_URL_FRONT=https://autenticador.mdh.gov.br
AUTH_URL_API=https://api-autenticador.mdh.gov.br/api
AUTH_CLIENT_ID=equipadh
AUTH_CLIENT_SECRET=123456
AUTH_SIGLA=equipadh
AUTH_URL_LOGOUT=https://autenticador.mdh.gov.br/logout
ENCRYPT_KEY=MPfi@240195
ENCRYPT_VI=YUmm6hY7JOEOTBTJ
ENCRYPT_METHOD=AES-128-CBC
```

## 04 Orquestração dos containers

Subir os containers de acordo com a orquestração especificada no arquivo **deploy/dev/backend/compose/docker-compose.yml**

As portas não precisar estar espelhadas de acordo com o especificado e os seguintes containers devem possuir seus próprios DNS: 
```
equipadh-app => api-equipadh.mdh.gov.br
equipadh-web => equipadh.mdh.gov.br
```

## 05. Execução de comandos de criação do banco de dados da aplicação

Executar os comandos abaixo no container de backend

```
artisan migrate
```
