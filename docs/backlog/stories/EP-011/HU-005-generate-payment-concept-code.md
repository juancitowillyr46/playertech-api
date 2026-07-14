# HU-005 Generar código automático de concepto de pago

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-005 |
| Épica | EP-011 Gestión de Conceptos de Pago |
| Prioridad | Alta |
| MVP | Sí |
| Estado | Draft |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir que el sistema genere automáticamente el código del concepto de pago a partir de su nombre.

---

# Historia de Usuario

Como usuario de academia

Quiero que el código del concepto de pago se genere automáticamente

Para evitar errores manuales, mantener consistencia y simplificar el alta desde el frontend.

---

# Reglas de Negocio

* El código debe derivarse del nombre normalizado.
* El código debe generarse en backend.
* El código no debe editarse manualmente desde frontend.
* Si el código ya existe, el sistema debe resolver la colisión con sufijo determinístico.
* El código debe mantenerse inmutable en actualización.

---

# Criterios de Aceptación

* Dado un nombre válido, cuando creo un concepto, entonces el sistema genera su código automáticamente.
* Dado un nombre repetido, cuando creo otro concepto, entonces el sistema genera un código único con sufijo.
* Dado un concepto existente, cuando lo actualizo, entonces el código original se conserva.

---

# Permisos Requeridos

* PaymentConcept.Create
* PaymentConcept.Update
