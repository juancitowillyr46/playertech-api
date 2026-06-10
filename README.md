# PlayerTech

PlayerTech es una plataforma SaaS multi-tenant para academias de fútbol.

## Requisitos

- Docker
- Docker Compose
- PHP 8.4
- Symfony 7.4
- MySQL 8+

## Estructura

- `app/` código fuente Symfony
- `docker/` infraestructura local
- `http/` colecciones HTTP
- `specs/` documentación funcional y técnica

## Ejecución

```bash
docker compose -f docker/docker-compose.yml up -d --build
```

```bash
cd app
composer install
```

```bash
php bin/console
```

## Enfoque de trabajo

- Primero se construye la base técnica.
- Todo desarrollo y ejecución se realiza dentro de contenedores.
- Los módulos de negocio se implementan de forma incremental cuando la foundation esté cerrada.

## Documentación

- `specs/00-product.md`
- `specs/01-arquitecture.md`
- `specs/02-domains.md`
- `specs/03-security.md`
- `specs/04-api.md`
- `specs/06-database.md`
- `specs/10-project-setup.md`
- `specs/11-testing-strategy.md`
- `specs/12-execution-order.md`
- `specs/13-user-story-rebuild-guide.md`
