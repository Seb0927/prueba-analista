# Instalación y ejecución

## Requisito único

- [Docker Engine](https://docs.docker.com/engine/install/) o Docker Desktop
- Docker Compose (incluido en Docker Desktop y en el plugin `docker compose`)

No es necesario instalar PHP, Composer ni MySQL en el equipo anfitrión: todo se
ejecuta dentro de los contenedores.

## Puesta en marcha

```bash
# 1. Clonar el repositorio
git clone <url-del-repositorio> prueba-analista
cd prueba-analista

# 2. Construir y levantar los contenedores
docker compose up --build -d

# 3. Verificar el estado
docker compose ps
```

La aplicación queda disponible en **<http://localhost:8000>**.

El contenedor `db` importa `database/world.sql` automáticamente la primera vez
que se crea su volumen (`db_data`). El contenedor `app` espera a que MySQL esté
saludable antes de iniciar, genera la clave de la aplicación y prepara las
cachés de Laravel desde su `entrypoint.sh`.

## Comandos útiles

| Acción | Comando |
| --- | --- |
| Ver logs de la app | `docker compose logs -f app` |
| Ver logs de MySQL | `docker compose logs -f db` |
| Reiniciar | `docker compose restart` |
| Detener (sin borrar datos) | `docker compose down` |
| Detener y **reimportar** la base | `docker compose down -v && docker compose up --build -d` |
| Entrar a la app | `docker compose exec app sh` |
| Consultar la base | `docker compose exec db mysql -uanalista -panalistasecret world` |

> El usuario de la aplicación es `analista` / `analistasecret` y el de root es
> `root` / `rootsecret`. Para cambiar las contraseñas, define `MYSQL_PASSWORD` y
> `MYSQL_ROOT_PASSWORD` en un archivo `.env` antes de levantar los contenedores,
> y usa `docker compose down -v` para aplicar el cambio.

## Configuración

Las variables de entorno del contenedor `app` están en `docker-compose.yml` y
las plantilla general en `.env.example`. Los valores importantes son:

```dotenv
DB_CONNECTION=mysql
DB_HOST=db
DB_DATABASE=world
DB_USERNAME=analista
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

> La base `world` no contiene las tablas propias de Laravel (`sessions`, `cache`,
> `jobs`), por eso sesión y caché usan archivos y la cola usa `sync`.

## Resolución de problemas

| Síntoma | Causa / solución |
| --- | --- |
| `Access denied for user 'analista'` | Contraseña distinta a la del compose. Revísala y haz `down -v`. |
| La web carga pero no hay países | El dump no se importó. Revisa `docker compose logs db` y ejecuta `down -v`. |
| `No application encryption key` | El entrypoint no generó `APP_KEY`; ejecuta `docker compose exec app php artisan key:generate --force`. |
| Error 500 al consultar | Revisa `docker compose logs app` y `storage/logs` dentro del contenedor. |
| El puerto 8000 está ocupado | Cambia `"8000:80"` por otro puerto en `docker-compose.yml`. |

## Apéndice: instalación nativa (opcional, sin Docker)

Si prefieres no usar contenedores en Linux Mint / Ubuntu:

```bash
# Laravel 13 requiere PHP >= 8.4.1; en Linux Mint 22 / Ubuntu 24.04 se usa el PPA.
sudo apt install -y software-properties-common
sudo add-apt-repository -y ppa:ondrej/php
sudo apt update
sudo apt install -y php8.4 php8.4-cli php8.4-mysql php8.4-mbstring \
  php8.4-xml php8.4-curl php8.4-zip curl unzip composer mysql-server
sudo systemctl enable --now mysql
sudo mysql_secure_installation

cd /tmp
curl -LO https://downloads.mysql.com/docs/world-db.tar.gz
tar xzf world-db.tar.gz
sudo mysql < world-db/world.sql

cd prueba-analista
composer install
cp .env.example .env
php artisan key:generate
# Edita .env: DB_HOST=127.0.0.1, DB_USERNAME=root, DB_PASSWORD=<tu clave>
php artisan serve
```

Luego abre <http://127.0.0.1:8000>.
