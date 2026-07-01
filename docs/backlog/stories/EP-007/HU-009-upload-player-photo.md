# HU-009 Subir Foto del Jugador

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-009 |
| Épica | EP-007 Gestión de Jugadores |
| Prioridad | Media |
| MVP | Sí |
| Estado | Draft |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir que el administrador de la academia asocie una foto al perfil de un jugador para mejorar su identificación visual dentro del sistema.

---

# Historia de Usuario

Como administrador de academia

Quiero subir la foto de un jugador

Para identificar visualmente al jugador en la plataforma.

---

# Valor de Negocio

Facilita el reconocimiento del jugador dentro de listados, perfiles y futuros flujos operativos.

---

# Contexto

La foto del jugador es un dato complementario del perfil y no debe interferir con la lógica principal del dominio.

---

# Dominios Involucrados

* Player
* Academy

---

# Reglas de Negocio

## BR-001

El jugador debe existir.

## BR-002

El usuario debe pertenecer al tenant actual.

## BR-003

El archivo debe aceptar formatos `png`, `jpg` o `jpeg`.

## BR-004

El sistema debe reemplazar la foto anterior si ya existe.

## BR-005

La foto debe quedar asociada al jugador dentro de su academia.

---

# Flujo Principal

1. El administrador selecciona un jugador.
2. Carga una imagen válida.
3. El sistema valida el formato.
4. El sistema guarda la foto.
5. El sistema confirma la operación.

---

# Criterios de Aceptación

## CA-001 Carga exitosa

Dado un jugador existente

Cuando se sube una foto válida

Entonces el sistema actualiza la imagen del jugador.

## CA-002 Validación de formato

Dado un archivo no permitido

Cuando se intenta subir

Entonces el sistema rechaza la operación.

## CA-003 Aislamiento tenant

Dado un usuario tenant autenticado

Cuando intenta modificar un jugador de otra academia

Entonces el sistema rechaza la operación.

---

# Alcance MVP

* Subida de foto de jugador.
* Reemplazo de foto existente.
* Validación básica de formatos permitidos.

---

# Consideraciones Técnicas

* Endpoint sugerido: `PATCH /api/v1/academy/players/{playerId}/photo`
* La API debe devolver la referencia del archivo almacenado, no binario.

---

# Fuera de Alcance

* Editor de imágenes.
* Recorte automático.
* Redimensionamiento avanzado.
