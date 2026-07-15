# HU-001 Registrar Información Tributaria de la Academia

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-001 |
| Épica | EP-023 Información Tributaria de Academias, Comprobantes y Soporte Fiscal |
| Prioridad | Alta |
| MVP | No |
| Estado | New |
| Actor Principal | Super Admin |

---

# Objetivo

Permitir guardar la información fiscal básica de una academia para preparar futuros comprobantes y procesos DIAN.

---

# Historia de Usuario

Como super administrador

Quiero registrar la información tributaria de una academia

Para dejarla preparada para comprobantes de pago y soporte fiscal.

---

# Reglas de Negocio

* La información tributaria pertenece a una academia.
* La información debe poder consultarse y actualizarse después.
* Esta historia no debe acoplar la lógica fiscal al módulo base de Academy.

---

# Criterios de Aceptación

* Dado una academia válida, cuando registro su información tributaria, entonces el sistema la persiste.

