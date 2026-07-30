# HU-004 - Descargar documento del jugador

**File:** `HU-004-download-player-document.md`

## Historia de Usuario

Como **Owner/Admin del tenant**,
quiero descargar un documento de un jugador,
para imprimirlo, enviarlo o utilizarlo en un proceso administrativo externo.

## Contexto de negocio

La academia puede necesitar entregar documentos a organizadores de torneos, imprimir copias, enviarlas por correo o almacenarlas temporalmente en un dispositivo autorizado.

El sistema debe permitir descargar el archivo original de forma segura.

## Alcance

Esta historia incluye:

- Descargar un documento.
- Preservar el nombre original del archivo.
- Retornar el tipo de contenido correcto.
- Validar la pertenencia al tenant.
- Servir el archivo a través del backend.

Esta historia no incluye:

- Enlaces públicos.
- Envío por correo.
- Impresión directa desde la aplicación.
- Combinar múltiples documentos en un solo archivo.
- Descargar el expediente completo del jugador como ZIP o PDF.

## Precondiciones

- El usuario está autenticado.
- El usuario autenticado es Owner/Admin del tenant.
- El documento existe.
- El documento pertenece a un jugador del mismo tenant.
- El archivo físico existe en almacenamiento.

## Flujo principal

1. El Owner/Admin accede a los documentos del jugador.
2. El Owner/Admin selecciona **Descargar**.
3. El sistema valida el acceso.
4. El sistema recupera el archivo almacenado.
5. El sistema retorna el archivo como adjunto.
6. El navegador inicia la descarga usando el nombre original.

## Criterios de aceptación

### AC-001 - Descargar documento existente

**Dado** que un documento existente pertenece al tenant
**Cuando** el Owner/Admin selecciona **Descargar**
**Entonces** el sistema debe retornar el archivo original como un adjunto descargable.

### AC-002 - Preservar nombre original

**Dado** que el documento almacenado tiene un nombre original
**Cuando** el archivo se descarga
**Entonces** el archivo descargado debe usar ese nombre original.

### AC-003 - Tipo de contenido correcto

**Dado** que el archivo se descarga
**Cuando** la respuesta es generada
**Entonces** el sistema debe retornar el MIME type validado.

### AC-004 - Disposición attachment

**Dado** que el documento se solicita para descarga
**Cuando** el sistema retorna el archivo
**Entonces** la respuesta debe usar `attachment`.

### AC-005 - Aislamiento por tenant

**Dado** que el documento pertenece a otro tenant
**Cuando** el usuario intenta descargarlo
**Entonces** el sistema debe denegar el acceso
**Y** no debe retornar contenido del archivo.

### AC-006 - El documento no pertenece al jugador

**Dado** que el documento existe
**Pero** no está asociado al jugador de la solicitud
**Cuando** se solicita la descarga
**Entonces** el sistema debe rechazar la solicitud.

### AC-007 - Archivo físico faltante

**Dado** que los metadatos del documento existen
**Pero** el archivo no existe en el almacenamiento
**Cuando** se solicita la descarga
**Entonces** el sistema debe retornar un error controlado
**Y** registrar la inconsistencia de almacenamiento.

## Reglas de negocio

- La descarga siempre debe estar autorizada.
- El tenant debe derivarse del usuario autenticado.
- El documento debe pertenecer al jugador indicado en la ruta.
- El nombre original puede usarse para la descarga, pero debe sanitizarse.
- El nombre interno de almacenamiento nunca debe exponerse.
- Los documentos eliminados no pueden descargarse.

## Contrato API sugerido

```http
GET /api/v1/players/{playerId}/documents/{documentId}/download
```

### Headers sugeridos

```http
Content-Type: application/pdf
Content-Disposition: attachment; filename="mateo-identity-card.pdf"
X-Content-Type-Options: nosniff
```

## Escenarios de error

- Jugador no encontrado.
- Documento no encontrado.
- El documento pertenece a otro jugador.
- Acceso al tenant denegado.
- Archivo físico faltante.
- Proveedor de almacenamiento no disponible.

## Requisitos de auditoría

En el MVP se puede registrar la descarga mediante logs de aplicación.

Un futuro componente de auditoría podría persistir:

- Usuario que descargó el archivo.
- Identificador del documento.
- Identificador del jugador.
- Fecha de descarga.
- Dirección IP.
- User agent.

## Definition of Done

- Los usuarios autorizados pueden descargar documentos.
- Los nombres originales se preservan de forma segura.
- Se aplica aislamiento por tenant.
- Los archivos faltantes se manejan correctamente.
- No se exponen rutas internas de almacenamiento.
- Existen pruebas automatizadas de acceso y descarga exitosa.
- La documentación de la API está actualizada.
