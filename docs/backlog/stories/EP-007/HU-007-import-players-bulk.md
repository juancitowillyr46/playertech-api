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

Permitir que el administrador del tenant importe jugadores y su categoría desde un archivo Excel para acelerar altas masivas y migraciones de datos.

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
* La categoría debe existir dentro de la academia o resolverse por `category_key`.
* Si el archivo contiene errores estructurales o de negocio, la importación debe rechazarse.

---

# Criterios de Aceptación

* Dado un archivo Excel válido, cuando lo importo, entonces el sistema registra los jugadores.
* Dado un archivo con errores de validación, cuando lo importo, entonces el sistema rechaza la importación y reporta los errores por fila.
* Dado un jugador duplicado por documento dentro de la academia, cuando lo importo, entonces el sistema rechaza la fila.
* Dado un usuario sin contexto tenant, cuando intenta importar, entonces el sistema rechaza la operación.

---

# Alcance MVP

* Subida de archivo `.xlsx`.
* Validación de plantilla.
* Procesamiento síncrono inicial.
* Reporte de errores por fila.
* Importación de jugadores y categorías referenciadas.

---

# Decisión Técnica Inicial

* Endpoint sugerido: `POST /api/v1/academy/players/import`
* Entrada: archivo Excel en `multipart/form-data`
* Estrategia inicial: rechazar el archivo completo si existe al menos un error de validación
* Evolución posterior: procesamiento asíncrono con cola y reporte de progreso

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
* Clave de categoría: `category_key`
* Estrategia MVP: falla toda la importación si existe al menos un error de validación

