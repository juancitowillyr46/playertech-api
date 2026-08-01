# Investigación: Catálogo compartido de tipos de documento

## Decisión 1: Ubicación en `Shared`

**Decisión**: Ubicar el catálogo en `app/src/Shared/Domain/Document/DocumentType.php` y exponerlo desde una presentación HTTP compartida.

**Justificación**: Los tipos de documento son utilizados por Player, LegalGuardian, importación y gestión documental. Su identidad no depende de un agregado particular ni tiene un ciclo de vida propio en esta iteración.

**Alternativas consideradas**:

- Mantenerlo en Player: descartado porque convertiría a Player en propietario de un concepto utilizado por otros módulos.
- Crear un módulo `Document`: descartado porque esta feature no administra documentos físicos ni tiene un agregado documental transversal.
- Crear una tabla maestra: diferido; requiere decidir si el catálogo será global o configurable por academia.

## Decisión 2: Catálogo estático en la primera iteración

**Decisión**: Mantener los seis tipos como catálogo estático y global, con valores técnicos estables y etiquetas visibles.

**Justificación**: El alcance aprobado no incluye persistencia, administración ni configuración tenant. Un enum permite centralizar valores sin introducir una migración prematura.

**Alternativas consideradas**:

- Tabla `document_types`: diferida a una HU futura.
- Catálogo duplicado por consumidor: rechazado por riesgo de divergencia.

## Decisión 3: Endpoint neutral no paginado

**Decisión**: Publicar `GET /api/v1/academy/document-types/options` como endpoint oficial, con respuesta `data` y `meta: {}`.

**Justificación**: La ruta no pertenece a Player y el catálogo contiene seis opciones conocidas. El patrón coincide con `categories/options` y `staff/options`.

**Alternativas consideradas**:

- Mantener la ruta bajo `players`: descartado por alcance transversal.
- Paginación: descartada para un catálogo pequeño y cerrado; ADR-004 aplica a listados de colección, mientras que los endpoints `options` existentes son colecciones livianas no paginadas.
- Mantener alias permanente: descartado; la decisión aprobada es reemplazar la ruta anterior y migrar consumidores.

## Decisión 4: Seguridad y contexto tenant

**Decisión**: Requerir usuario autenticado con contexto válido de academia. No se exige exclusivamente `Owner/Admin` y no se recibe `academy_id` desde el cliente.

**Justificación**: El catálogo no contiene datos sensibles, pero la ruta pertenece al área autenticada de academia y debe respetar la separación de `ROLE_ROOT` frente a usuarios tenant.

**Alternativas consideradas**:

- Endpoint público: rechazado porque rompería la frontera de la API tenant.
- Restringir a `Owner/Admin`: descartado por decisión funcional; el catálogo puede ser utilizado por cualquier usuario tenant autenticado.

## Decisión 5: Compatibilidad

**Decisión**: La ruta neutral es el contrato oficial; la ruta anterior debe eliminarse después de migrar los consumidores internos.

**Justificación**: La ruta actual ya fue creada bajo Player, pero su semántica es transversal. La migración debe quedar explícita para evitar conservar dos contratos sin necesidad.

**Alternativas consideradas**:

- Cambio silencioso sin documentar: rechazado por el Principio IX de Compatibilidad.
- Alias temporal: no elegido en la aclaración funcional.
