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
5. TenantContext y tenant filter.
6. Auditoria y soft delete.
7. OpenAPI y formato de errores.

---

# Phase 2 - Core Modules

1. Academy.
2. Identity.
3. Venues.
4. Categories.
5. Teams.
6. Guardians.
7. Players.
8. Memberships.
9. Payment concepts.
10. Payments.
11. Payment evidences.

---

# Phase 3 - Business Flow Validation

1. Flujo crear academia.
2. Flujo crear usuario.
3. Flujo crear sede.
4. Flujo crear categoria.
5. Flujo crear equipo.
6. Flujo registrar acudiente.
7. Flujo registrar jugador.
8. Flujo crear matricula.
9. Flujo registrar pago.
10. Flujo consultar historial y dashboard.

---

# Phase 4 - User Stories

Cuando la base tecnica y los modulos fundacionales esten listos, se implementaran las HUs en el orden de dependencia real.

---

# Execution Rules

* No iniciar HUs antes de cerrar foundation.
* No agregar tecnologia sin justificacion funcional.
* No reordenar modulos sin revisar dependencias.
* No romper contratos ya definidos.
* Mantener trazabilidad por commit en cada cambio importante.
* Aceptar excepciones tecnicas acotadas en Identity cuando reduzcan friccion de la foundation sin romper el desacople de los modulos de negocio.



