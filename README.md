# Fullstack Challenge - Dictionary API

-- Link do video testando a API: https://drive.google.com/file/d/1oK65f8prkhOUI4hS05bKSljtS_3GEwyQ/view?usp=sharing

Uma API Restful que permite aos usuários registrar-se, fazer login, visualizar palavras do dicionário e gerenciar suas favoritas.

---

## 🚀 Processo de Desenvolvimento

Como desenvolvedor júnior, segui uma abordagem estruturada para construir este projeto. Aqui está o passo a passo do desenvolvimento:

### 1. Configuração Inicial
Primeiro, configurei o ambiente base:
- Instalei o Laravel 11 usando Composer
- Configurei o Laravel Sail (Docker) para garantir um ambiente consistente
- Defini as variáveis de ambiente no arquivo `.env`

### 2. Banco de Dados
Comecei pelo banco de dados pois é a fundação do projeto:
- Criei as migrations para estruturar o banco:
  - `users`: Para armazenar dados dos usuários
  - `favorites`: Para guardar as palavras favoritas
  - `histories`: Para registrar palavras visualizadas
- Executei as migrations para criar as tabelas

### 3. Autenticação
Implementei a autenticação pois é necessária para outras funcionalidades:
- Configurei o JWT para tokens de autenticação
- Criei o AuthController para registro e login
- Implementei as rotas de autenticação

### 4. Importação do Dicionário
Desenvolvi o sistema de importação de palavras:
- Criei um comando Artisan personalizado (`ImportWords`)
- Processei o arquivo `words_dictionary.json`
- Importei as palavras para o banco de dados

### 5. Cache com Redis
Implementei o sistema de cache para otimizar a performance:
- Configurei o Redis como driver de cache
- Adicionei cache nas consultas frequentes
- Implementei os headers obrigatórios (x-cache e x-response-time)

### 6. Controllers e Rotas
Desenvolvi os controllers principais:
- `EntryController`: Para gerenciar palavras do dicionário
- `UserController`: Para perfil e histórico do usuário
- `FavoriteController`: Para gerenciar favoritos

### 7. Testes e Validação
Por fim, realizei testes para garantir a qualidade:
- Testei todas as rotas no Insomnia
- Verifiquei o funcionamento do cache
- Validei a autenticação e autorização

### Por que esta ordem?

1. **Banco Primeiro**: Começar pelo banco de dados permite entender melhor a estrutura dos dados e relacionamentos
2. **Autenticação**: É fundamental ter autenticação funcionando antes de implementar funcionalidades protegidas
3. **Cache Depois**: Implementei cache após ter as funcionalidades básicas funcionando, para otimizar o que já estava pronto
4. **Testes por Último**: Testar com tudo implementado permite uma validação mais completa

### Desafios Encontrados

- Configuração inicial do Docker/Sail( tive pouco contato com o Docker)
- Implementação do sistema de cache com Redis (não conhecia o Redis)
- Importação eficiente do dicionário
- Gestão de tokens JWT

### Aprendizados

- Importância de um bom planejamento inicial
- Benefícios de usar containers Docker
- Como implementar cache eficientemente
- Boas práticas de autenticação com JWT

---

## 🛠️ Tecnologias Utilizadas

- **Laravel**: Framework PHP para desenvolvimento da API
- **Redis**: Sistema de armazenamento em cache
- **JWT (JSON Web Tokens)**: Para autenticação de usuários
- **MySQL**: Banco de dados relacional
---

## ⚙️ Configuração do Ambiente e Docker

### Configuração Inicial
1. **Instalação do Laravel Sail (Docker)**
./vendor/bin/sail up -d

2. **Configuração do Banco de Dados e Cache (Redis)**
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password
CACHE_DRIVER=redis
REDIS_HOST=redis
REDIS_PORT=6379

3. **Executar Migrations**
./vendor/bin/sail artisan migrate

4. **Importar Palavras do Dicionário**
./vendor/bin/sail artisan import:words

### Docker e DevOps

O projeto utiliza Laravel Sail, fornecendo uma configuração Docker robusta e padronizada para desenvolvimento e deploy.

#### Containers e Serviços
- **Laravel**: Aplicação PHP
- **MySQL**: Banco de dados relacional
- **Redis**: Sistema de cache
- **Mailpit**: Servidor de email para testes
- **Selenium**: Testes automatizados
- **Meilisearch**: Motor de busca

#### Comandos Docker (Laravel Sail)
Iniciar todos os containers Docker
./vendor/bin/sail up -d
Parar todos os containers
./vendor/bin/sail down
Executar comandos do Artisan
./vendor/bin/sail artisan [comando]
Executar comandos do Composer
./vendor/bin/sail composer [comando]

#### Benefícios para DevOps
- Ambiente isolado e reproduzível
- Configuração padronizada via docker-compose.yml
- Fácil escalabilidade e manutenção
- Documentação oficial extensa
- Integração contínua simplificada

---
## 📚 Documentação OpenAPI 3.0

A API possui documentação completa seguindo as especificações OpenAPI 3.0, permitindo uma visualização interativa de todos os endpoints e suas funcionalidades.

### Acesso à Documentação
- **Endpoint**: `/api-docs`
- **Método**: GET
- **Descrição**: Interface interativa com todos os endpoints, parâmetros e exemplos de requisições/respostas

### Processo de Documentação
1. Exportação da coleção do Insomnia (ferramenta de teste de API)
2. Conversão para formato OpenAPI 3.0 usando insomnia-documenter
3. Integração com o projeto Laravel na pasta `public/docs`

### Recursos Documentados
- Descrições detalhadas de todos os endpoints
- Exemplos de requisições e respostas
- Esquemas de autenticação
- Modelos de dados
- Códigos de status HTTP

---

## 🚀 Endpoints da API

### 1. Rota Inicial: `[GET] /`
Retorna a mensagem "Fullstack Challenge 🏅 - Dictionary".

**Exemplo de Resposta:**
    {
    "message": "Fullstack Challenge 🏅 - Dictionary"
    }

### 2. Autenticação
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

### 3. Dicionário
- **[GET] /entries/en**
  - Lista palavras com busca e paginação.

- **[GET] /entries/en/:word**
  - Retorna detalhes da palavra especificada e registra no histórico.

### 4. Favoritos
- **[POST] /entries/en/:word/favorite**
  - Adiciona uma palavra aos favoritos.

- **[DELETE] /entries/en/:word/unfavorite**
  - Remove uma palavra dos favoritos.

### 5. Usuário
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

Este projeto atende aos requisitos solicitados no desafio, implementando uma API funcional com autenticação, gerenciamento de favoritos e histórico, além de otimizações através do uso de cache com Redis. Os diferenciais alcançados incluem:
- Documentação OpenAPI 3.0 completa
- Configuração Docker robusta através do Laravel Sail
- Sistema de cache eficiente com Redis
- Link do video testando a API: https://drive.google.com/file/d/1oK65f8prkhOUI4hS05bKSljtS_3GEwyQ/view?usp=sharing
