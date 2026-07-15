# HU-001 Registrar Información Fiscal de la Academia

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-001 |
| Épica | EP-023 Información Fiscal de Academias, Comprobantes y Soporte Fiscal |
| Prioridad | Alta |
| MVP | Sí |
| Estado | Implemented |
| Actor Principal | Super Admin |

---

# Objetivo

Permitir guardar la información fiscal básica de una academia para preparar comprobantes operativos y soporte fiscal.

---

# Historia de Usuario

Como super administrador

Quiero registrar la información fiscal de una academia

Para dejarla preparada para comprobantes de pago y soporte fiscal.

---

# Reglas de Negocio

* La información fiscal pertenece a una academia.
* La academia mantiene un único perfil fiscal principal.
* Los comprobantes de pago deben tomar por defecto ese perfil.
* El dígito de verificación es opcional.

---

# Criterios de Aceptación

* Dado una academia válida, cuando registro su información fiscal, entonces el sistema la persiste.
