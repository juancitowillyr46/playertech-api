# PlayerTech

PlayerTech es una API SaaS multi-tenant para academias de fútbol.

## Descripción

La plataforma está pensada para gestionar academias, sedes, categorías, equipos, jugadores, acudientes, matrículas y pagos desde una base técnica sólida, segura y evolutiva.

## Requisitos

- Docker
- Docker Compose
- PHP 8.4
- Symfony 7.4
- MySQL 8+

## Estructura

- `app/` código fuente Symfony
- `docker/` infraestructura local
- `postman/` colección Postman y entorno local
- `specs/` documentación funcional y técnica
- `docs/architecture/` ADRs, auditorías y decisiones técnicas
- `docs/contracts/` índice de contratos HTTP y sincronización con frontend

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

## Consumo de la API

- `docs/contracts/api-reference.md` es la referencia HTTP operativa principal.
- `postman/` contiene la colección y el entorno para validación manual rápida.
- `docs/contracts/api-reference.md` agrupa el índice de contratos vigentes.
- `docs/flows/` concentra los flujos funcionales específicos.
- `docs/domain/02-domains.md` queda como legado conceptual; la versión canónica es `docs/domains/domain-overview.md`.

## Enfoque de trabajo

- Primero se construye la base técnica.
- Todo desarrollo y ejecución se realiza dentro de contenedores.
- Los módulos de negocio se implementan de forma incremental cuando la foundation esté cerrada.

## Documentación

- `docs/product/product-vision.md`
- `docs/architecture/architecture-overview.md`
- `docs/domains/domain-overview.md`
- `docs/security/security-overview.md`
- `docs/contracts/api-principles.md`
- `docs/database/database-standards.md`
- `docs/architecture/guides/project-setup-guide.md`
- `docs/architecture/guides/testing-strategy.md`
- `docs/architecture/guides/execution-order-guide.md`
- `docs/architecture/guides/user-story-rebuild-guide.md`
- `specs/14-current-state.md`
- `docs/contracts/api-reference.md`
- `docs/architecture/policies/sdd-policy.md`
- `docs/architecture/templates/change-template-light.md`
- `docs/architecture/templates/change-template-full.md`
- `docs/architecture/audits/SDD-backend-audit.md`
- `docs/contracts/api-reference.md`
