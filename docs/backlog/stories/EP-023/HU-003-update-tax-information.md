# HU-003 Actualizar Información Fiscal de la Academia

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-003 |
| Épica | EP-023 Información Fiscal de Academias, Comprobantes y Soporte Fiscal |
| Prioridad | Alta |
| MVP | Sí |
| Estado | Implemented |
| Actor Principal | Super Admin |

---

# Objetivo

Permitir actualizar la información fiscal de una academia cuando cambien sus datos tributarios.

---

# Historia de Usuario

Como super administrador

Quiero actualizar la información fiscal de una academia

Para mantener vigente su configuración fiscal.

---

# Reglas de Negocio

* La actualización debe respetar el contexto de la academia.
* No se debe romper la información base de Academy.
* El dígito de verificación debe poder actualizarse de manera opcional.

---

# Criterios de Aceptación

* Dado una academia existente, cuando actualizo su información fiscal, entonces el sistema guarda los cambios.
