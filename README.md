# mathmpr.com

Site pessoal em Laravel, com frontend em Blade/Sass e inicio de backend para
posts com suporte a i18n e composicao de conteudo por blocos.

O projeto ainda esta em desenvolvimento. A home e a tela de post unico sao
majoritariamente estaticas, enquanto o backend/admin ja tem a estrutura inicial
para criar `nodes` e `node_contents`.

## Stack

- PHP 8.3
- Laravel 12
- MySQL 8 ou SQLite para desenvolvimento simples
- Node.js e npm
- Laravel Mix/Webpack
- Sass
- jQuery, jQuery UI, Bootstrap, CodeMirror e Editor.js

## Requisitos

Para rodar com Docker:

- Docker
- Docker Compose

Para rodar localmente sem Docker:

- PHP 8.3 ou superior
- Composer
- Node.js e npm
- Extensoes PHP:
  - `dom`
  - `libxml`
  - `fileinfo`
  - `pdo_mysql`, se usar MySQL
  - `pdo_sqlite`, se usar SQLite
  - `gd`, usado por processamento/cache de imagens
- MySQL 8, se usar o banco padrao do `.env`

## Iniciar com Docker

Este e o caminho recomendado para desenvolvimento.

Crie o `.env` se ele ainda nao existir:

```bash
cp .env.example .env
```

O `.env` usado pelo Docker deve apontar para o MySQL interno:

```dotenv
DB_CONNECTION=mysql
DB_HOST=mathmpr_db
DB_PORT=3306
DB_DATABASE=mathmpr
DB_USERNAME=mathmpr
DB_PASSWORD=root
```

Suba o projeto:

```bash
docker compose up -d --build
```

Acesse:

```text
http://127.0.0.1:8000/pt
```

ou:

```text
http://127.0.0.1:8000/en
```

O stack Docker sobe:

- `mathmpr-app`: PHP-FPM/Laravel
- `mathmpr-nginx`: nginx em `http://127.0.0.1:8000`
- `mathmpr_db`: MySQL 8 na rede interna do Compose

O MySQL nao e publicado em uma porta do host. A aplicacao acessa o banco por
`mathmpr_db:3306` dentro da rede Docker.

Para acessar o MySQL manualmente:

```bash
docker compose exec db mysql -umathmpr -proot mathmpr
```

### Bootstrap automatico

O entrypoint do container PHP faz automaticamente:

- cria `.env` a partir de `.env.example`, se `.env` nao existir;
- instala dependencias PHP se `vendor/` nao existir;
- gera `APP_KEY` se estiver vazio;
- instala dependencias Node se `node_modules/` nao existir;
- roda `npm run dev` se `public/mix-manifest.json` nao existir;
- espera o MySQL ficar disponivel;
- roda `php artisan migrate --force`;
- roda `php artisan db:seed --force`.

Para subir sem rodar migrations ou seeders:

```bash
RUN_MIGRATIONS=false RUN_SEEDERS=false docker compose up -d
```

### Comandos uteis no Docker

Rodar Artisan:

```bash
docker compose exec mathmpr php artisan route:list
```

Rodar migrations manualmente:

```bash
docker compose exec mathmpr php artisan migrate
```

Rodar seeders manualmente:

```bash
docker compose exec mathmpr php artisan db:seed
```

Ver logs:

```bash
docker compose logs -f mathmpr
```

Reiniciar app:

```bash
docker compose restart mathmpr
```

Parar containers:

```bash
docker compose down
```

## Assets com Docker

Gerar assets uma vez dentro do container:

```bash
docker compose exec mathmpr npm run dev
```

Assistir alteracoes durante desenvolvimento:

```bash

```

Com Docker, o BrowserSync fica disponivel no host em:

- site com reload automatico: `http://127.0.0.1:3002/pt`
- interface do BrowserSync: `http://127.0.0.1:3003`

