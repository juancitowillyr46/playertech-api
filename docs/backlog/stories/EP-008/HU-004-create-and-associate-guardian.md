# HU-004 Crear Acudiente y Asociarlo a Jugador

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-004 |
| Épica | EP-008 Gestión de Relaciones Jugador-Acudiente |
| Prioridad | Alta |
| MVP | Sí |
| Estado | Draft |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir crear un acudiente nuevo y asociarlo inmediatamente a un jugador.

---

# Historia de Usuario

Como administrador de academia

Quiero crear un acudiente nuevo desde el detalle de un jugador y asociarlo en la misma operación

Para no obligarme a salir del contexto del jugador cuando el acudiente todavía no existe en el sistema.

---

# Reglas de Negocio

* El jugador debe existir y pertenecer a la academia actual.
* El acudiente nuevo debe crearse dentro de la misma academia.
* La relación debe respetar el principal vigente.
* Si el acudiente ya existe, el sistema debería permitir reutilizarlo en lugar de duplicarlo.

---

# Criterios de Aceptación

* Dado un jugador válido y datos nuevos de acudiente, cuando ejecuto la operación, entonces el sistema crea el acudiente y lo asocia.
* Dado un acudiente ya existente, cuando intento crearlo de nuevo desde el mismo flujo, entonces el sistema evita duplicados y lo reutiliza si corresponde.

---

# Permisos Requeridos

* Guardian.Create
* PlayerGuardian.Create
