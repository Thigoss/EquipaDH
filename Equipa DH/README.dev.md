# 01. Pré Requisitos

- Docker instalado
- Orquestrador docker-compose instalado
- Nodejs e npm na versão 16 instalado

# 02. Realizar a build do container da imagem do backend

docker build -t api:latest . -f Dockerfile-backend

# 03. Alterar a imagem do container backend do arquivo docker-compose.yml presente na raiz do projeto para a imagem criada no passo anterior. 

# 04. Executar o comando abaixo para subir os containers

```
docker-compose up -d
```

# 05. Entrar no diretório frontend e executar o comando abaixo:

```
npm instal
npm run dev
```

Acessar o sistema no ambiente local com a url http://localhost:8080