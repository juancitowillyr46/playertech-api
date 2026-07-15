# HU-002 Consultar Información Fiscal de la Academia

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-002 |
| Épica | EP-023 Información Fiscal de Academias, Comprobantes y Soporte Fiscal |
| Prioridad | Alta |
| MVP | Sí |
| Estado | Implemented |
| Actor Principal | Super Admin |

---

# Objetivo

Permitir visualizar la información fiscal guardada para una academia.

---

# Historia de Usuario

Como super administrador

Quiero consultar la información fiscal de una academia

Para revisar si está lista para facturación o comprobantes.

---

# Reglas de Negocio

* La consulta debe devolver sólo la información fiscal de la academia solicitada.
* El acceso debe restringirse según el contexto del usuario.
* El perfil fiscal es único por academia.

---

# Criterios de Aceptación

* Dado una academia con información fiscal, cuando la consulto, entonces el sistema la muestra.
