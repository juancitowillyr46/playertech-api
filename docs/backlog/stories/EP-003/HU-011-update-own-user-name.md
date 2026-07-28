# HU-012 Actualizar mi nombre

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-012 |
| Épica | EP-003 Gestión de Usuarios y Accesos |
| Prioridad | Media |
| MVP | Sí |
| Estado | Draft |
| Actor Principal | Usuario autenticado |

---

# Objetivo

Permitir que el usuario autenticado actualice únicamente su nombre visible.

---

# Historia de Usuario

Como usuario autenticado

Quiero actualizar mi nombre

Para mantener mi perfil personal actualizado sin modificar otros datos.

---

# API Sugerida

* `PUT /api/v1/auth/me/name`

---

# Alcance

* Permite cambiar sólo el nombre.
* No permite cambiar correo, rol, academia ni contraseña.

