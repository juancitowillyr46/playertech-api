# Investigación: Catálogo compartido de parentescos

## Decisión 1: Ubicación en `Shared`

**Decisión**: Ubicar el catálogo en `app/src/Shared/Domain/Relationship/Relationship.php` y exponerlo desde una presentación HTTP compartida.

**Justificación**: Los parentescos son utilizados por más de una funcionalidad del sistema. Su identidad no depende de un agregado particular ni tiene un ciclo de vida propio en esta iteración.

## Decisión 2: Catálogo estático en la primera iteración

**Decisión**: Mantener los parentescos como catálogo estático y global, con valores técnicos estables y etiquetas visibles.

**Justificación**: El alcance aprobado no incluye persistencia, administración ni configuración tenant. Un enum permite centralizar valores sin introducir una migración prematura.

## Decisión 3: Endpoint neutral no paginado

**Decisión**: Publicar `GET /api/v1/academy/relationships/options` como endpoint oficial, con respuesta `data` y `meta: {}`.

**Justificación**: La ruta no pertenece a un módulo concreto y el catálogo contiene ocho opciones conocidas. El patrón coincide con otros endpoints `options` existentes.

## Decisión 4: Seguridad y contexto tenant

**Decisión**: Requerir usuario autenticado con contexto válido de academia. No se exige exclusivamente `Owner/Admin` y no se recibe `academy_id` desde el cliente.

**Justificación**: El catálogo no contiene datos sensibles, pero la ruta pertenece al área autenticada de academia y debe respetar la separación de `ROLE_ROOT` frente a usuarios tenant.

## Decisión 5: Compatibilidad

**Decisión**: La ruta neutral es el contrato oficial; cualquier ruta previa debe documentarse como legado y migrarse antes de retirarse.

**Justificación**: Evita mantener dos contratos sin necesidad y mantiene explícito el plan de migración.
