# Fullstack Challenge - Dictionary API

Uma API Restful que permite aos usuários registrar-se, fazer login, visualizar palavras do dicionário e gerenciar suas favoritas.

---

## 🛠️ Tecnologias Utilizadas

- **Laravel**: Framework PHP para desenvolvimento da API.
- **Redis**: Sistema de armazenamento em cache.
- **JWT (JSON Web Tokens)**: Para autenticação de usuários.
- **MySQL**: Banco de dados relacional.

---

## 📂 Estrutura do Projeto
/api-ortep
├── app
│ ├── Console
│ │ └── Commands
│ │ └── ImportWords.php
│ ├── Http
│ │ ├── Controllers
│ │ │ ├── AuthController.php
│ │ │ ├── EntryController.php
│ │ │ └── UserController.php
│ ├── Models
│ │ ├── Favorite.php
│ │ ├── History.php
│ │ └── User.php
├── config
│ ├── auth.php
│ ├── cache.php
│ └── outros arquivos de configuração...
├── database
│ ├── migrations
│ │ ├── 0001_01_01_000000_create_users_table.php
│ │ ├── 2025_01_22_055258_create_favorites_table.php
│ │ ├── 2025_01_22_073059_create_words_table.php
│ │ └── outras migrations...
├── public
├── resources
├── routes
│ └── api.php
├── storage
├── tests
├── .env
└── words_dictionary.json

---

## ⚙️ Configuração do Ambiente

### 1. Instalação do Laravel Sail (Docker)
Para iniciar o projeto, você deve ter o Docker instalado. Utilize o Laravel Sail para configurar o ambiente:
./vendor/bin/sail up -d

### 2. Configuração do Banco de Dados e Cache (Redis)
Certifique-se de que as variáveis no arquivo `.env` estão configuradas corretamente:
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password
CACHE_DRIVER=redis
REDIS_HOST=redis
REDIS_PORT=6379

### 3. Executar Migrations
./vendor/bin/sail artisan migrate

### 4. Importar Palavras do Dicionário 
Um comando Artisan foi criado para importar palavras do arquivo `words_dictionary.json`:
./vendor/bin/sail artisan import:words

---

## 🚀 Endpoints da API

### 1. Rota Inicial: `[GET] /`
Retorna a mensagem "Fullstack Challenge 🏅 - Dictionary".

**Exemplo de Resposta:**
    {
    "message": "Fullstack Challenge 🏅 - Dictionary"
    }

### 2. Autenticação:
- **[POST] /auth/signup**  
  Registra um novo usuário.

  **Exemplo de Requisição:**
    {
    "name": "User Test",
    "email": "test@example.com",
    "password": "password123"
    }

**Exemplo de Resposta:**
    {
    "id": 1,
    "name": "User Test",
    "token": "Bearer <seu_token_jwt>"
    }

- **[POST] /auth/signin**  
Autentica um usuário e retorna um token JWT.

**Exemplo de Requisição:**
    {
    "email": "test@example.com",
    "password": "password123"
    }  

**Exemplo de Resposta:**
    {
    "id": 1,
    "name": "User Test",
    "token": "Bearer <seu_token_jwt>"
    }

### 3. Dicionário:
- **[GET] /entries/en**
- Lista palavras com busca e paginação.

- **[GET] /entries/en/:word**
- Retorna detalhes da palavra especificada e registra no histórico.

### 4. Favoritos:
- **[POST] /entries/en/:word/favorite**
- Adiciona uma palavra aos favoritos.

- **[DELETE] /entries/en/:word/unfavorite**
- Remove uma palavra dos favoritos.

### 5. Usuário:
- **[GET] /user/me**
- Retorna o perfil do usuário autenticado.

- **[GET] /user/me/history**
- Retorna o histórico de palavras visualizadas pelo usuário.

- **[GET] /user/me/favorites**
- Retorna as palavras favoritas do usuário.

---

## 📖 Processos de Investigação

Durante o desenvolvimento deste projeto, várias decisões foram tomadas:

1. **Escolha do Redis**: Optou-se por usar o Redis devido à sua eficiência em caching, melhorando a performance das requisições repetidas.
2. **Estrutura das Rotas**: As rotas foram estruturadas com base nos requisitos fornecidos, garantindo que cada funcionalidade fosse acessível através de endpoints RESTful.
3. **Implementação do Cache**: O cache foi implementado nas rotas que realizam buscas frequentes, utilizando os headers `x-cache` e `x-response-time` para monitorar a eficácia do cache.
4. **Apelido para o Redis**: O Redis foi apelidado como um sistema leve e rápido, ideal para armazenar dados temporários e otimizar a performance da aplicação.

---

## 📋 Conclusão

Este projeto atende aos requisitos solicitados no desafio, implementando uma API funcional com autenticação, gerenciamento de favoritos e histórico, além de otimizações através do uso de cache com Redis.

---

## Finalização e Instruções para a Apresentação

1. Adicione o link do repositório com a sua solução no teste.
2. Adicione o link da apresentação do seu projeto no README.md.
3. Verifique se o Readme está bom e faça o commit final em seu repositório;
4. Envie e aguarde as instruções para seguir. Sucesso e boa sorte. =)

> This is a challenge by [Coodesh](https://coodesh.com/)



