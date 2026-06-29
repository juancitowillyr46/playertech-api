# Execution Order

Este orden esta pensado para construir la base tecnica primero y evitar cabos sueltos antes de trabajar historias de usuario.

---

# Traceability Rule

Antes de iniciar cada nueva iteracion, revisar:

* [`specs/14-current-state.md`](./14-current-state.md)
* el commit mas reciente
* la clasificacion funcional o no funcional del cambio a realizar

Cada cambio relevante debe dejar una referencia clara en el estado actual.

---

# Phase 0 - Documentation Alignment

1. Alinear `docs` y `specs` como fuente de verdad.
2. Confirmar arquitectura, seguridad, base de datos y API.
3. Resolver ambiguedades antes de escribir codigo.
4. Registrar el estado actual y la trazabilidad de commits.

---

# Phase 1 - Foundation

1. Estructura base del proyecto Symfony.
2. Docker y entorno local.
3. Configuracion de Doctrine y migraciones.
4. JWT y Symfony Security.
5. Separacion de contexto plataforma (`ROLE_ROOT`) y contexto tenant.
6. XML mapping puro con Value Objects tipados y Custom Types para IDs UUID.
7. CQRS en Application Layer con comandos, queries y handlers.
8. TenantContext y tenant filter.
9. Auditoria y soft delete.
10. OpenAPI y formato de errores.

---

# Phase 2 - Core Modules

1. Academy.
2. Identity.
3. Venues.
4. Categories.
5. Players.
6. Teams.
7. Guardians.
8. Memberships.
9. Payment concepts.
10. Payments.
11. Payment evidences.

---

# Phase 3 - Tenant Onboarding

1. Alta simplificada de tenant.
2. Activación por correo del tenant.
3. Primer acceso del owner/admin del tenant.

---

# Phase 4 - Business Flow Validation

1. Flujo crear academia.
2. Flujo crear usuario.
3. Flujo crear sede.
4. Flujo crear categoria.
5. Flujo registrar jugador.
6. Flujo registrar acudiente.
7. Flujo crear equipo.
8. Flujo crear matricula.
9. Flujo registrar pago.
10. Flujo consultar historial y dashboard.

---

# Phase 5 - User Stories

Cuando la base tecnica y los modulos fundacionales esten listos, se implementaran las HUs en el orden de dependencia real.

---

# Execution Rules

* No iniciar HUs antes de cerrar foundation.
* No agregar tecnologia sin justificacion funcional.
* No reordenar modulos sin revisar dependencias.
* No romper contratos ya definidos.
* Mantener trazabilidad por commit en cada cambio importante.
* Aceptar excepciones tecnicas acotadas en Identity cuando reduzcan friccion de la foundation sin romper el desacople de los modulos de negocio.
* `ROLE_ROOT` opera sin tenant (`academy_id = null`) y no debe ser tratado como usuario de academia.
* Todo usuario tenant debe tener `academy_id` y sus operaciones de negocio deben quedar aisladas por tenant.
* `Academy` se toma como referencia de XML Mapping puro con `AcademyId` como custom type y VOs compartidos como embeddables.
* La capa HTTP debe ser delgada y delegar en handlers de Application Layer.
