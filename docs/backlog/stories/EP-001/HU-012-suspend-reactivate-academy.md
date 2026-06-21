# HU-012 Suspender o Reactivar Academia

## Información General

| Campo            | Valor                       |
| ---------------- | --------------------------- |
| ID               | HU-012                      |
| Épica            | EP-001 Gestión de Academias |
| Prioridad        | Alta                        |
| MVP              | Sí                          |
| Estado           | Done                        |
| Actor Principal  | Super Admin                 |
| Actor Secundario | N/A                         |

---

# Objetivo

Permitir controlar el estado operativo de una academia desde el contexto de plataforma.

---

# Problema de Negocio

Es necesario controlar qué academias pueden utilizar la plataforma sin eliminar información histórica.

---

# Historia de Usuario

Como Super Admin

Quiero suspender o reactivar una academia

Para controlar su acceso a la plataforma.

---

# Valor de Negocio

Permite gestionar el ciclo de vida operativo de las academias.

---

# Contexto

PlayerTech conserva toda la información histórica de las academias.

Las academias no se eliminan físicamente.

---

# Dominios Involucrados

* Academy
* User

---

# Reglas de Negocio

## BR-001

Las academias no pueden eliminarse físicamente.

## BR-002

Una academia suspendida no puede autenticarse.

## BR-003

La información histórica debe conservarse.

## BR-004

Solo academias suspendidas pueden ser reactivadas.

---

# Flujo Principal (Suspensión)

1. Super Admin selecciona academia.
2. Solicita suspensión.
3. El sistema cambia el estado a SUSPENDED.
4. El sistema registra la operación.

---

# Flujo Principal (Reactivación)

1. Super Admin selecciona academia suspendida.
2. Solicita reactivación.
3. El sistema cambia el estado a ACTIVE.
4. El sistema registra la operación.

---

# Flujos Alternativos

## AF-001

La academia ya se encuentra suspendida.

Resultado:

El sistema informa el estado actual.

---

## AF-002

La academia ya se encuentra activa.

Resultado:

El sistema informa el estado actual.

---

# Criterios de Aceptación

## CA-001 Suspensión exitosa

Dado una academia activa

Cuando el Super Admin la suspende

Entonces el sistema cambia el estado a SUSPENDED.

---

## CA-002 Bloqueo de acceso

Dado una academia suspendida

Cuando cualquiera de sus usuarios intenta autenticarse

Entonces el sistema rechaza el acceso.

---

## CA-003 Reactivación exitosa

Dado una academia suspendida

Cuando el Super Admin la reactiva

Entonces el sistema cambia el estado a ACTIVE.

---

# Casos de Error

## ER-001

Academia inexistente.

## ER-002

Estado inválido para la operación solicitada.

---

# Permisos Requeridos

* Academy.Suspend
* Academy.Activate

---

# Auditoría

Registrar:

* Usuario
* Fecha
* Hora
* Estado anterior
* Estado nuevo

---

# Consideraciones Técnicas

No aplica.

---

# Fuera de Alcance

* Eliminación física de academias
* Gestión de facturación SaaS

---

# Dependencias

HU-008 Crear Academia

---

# Métricas de Éxito

* Academias suspendidas
* Academias reactivadas

---

# Notas

La suspensión afecta la autenticación de todos los usuarios pertenecientes a la academia.
Esta historia pertenece exclusivamente al contexto de plataforma (`ROLE_ROOT`).

Referencia técnica: 419ded4
