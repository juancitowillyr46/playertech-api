# HU-003 Actualizar Categoría

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-003 |
| Épica | EP-004 Gestión de Categorías |
| Prioridad | Alta |
| MVP | Sí |
| Estado | Draft |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir actualizar los datos de una categoría existente.

---

# Historia de Usuario

Como administrador de academia

Quiero actualizar una categoría

Para mantener la clasificación deportiva alineada con la operación real.

---

# Reglas de Negocio

* La categoría debe existir.
* La edición debe respetar el contexto de la academia.
* El rango de edad debe seguir siendo válido.
* No se permiten duplicados por academia.

---

# Criterios de Aceptación

* Dado una categoría existente, cuando se actualiza con datos válidos, entonces se guardan los cambios.
* Dado un intento de duplicado, cuando se guarda, entonces el sistema rechaza la operación.

