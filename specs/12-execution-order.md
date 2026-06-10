# 12-execution-order.md

# Execution Order

Este orden está pensado para construir la base técnica primero y evitar cabos sueltos antes de trabajar historias de usuario.

---

# Phase 0 - Documentation Alignment

1. Alinear `docs` y `specs` como fuente de verdad.
2. Confirmar arquitectura, seguridad, base de datos y API.
3. Resolver ambigüedades antes de escribir código.

---

# Phase 1 - Foundation

1. Estructura base del proyecto Symfony.
2. Docker y entorno local.
3. Configuración de Doctrine y migraciones.
4. JWT y Symfony Security.
5. TenantContext y tenant filter.
6. Auditoría y soft delete.
7. OpenAPI y formato de errores.

---

# Phase 2 - Core Modules

1. Academy.
2. Auth.
3. Users.
4. Venues.
5. Categories.
6. Teams.
7. Guardians.
8. Players.
9. Memberships.
10. Payment concepts.
11. Payments.
12. Payment evidences.

---

# Phase 3 - Business Flow Validation

1. Flujo crear academia.
2. Flujo crear usuario.
3. Flujo crear sede.
4. Flujo crear categoría.
5. Flujo crear equipo.
6. Flujo registrar acudiente.
7. Flujo registrar jugador.
8. Flujo crear matrícula.
9. Flujo registrar pago.
10. Flujo consultar historial y dashboard.

---

# Phase 4 - User Stories

Cuando la base técnica y los módulos fundacionales estén listos, se implementarán las HUs en el orden de dependencia real.

---

# Execution Rules

* No iniciar HUs antes de cerrar foundation.
* No agregar tecnología sin justificación funcional.
* No reordenar módulos sin revisar dependencias.
* No romper contratos ya definidos.

