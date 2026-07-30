# HU-005 - Reemplazar documento del jugador

**File:** `HU-005-replace-player-document.md`

## Historia de Usuario

Como **Owner/Admin del tenant**,
quiero reemplazar un documento existente de un jugador por un archivo más reciente,
para que el expediente digital contenga la versión más actual disponible.

## Contexto de negocio

A veces el acudiente envía primero una foto de baja calidad y luego una versión escaneada. También puede suceder que un documento sea renovado, corregido o sustituido porque la carga anterior estaba incompleta.

El Owner/Admin necesita actualizar el archivo almacenado sin generar confusión sobre cuál es la versión vigente.

## Decisión MVP

Para el MVP, reemplazar un documento actualiza el archivo activo y sus metadatos.

El historial completo de versiones queda fuera del alcance inicial.

La auditoría debe identificar:

- Quién realizó el reemplazo.
- Cuándo se realizó.
- Qué documento fue actualizado.

## Alcance

Esta historia incluye:

- Reemplazar el archivo físico de un documento existente.
- Actualizar opcionalmente las observaciones.
- Actualizar opcionalmente el tipo de documento.
- Actualizar los metadatos del archivo.
- Registrar el usuario y la fecha de modificación.
- Eliminar el archivo físico anterior después de un reemplazo exitoso.

Esta historia no incluye:

- Historial completo de versiones.
- Restaurar versiones anteriores.
- Comparar dos versiones de archivo.
- Detección automática de duplicados.

## Precondiciones

- El usuario está autenticado.
- El usuario autenticado es Owner/Admin del tenant.
- El jugador existe.
- El documento existe.
- El documento pertenece al jugador.
- El jugador pertenece al tenant autenticado.
- El archivo de reemplazo es válido.

## Flujo principal

1. El Owner/Admin abre los documentos del jugador.
2. El Owner/Admin selecciona **Reemplazar** sobre un documento.
3. El sistema muestra la información actual del documento.
4. El Owner/Admin selecciona un nuevo archivo.
5. El Owner/Admin opcionalmente actualiza el tipo de documento u observaciones.
6. El Owner/Admin confirma la operación.
7. El sistema valida el archivo de reemplazo.
8. El sistema almacena el nuevo archivo.
9. El sistema actualiza los metadatos del documento.
10. El sistema elimina el archivo físico anterior.
11. El sistema confirma el reemplazo.

## Criterios de aceptación

### AC-001 - Reemplazar documento exitosamente

**Dado** que un documento existente pertenece al tenant
**Y** que el Owner/Admin selecciona un archivo válido de reemplazo
**Cuando** se confirma el reemplazo
**Entonces** el sistema debe almacenar el nuevo archivo
**Y** actualizar el registro existente
**Y** conservar el mismo identificador del documento.

### AC-002 - Actualizar metadatos

**Dado** que el documento fue reemplazado
**Cuando** el reemplazo tiene éxito
**Entonces** el sistema debe actualizar:

- Nombre original.
- MIME type.
- Tamaño del archivo.
- Referencia interna de almacenamiento.
- Fecha de actualización.
- Usuario que actualizó.

### AC-003 - Mantener asociación con el jugador

**Dado** que el documento fue reemplazado
**Cuando** la operación tiene éxito
**Entonces** el documento debe permanecer asociado al mismo jugador.

### AC-004 - Eliminar archivo anterior

**Dado** que el nuevo archivo y sus metadatos fueron almacenados correctamente
**Cuando** el proceso de reemplazo finaliza
**Entonces** el archivo físico anterior debe eliminarse o programarse para limpieza.

### AC-005 - Conservar archivo anterior ante fallas

**Dado** que el nuevo archivo no puede almacenarse
**O** que los metadatos no pueden actualizarse
**Cuando** el reemplazo falla
**Entonces** el documento existente y el archivo anterior deben permanecer disponibles.

### AC-006 - Rechazar archivo no soportado

**Dado** que el archivo de reemplazo tiene un formato no soportado
**Cuando** el Owner/Admin confirma el reemplazo
**Entonces** el sistema debe rechazar la operación
**Y** preservar el documento actual.

### AC-007 - Rechazar archivo que excede el tamaño máximo

**Dado** que el archivo seleccionado excede el tamaño permitido
**Cuando** se envía el reemplazo
**Entonces** el sistema debe rechazar la operación
**Y** preservar el documento actual.

### AC-008 - Aislamiento por tenant

**Dado** que el documento pertenece a otro tenant
**Cuando** el usuario intenta reemplazarlo
**Entonces** el sistema debe denegar la operación.

### AC-009 - Registrar información de auditoría

**Dado** que el reemplazo tiene éxito
**Cuando** el documento se actualiza
**Entonces** el sistema debe registrar quién lo reemplazó y cuándo.

## Reglas de negocio

- El reemplazo actualiza el registro existente.
- El identificador del documento debe permanecer igual.
- La asociación con el jugador debe permanecer igual.
- Debe generarse un nuevo identificador interno de almacenamiento.
- El archivo anterior no debe eliminarse antes de guardar y persistir el nuevo de forma segura.
- La operación debe evitar archivos huérfanos.
- El versionado completo queda fuera del MVP.
- El usuario autenticado debe registrarse como actualizador.

## Contrato API sugerido

```http
POST /api/v1/players/{playerId}/documents/{documentId}/replace
Content-Type: multipart/form-data
```

Usar `POST` para una acción multipart de reemplazo es aceptable cuando la operación representa una acción de dominio específica.

### Campos de request

```text
file: binary
documentType: Optional
observations: Optional
```

## Escenarios de error

- Jugador no encontrado.
- Documento no encontrado.
- El documento no pertenece al jugador.
- Acceso al tenant denegado.
- Archivo inválido.
- Error de almacenamiento.
- Error de persistencia de metadatos.
- Error limpiando el archivo anterior.

Un error de limpieza posterior a un reemplazo exitoso no debería invalidar necesariamente la operación del usuario, pero sí debe registrarse y reintentarse.

## Requisitos de auditoría

Registrar:

- Identificador del documento.
- Identificador del jugador.
- Nombre anterior del archivo.
- Nuevo nombre del archivo.
- Usuario que actualizó.
- Fecha de actualización.

## Definition of Done

- Los documentos pueden reemplazarse.
- El identificador del documento permanece igual.
- Los reemplazos fallidos preservan el documento anterior.
- Los reemplazos exitosos limpian el archivo previo.
- Se actualizan los metadatos de auditoría.
- Se aplica aislamiento por tenant.
- Existen pruebas automatizadas de éxito, rollback y autorización.
- La documentación de la API está actualizada.
