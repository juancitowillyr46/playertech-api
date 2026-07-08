# HU-002 Cerrar Sesión

## Información General

| Campo     | Valor  |
| --------- | ------ |
| ID        | HU-002 |
| Épica     | EP-003 |
| Prioridad | Alta   |
| MVP       | Sí     |

---

# Historia de Usuario

Como usuario autenticado

Quiero cerrar sesión

Para finalizar de manera segura mi acceso a la plataforma.

---

# Reglas de Negocio

## BR-001

Solo usuarios autenticados pueden cerrar sesión.

---

# Flujo Principal

1. Usuario selecciona cerrar sesión.
2. Sistema invalida la sesión.
3. Sistema redirige al login.

---

# Criterios de Aceptación

## CA-001

Dado un usuario autenticado

Cuando solicita cerrar sesión

Entonces el sistema invalida la sesión activa.

## CA-002

Dado una sesión finalizada

Cuando intenta acceder a recursos protegidos

Entonces el sistema solicita autenticación nuevamente.

