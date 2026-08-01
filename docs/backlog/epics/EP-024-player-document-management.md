# EP-024 - Gestión documental de jugadores

## Objetivo

Como administradora de la academia,
quiero almacenar y administrar los documentos personales de cada jugador,
para disponer de ellos de forma inmediata cuando sean solicitados en entrenamientos, competencias, procesos administrativos o por entidades organizadoras.

---

## Problema de negocio

Actualmente los documentos de los jugadores se solicitan por WhatsApp o de manera física.

Esto genera problemas como:

- El padre olvida llevar el documento al torneo.
- El documento fue enviado hace meses y es difícil encontrarlo nuevamente.
- Existen múltiples versiones del mismo documento.
- La administradora debe volver a solicitar la información.
- Se pierden copias físicas.
- No existe una fuente oficial donde consultar la documentación del jugador.

---

## Objetivos del MVP

Permitir que cada jugador tenga un expediente documental digital donde la academia pueda consultar y administrar los documentos necesarios para su participación en actividades deportivas y administrativas.

El sistema deberá permitir:

- Registrar documentos asociados al jugador.
- Visualizar documentos almacenados.
- Descargar documentos.
- Reemplazar documentos existentes.
- Eliminar documentos cargados por error.

No hace parte del MVP:

- OCR de documentos.
- Firma electrónica.
- Validación automática de vencimientos.
- Reconocimiento automático del tipo de documento.
- Solicitud automática de documentos a los acudientes.
- Historial de versiones.

---

## Valor de negocio

La gestión documental elimina la dependencia de conversaciones de WhatsApp y documentos físicos, permitiendo que la academia tenga acceso inmediato a la documentación de cada jugador durante competencias, inscripciones y procesos administrativos.

Además, constituye la base para futuras funcionalidades como expedientes digitales, carga de documentos por parte de los acudientes, alertas de vencimiento y validaciones automáticas para torneos.

---

## Alcance funcional

Cada jugador podrá tener asociados múltiples documentos.

Ejemplos:

- Registro Civil.
- Tarjeta de Identidad.
- Cédula de Ciudadanía.
- Pasaporte.
- Documento de identidad del acudiente.
- EPS.
- Seguro deportivo.
- Carné de inscripción a torneos.
- Certificado médico.

Inicialmente el catálogo de tipos de documentos será administrado por el sistema y podrá evolucionar posteriormente hacia una configuración por academia.

Para los documentos adjuntos, los valores y etiquetas oficiales provienen de `EP-025 - Catálogos maestros compartidos` y su contrato `GET /api/v1/academy/document-types/options`.

---

## Modelo conceptual

Player

└── Documents

- Document
  - id
  - playerId
  - documentType
  - filename
  - storagePath
  - mimeType
  - size
  - uploadedAt
  - uploadedBy
  - observations

---

## Flujo principal

Jugador

↓

Pestaña **Documentos**

↓

Adjuntar documento

↓

Seleccionar tipo

↓

Seleccionar archivo

↓

Guardar

↓

Documento disponible para consulta inmediata.

---

## Historias de Usuario

- HU-001 – Consultar documentos del jugador.
- HU-002 – Adjuntar documento al jugador.
- HU-003 – Visualizar documento.
- HU-004 – Descargar documento.
- HU-005 – Reemplazar documento.
- HU-006 – Eliminar documento.
- HU-007 – Validar archivos permitidos.
- HU-008 – Mostrar estado documental del jugador.

## Historias incorporadas en esta iteración

- HU-001 – Consultar documentos del jugador.
- HU-002 – Adjuntar documento al jugador.
- HU-003 – Visualizar documento.
- HU-004 – Descargar documento.
- HU-005 – Reemplazar documento.

---

## Consideraciones técnicas

- Los archivos deberán almacenarse fuera del directorio público de la aplicación.
- El acceso a los documentos deberá realizarse mediante autorización del backend.
- Los metadatos del documento se almacenarán en la base de datos.
- El sistema deberá mantener la auditoría de creación y actualización de cada documento.
- La implementación deberá permitir sustituir el mecanismo de almacenamiento (local, S3 u otro) sin afectar el dominio.

---

## Funcionalidades futuras

- Carga de documentos por parte del acudiente.
- Solicitud de documentos mediante enlaces seguros.
- Historial de versiones.
- Fechas de vencimiento.
- Alertas automáticas.
- Expediente digital en PDF.
- Compartición temporal mediante enlaces.
- OCR para extracción automática de información.
- Validación documental para inscripción en torneos.
