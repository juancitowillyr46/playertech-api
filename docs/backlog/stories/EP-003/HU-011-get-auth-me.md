# HU-011 Consultar perfil autenticado

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-011 |
| Épica | EP-003 Gestión de Usuarios y Accesos |
| Prioridad | Alta |
| MVP | Sí |
| Estado | Draft |
| Actor Principal | Usuario autenticado |

---

# Objetivo

Permitir que el front obtenga la información base del usuario autenticado, incluyendo correo, rol y contexto operativo.

---

# Historia de Usuario

Como usuario autenticado

Quiero consultar mi perfil

Para que la interfaz conozca mi identidad y mis permisos.

---

# API Sugerida

* `GET /api/v1/auth/me`

---

# Alcance

* Retorna `id`, `email`, `fullName`, `roles` y `academyId` cuando aplique.
* No expone datos de la academia salvo referencia de contexto.

