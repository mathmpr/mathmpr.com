#!/usr/bin/env bash
set -euo pipefail

cd /var/www

if [ ! -f .env ]; then
    cp .env.example .env
fi

if [ ! -d vendor ]; then
    composer install --no-interaction --prefer-dist
fi

if grep -q '^APP_KEY=$' .env; then
    php artisan key:generate --ansi
fi

if [ ! -d node_modules ]; then
    npm install
fi

if [ ! -f public/mix-manifest.json ]; then
    npm run dev
fi

if [ ! -e public/storage ]; then
    php artisan storage:link
fi

db_connection="${DB_CONNECTION:-}"
if [ -z "$db_connection" ] && [ -f .env ]; then
    db_connection="$(grep -E '^DB_CONNECTION=' .env | cut -d '=' -f 2- || true)"
fi

if [ "${db_connection:-mysql}" = "mysql" ]; then
    php -r '
        $env = file_exists(".env") ? parse_ini_file(".env", false, INI_SCANNER_RAW) : [];
        $host = getenv("DB_HOST") ?: ($env["DB_HOST"] ?? "db");
        $port = getenv("DB_PORT") ?: ($env["DB_PORT"] ?? "3306");
        $database = getenv("DB_DATABASE") ?: ($env["DB_DATABASE"] ?? "");
        $username = getenv("DB_USERNAME") ?: ($env["DB_USERNAME"] ?? "root");
        $password = getenv("DB_PASSWORD") ?: ($env["DB_PASSWORD"] ?? "");
        $dsn = "mysql:host={$host};port={$port};dbname={$database}";

        fwrite(STDOUT, "Waiting for MySQL at {$host}:{$port}...\n");

        for ($attempt = 1; $attempt <= 60; $attempt++) {
            try {
                new PDO($dsn, $username, $password);
                fwrite(STDOUT, "MySQL is available.\n");
                exit(0);
            } catch (Throwable $exception) {
                if ($attempt === 60) {
                    fwrite(STDERR, "MySQL did not become available: {$exception->getMessage()}\n");
                    exit(1);
                }

                sleep(2);
            }
        }
    '
fi

if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    php artisan migrate --force
fi

if [ "${RUN_SEEDERS:-true}" = "true" ]; then
    php artisan db:seed --force
fi

exec "$@"
