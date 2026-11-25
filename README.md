# Explorador de ciudades por país

Aplicación web de una sola pantalla desarrollada como **Pregunta 1** de la prueba
técnica para el cargo de **Analista de Soporte Nivel 1 (Infodec)**.

Permite seleccionar cualquier país de la base de datos **World** y ver:

- el **Top 10** de ciudades más pobladas;
- el **Bottom 10** de ciudades menos pobladas;
- el listado completo de ciudades del país con su población.

La interfaz es responsiva y funciona en computador, tablet y teléfono.

## Tecnologías

| Capa | Tecnología |
| --- | --- |
| Backend | PHP 8.4 / Laravel 13 (patrón MVC) |
| Base de datos | MySQL 8.0 (base oficial `world`) |
| Contenedores | Docker + Docker Compose |
| Frontend | Blade, CSS y JavaScript nativo (sin dependencias externas) |

## Inicio rápido

```bash
git clone <url-del-repositorio> prueba-analista
cd prueba-analista
docker compose up --build -d
```

Abrir <http://localhost:8000>. La base de datos se importa automáticamente en el
primer arranque desde `database/world.sql` (dump oficial de MySQL).

Para detener: `docker compose down`. Para borrar la base y volver a importarla:
`docker compose down -v` (esto elimina el volumen `db_data`).

## Documentación

- [Instalación y ejecución](docs/instalacion.md)
- [Base de datos y diagrama ER](docs/base-de-datos.md)

## Estructura principal

```
app/Http/Controllers/   Controladores (países y ciudades)
app/Models/             Modelos Eloquent (Country, City)
database/world.sql      Dump oficial de la base World
docker/                 Configuración de Apache y entrypoint del contenedor
docs/                   Documentación técnica
public/css|js           Estilos responsivos y consumo AJAX
resources/views/        Única pantalla (layout + formulario + resultados)
```

## Control de versiones

El historial de commits está organizado por partes funcionales: configuración,
modelos, controladores/rutas, interfaz, contenedores y documentación.
