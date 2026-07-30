# HU-003 - Visualizar documento del jugador

**File:** `HU-003-view-player-document.md`

## Historia de Usuario

Como **Owner/Admin del tenant**,
quiero visualizar el documento de un jugador desde la aplicación,
para consultar su contenido sin necesidad de descargarlo primero.

## Contexto de negocio

Durante un torneo o un proceso administrativo, la academia puede necesitar mostrar rápidamente un documento de identidad, registro civil, carné u otro soporte del jugador.

El sistema debe permitir abrir documentos soportados de forma segura desde el expediente digital del jugador.

## Alcance

Esta historia incluye:

- Abrir un documento soportado desde la aplicación.
- Servir el documento mediante un endpoint autorizado del backend.
- Mostrar archivos PDF.
- Mostrar archivos de imagen soportados.
- Mantener la aislamiento por tenant.
- Retornar el tipo de contenido correcto.

Esta historia no incluye:

- Editar el documento.
- Descargar el documento de forma explícita.
- OCR.
- Generar miniaturas.
- Enlaces públicos al documento.

## Precondiciones

- El usuario está autenticado.
- El usuario autenticado es Owner/Admin del tenant.
- El jugador existe.
- El documento existe.
- El jugador y el documento pertenecen al tenant autenticado.
- El archivo existe en el almacenamiento.

## Flujo principal

1. El Owner/Admin abre la sección de documentos del jugador.
2. El Owner/Admin selecciona **Visualizar** sobre un documento.
3. El sistema valida el acceso.
4. El sistema recupera el archivo desde el almacenamiento.
5. El sistema retorna el archivo con el tipo de contenido adecuado.
6. La interfaz muestra el PDF o la imagen en un visor, modal o pestaña del navegador.

## Criterios de aceptación

### AC-001 - Visualizar documento PDF

**Dado** que el documento es un PDF
**Y** que el Owner/Admin tiene acceso al jugador
**Cuando** el Owner/Admin selecciona **Visualizar**
**Entonces** el sistema debe retornar el PDF
**Y** la interfaz debe mostrarlo con un visor compatible.

### AC-002 - Visualizar documento de imagen

**Dado** que el documento es una imagen soportada
**Cuando** el Owner/Admin selecciona **Visualizar**
**Entonces** el sistema debe retornar la imagen
**Y** la interfaz debe mostrarla sin forzar descarga.

### AC-003 - Tipo de contenido correcto

**Dado** que se solicita un documento
**Cuando** el archivo es retornado
**Entonces** la respuesta debe incluir el header `Content-Type` correcto.

### AC-004 - Disposición inline

**Dado** que el formato del documento soporta vista previa
**Cuando** el archivo es retornado
**Entonces** la respuesta debe usar una disposición `inline`.

### AC-005 - Aislamiento por tenant

**Dado** que el documento pertenece a otro tenant
**Cuando** el usuario intenta visualizarlo
**Entonces** el sistema debe denegar el acceso
**Y** no debe exponer metadatos ni contenido del archivo.

### AC-006 - Documento no encontrado

**Dado** que el identificador del documento no existe
**Cuando** el Owner/Admin lo solicita
**Entonces** el sistema debe retornar una respuesta de documento no encontrado.

### AC-007 - Archivo faltante en almacenamiento

**Dado** que los metadatos del documento existen
**Pero** el archivo físico no está en el almacenamiento
**Cuando** el Owner/Admin intenta visualizarlo
**Entonces** el sistema debe retornar un error controlado
**Y** registrar la inconsistencia en los logs.

### AC-008 - No exponer ruta pública

**Dado** que se muestra un documento
**Cuando** la aplicación recupera el archivo
**Entonces** la ruta física de almacenamiento no debe exponerse al cliente.

## Reglas de negocio

- Los documentos solo pueden servirse mediante un endpoint autorizado del backend.
- El backend debe validar el tenant antes de recuperar el archivo.
- Las rutas de almacenamiento deben permanecer internas.
- El navegador no debe recibir credenciales permanentes de almacenamiento.
- Solo los documentos activos pueden visualizarse.
- La respuesta debe prevenir interpretación insegura del MIME type.

## Contrato API sugerido

```http
GET /api/v1/players/{playerId}/documents/{documentId}/view
```

### Headers sugeridos

```http
Content-Type: application/pdf
Content-Disposition: inline; filename="mateo-identity-card.pdf"
X-Content-Type-Options: nosniff
```

## Escenarios de error

- Jugador no encontrado.
- Documento no encontrado.
- El documento no pertenece al jugador.
- El jugador pertenece a otro tenant.
- El archivo no existe en almacenamiento.
- El proveedor de almacenamiento no está disponible.
- El formato no se puede previsualizar.

## Consideraciones de seguridad

- No exponer URLs públicas directas del almacenamiento.
- No confiar en el MIME type solicitado por el cliente.
- Usar el MIME type validado en la carga.
- Sanitizar el nombre de archivo incluido en los headers.
- Considerar URLs firmadas de vida corta solo si la arquitectura futura lo exige.

## Definition of Done

- Los PDFs se pueden mostrar inline.
- Las imágenes se pueden mostrar inline.
- El acceso está restringido por tenant.
- No se exponen rutas internas de almacenamiento.
- Los escenarios de archivo faltante están controlados.
- Existen pruebas de autorización automatizadas.
- La documentación de la API está actualizada.
