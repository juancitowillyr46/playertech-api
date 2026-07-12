# HU-013 Restablecer contraseña

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-013 |
| Épica | EP-003 Gestión de Usuarios y Accesos |
| Prioridad | Alta |
| MVP | Sí |
| Estado | Draft |
| Actor Principal | Usuario autenticado |

---

# Objetivo

Permitir al usuario recuperar el acceso a su cuenta mediante un flujo de correo y confirmación de nueva contraseña.

---

# Historia de Usuario

Como usuario autenticado o usuario invitado

Quiero restablecer mi contraseña por correo

Para recuperar el acceso a mi cuenta de forma segura.

---

# API Sugerida

* `POST /api/v1/auth/password/reset-request`
* `POST /api/v1/auth/password/reset-confirm`

---

# Alcance

* Enviar correo con enlace o token de recuperación.
* Permitir definir una nueva contraseña desde la interfaz provista.
* Invalidar el token una vez usado.

