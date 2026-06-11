# Current State

Este documento registra el estado actual de la base tecnica, su trazabilidad y el criterio para continuar la siguiente iteracion.

---

# Implemented Foundation

La base tecnica actual incluye:

* README de entrada del repositorio.
* Estructura inicial del proyecto.
* Contenedores Docker para app y MySQL.
* Runtime minimo de Symfony.
* Endpoint de salud en `/api/v1/health`.
* Configuracion base de Doctrine, Security, JWT y OpenAPI.
* Primer commit de foundation.

---

# Traceability

| Item | Type | Status | Commit | Notes |
| ---- | ---- | ------ | ------ | ----- |
| README base | Documentation | Done | `b40e311` | Entrada principal del repositorio |
| Foundation bootstrap | Technical Enabler | Done | `7c3de8e` | Symfony, Docker, health endpoint y base runtime |
| Health endpoint | Functional | Done | `7c3de8e` | `/api/v1/health` responde JSON |
| Docker stack | Non-Functional / Technical Enabler | Done | `7c3de8e` | Ejecucion dentro de contenedores |
| Identity auth module refactor | Technical Enabler | Done | - | Login resuelto por Symfony Security `json_login`; `/me`, handlers JWT y entidad movidos a `Modules/Identity` |
| Identity technical user model | Technical Enabler | Done | - | `AccountUser` usa Doctrine attributes y GUID string para acelerar la foundation sin perder compatibilidad |
| Shared health endpoint | Technical Enabler | Done | - | HealthController moved to Shared/Presentation/Http |
| Legacy folder cleanup | Technical Enabler | Done | - | Eliminados `src/Command`, `src/Controller`, `src/Entity`, `src/EventSubscriber` y `src/Security` heredados |
| Root platform command | Technical Enabler | Done | - | `app:user:create-root` registra usuarios `ROLE_ROOT` sin tenant |
| UUID storage conversion | Technical Enabler | Done | - | La tabla `users` paso a UUID legible como string (`CHAR(36)`) |
| Auth JWT | Functional | Pending | - | Proximo paso de implementacion |
| Tenant context | Non-Functional / Architectural Constraint | Pending | - | Requerido para aislamiento multi-tenant |

---

# Commit References

* `7c3de8e` - `chore: bootstrap PlayerTech API foundation`
* `b40e311` - `docs: improve project README`

---

# Requirement Classification

## Functional

Capacidades visibles para el usuario o consumidor de la API.

Ejemplos:

* Health endpoint.
* Login JWT.
* Crear academia.
* Listar usuarios.

## Non-Functional

Condiciones de calidad, operacion o arquitectura.

Ejemplos:

* Docker obligatorio.
* Multi-tenant por `academy_id`.
* Soft delete.
* Auditoria.
* Stateless JWT.

## Technical Enabler

Piezas de infraestructura o runtime que habilitan la base funcional.

Ejemplos:

* `composer.json`.
* `Dockerfile`.
* `docker-compose.yml`.
* `Kernel.php`.
* Routing base.

---

# Next Steps

1. Cerrar autenticacion real.
2. Implementar tenant context.
3. Definir y generar migraciones base.
4. Preparar la primera entidad fundacional.
5. Mantener trazabilidad por commit en cada iteracion.

---

# Working Rule

Cada cambio importante debera dejar trazabilidad en este documento o en el orden de ejecucion, con referencia al commit correspondiente y clasificacion funcional o no funcional.

---

# Current Iteration Notes

* Auth/JWT reordenado a `Modules/Identity`.
* El login no usa AuthController; se ejecuta desde el firewall `json_login`.
* `AccountUser` queda como entidad tecnica acoplada al framework por pragmatismo.
* El almacenamiento UUID ya esta normalizado como string legible en la tabla `users`.
* Pendiente validar en runtime el login y el endpoint `/auth/me`.