Se os containers ja estavam criados antes das portas `3002` e `3003` serem
adicionadas ao `docker-compose.yml`, recrie o servico da aplicacao:

```bash
docker compose up -d --force-recreate mathmpr nginx
```

Build de producao:

```bash
docker compose exec mathmpr npm run prod
```

Como o projeto inteiro e montado em `/var/www`, os assets gerados no container
tambem aparecem no host em `public/css`, `public/js` e `public/images`.

O `npm run watch` roda dentro do container `mathmpr`, mas faz proxy para o
nginx pela rede Docker usando `BROWSERSYNC_PROXY=http://nginx`.

## Instalacao local

Use esta secao apenas se quiser rodar sem Docker.

Instale as dependencias PHP e JavaScript:

```bash
composer install
npm install
```

Crie o arquivo de ambiente:

```bash
cp .env.example .env
php artisan key:generate
```

## Configuracao do banco

### Opcao 1: MySQL local

Configure o `.env` para apontar para o seu MySQL local:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mathmpr
DB_USERNAME=mathmpr
DB_PASSWORD=root
```

Crie o banco antes de rodar as migrations:

```sql
CREATE DATABASE mathmpr CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Depois rode:

```bash
php artisan migrate
php artisan db:seed
```

### Opcao 2: SQLite

Para um setup local mais simples, crie um arquivo SQLite dentro do projeto:

```bash
touch database.sqlite
```

Configure o `.env` assim:

```dotenv
DB_CONNECTION=sqlite
DB_DATABASE=database.sqlite
DB_FOREIGN_KEYS=true
```

Depois rode:

```bash
php artisan migrate
php artisan db:seed
```

Observacao: use `DB_DATABASE=database.sqlite` como caminho relativo. A config
atual concatena esse valor com a raiz do projeto.

## Build dos assets sem Docker

Gerar assets uma vez:

```bash
npm run dev
```

Assistir alteracoes durante desenvolvimento:

```bash
npm run watch
```

Build de producao:

```bash
npm run prod
```

Os assets sao gerados em `public/css`, `public/js` e `public/images`.

## Iniciar localmente sem Docker

Suba o servidor embutido do Laravel:

```bash
php artisan serve --host=127.0.0.1 --port=8001
```

Acesse:

```text
http://127.0.0.1:8001/en
```

A aplicacao redireciona rotas sem idioma para o idioma detectado/default. Os
idiomas configurados sao `en` e `pt`.

## Admin

Login:

```text
http://127.0.0.1:8000/pt/login
```

Usuario criado pelo seeder:

```text
E-mail: matheus@mathmpr.com
Senha: aquintem
```

Dashboard:

```text
http://127.0.0.1:8000/pt/dashboard
```

Criar post/node:

```text
http://127.0.0.1:8000/pt/dashboard/nodes/create
```

## Estrutura principal

- `routes/web.php`: rotas web com prefixo opcional de idioma.
- `routes/api.php`: API com prefixo opcional de idioma.
- `resources/views/web/frontend`: paginas publicas.
- `resources/views/web/backend`: telas do admin.
- `resources/assets/css`: Sass do frontend e backend.
- `resources/assets/js`: JavaScript do frontend e backend.
- `app/Models/Node.php`: entidade principal de post/pagina.
- `app/Models/NodeContent.php`: blocos de conteudo dos posts.
- `app/Models/Translate.php`: traducoes salvas no banco.
- `app/Utils/Lang.php`: descoberta de idioma e persistencia de campos traduziveis.

## Observacoes de estado

- A home e a pagina single ainda usam conteudo estatico/placeholder.
- O backend de posts esta parcialmente implementado.
- O editor drag and drop usa jQuery UI e salva blocos pela API.
- O suporte a i18n existe, mas ainda merece revisao antes de virar base final.
- O Docker esta configurado para desenvolvimento com nginx, PHP-FPM e MySQL.
