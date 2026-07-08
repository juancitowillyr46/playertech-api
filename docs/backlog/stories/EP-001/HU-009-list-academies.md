# HU-009 Consultar Academias

## Información General

| Campo            | Valor                       |
| ---------------- | --------------------------- |
| ID               | HU-009                      |
| Épica            | EP-001 Gestión de Academias |
| Prioridad        | Alta                        |
| MVP              | Sí                          |
| Estado           | Done                        |
| Actor Principal  | Super Admin                 |
| Actor Secundario | N/A                         |

---

# Objetivo

Permitir consultar las academias registradas en la plataforma desde el contexto de plataforma.

---

# Problema de Negocio

El Super Admin necesita tener visibilidad de todas las academias registradas para administrar los clientes de la plataforma.

---

# Historia de Usuario

Como Super Admin

Quiero consultar las academias registradas

Para administrar los clientes de la plataforma.

---

# Valor de Negocio

Permite supervisar las academias activas, suspendidas e inactivas.

---

# Contexto

PlayerTech opera bajo un modelo SaaS Multi-Tenant.

Cada academia representa un tenant independiente.

---

# Dominios Involucrados

* Academy

---

# Reglas de Negocio

## BR-001

Solo el Super Admin puede consultar todas las academias.

## BR-002

Debe mostrarse el estado actual de cada academia.

---

# Datos Mostrados

* Nombre
* Correo de contacto
* Estado
* Fecha de creación

---

# Flujo Principal

1. Super Admin accede al módulo de academias.
2. El sistema consulta las academias registradas.
3. El sistema muestra el listado.

---

# Flujos Alternativos

## AF-001

No existen academias registradas.

Resultado:

El sistema muestra una lista vacía.

---

# Criterios de Aceptación

## CA-001 Consulta exitosa

Dado que existen academias registradas

Cuando el Super Admin consulta el listado

Entonces el sistema muestra las academias registradas.

---

## CA-002 Visualización de estado

Dado una academia registrada

Cuando aparece en el listado

Entonces el sistema muestra su estado actual.

---

# Casos de Error

No aplica.

---

# Permisos Requeridos

* Academy.Read

---

# Auditoría

Registrar:

* Usuario
* Fecha
* Hora

---

# Consideraciones Técnicas

No aplica.

---

# Fuera de Alcance

* Reportes de uso
* Facturación SaaS

---

# Dependencias

HU-008 Crear Academia

---

# Métricas de Éxito

* Número de consultas realizadas

---

# Notas

La consulta debe soportar crecimiento futuro de academias.
Esta historia pertenece al contexto de plataforma (`ROLE_ROOT`).

Referencia técnica: 419ded4

