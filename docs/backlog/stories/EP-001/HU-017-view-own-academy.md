# HU-017 Consultar mi academia

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-017 |
| Épica | EP-001 Gestión de Academias |
| Prioridad | Alta |
| MVP | Sí |
| Estado | Draft |
| Actor Principal | Tenant Academy Admin |

---

# Objetivo

Permitir que el owner/admin de una academia consulte la información completa de su propia academia desde el contexto tenant.

---

# Problema de Negocio

El equipo operativo necesita ver los datos completos de la academia sin ingresar a la plataforma de administración global.

---

# Historia de Usuario

Como Tenant Academy Admin

Quiero consultar la información completa de mi academia

Para revisar y validar los datos institucionales del tenant.

---

# Valor de Negocio

Facilita la autogestión de la academia sin exponer contexto de plataforma.

---

# API Sugerida

* `GET /api/v1/academy/me`

---

# Alcance

* Ver nombre, correo, teléfono, ubicación, estado, origen de registro y auditoría de la academia.
* No permite editar datos.

---

# Regla de Negocio

El tenant sólo puede consultar su propia academia.

