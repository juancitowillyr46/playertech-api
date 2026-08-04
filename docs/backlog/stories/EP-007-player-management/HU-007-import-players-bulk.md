# HU-007 Importar Jugadores en Lote

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-007 |
| Épica | EP-007 Gestión de Jugadores |
| Prioridad | Alta |
| MVP | Sí |
| Estado | Done |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir que el administrador del tenant importe jugadores desde un archivo Excel para acelerar altas masivas y migraciones de datos.

---

# Historia de Usuario

Como administrador de academia

Quiero subir jugadores y sus categorías mediante Excel

Para registrar en lote información que hoy se carga manualmente uno por uno.

---

# Valor de Negocio

* Reduce tiempo operativo en migraciones iniciales.
* Disminuye captura manual repetitiva.
* Sirve como mecanismo de onboarding para academias con datos previos.

---

# Reglas de Negocio

* El usuario debe pertenecer al tenant actual.
* El archivo debe validar formato y estructura antes de persistir.
* Cada jugador debe quedar asociado a la academia actual.
* El documento del jugador debe seguir siendo único por academia.
* La categoría se selecciona antes de subir el archivo y aplica al job completo, no por fila.
* La plantilla oficial debe descargarse desde backend y usar catálogos compartidos para `documentType`, `nationality`, `dominantFoot` y `gender`.
* Si el archivo contiene errores estructurales o de negocio, el job debe quedar en `COMPLETED_WITH_ERRORS` o `FAILED` según corresponda.

---

# Criterios de Aceptación

* Dado un archivo Excel válido, cuando lo importo, entonces el sistema registra los jugadores de forma asíncrona.
* Dado un archivo con errores de validación, cuando lo importo, entonces el sistema reporta los errores por fila y conserva los registros válidos.
* Dado un jugador duplicado por documento dentro de la academia, cuando lo importo, entonces el sistema rechaza la fila.
* Dado un usuario sin contexto tenant, cuando intenta importar, entonces el sistema rechaza la operación.

---

# Alcance MVP

* Selección previa de categoría.
* Descarga de plantilla `.xlsx` desde backend con hojas `Datos` y `Referencias`.
* La pestaña `Referencias` debe incluir el catálogo compartido de nacionalidades expuesto por `GET /api/v1/academy/nationalities/options`.
* Subida de archivo `.xlsx`.
* Creación de job asíncrono.
* Polling de progreso desde frontend.
* Reporte de errores por fila y resumen final.

---

# Decisión Técnica Inicial

* Endpoint sugerido: `POST /api/v1/academy/players/import`
* Entrada: archivo Excel en `multipart/form-data`
* Estrategia actual: procesamiento asíncrono con persistencia del job y reporte de progreso
* La categoría se pasa una sola vez por job y no por fila

---

# Dependencias

* Parser de Excel.
* Plantilla oficial de importación.
* Resolución de categorías por academia.
* Reporte estructurado de errores.

---

# Implementación

* Endpoint: `POST /api/v1/academy/players/import`
* Entrada: `multipart/form-data`
* Formato: `.xlsx`
* Clave de categoría: `categoryId`
* Estrategia MVP: persistir filas válidas, reportar errores por fila y exponer progreso consultable
