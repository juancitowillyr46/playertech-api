# HU-010 Consultar Detalle de Academia

## Información General

| Campo            | Valor                       |
| ---------------- | --------------------------- |
| ID               | HU-010                      |
| Épica            | EP-001 Gestión de Academias |
| Prioridad        | Media                       |
| MVP              | Sí                          |
| Estado           | Done                        |
| Actor Principal  | Super Admin                 |
| Actor Secundario | N/A                         |

---

# Objetivo

Permitir consultar la información detallada de una academia registrada.

---

# Problema de Negocio

El Super Admin necesita acceder a la información completa de una academia para realizar tareas administrativas y de soporte.

---

# Historia de Usuario

Como Super Admin

Quiero consultar el detalle de una academia

Para conocer toda la información asociada a la misma.

---

# Valor de Negocio

Facilita la administración y el soporte de academias registradas.

---

# Contexto

Cada academia representa un tenant independiente dentro de PlayerTech.

---

# Dominios Involucrados

* Academy

---

# Reglas de Negocio

## BR-001

La academia debe existir.

## BR-002

Solo el Super Admin puede consultar cualquier academia.

---

# Datos Mostrados

* Identificador
* Nombre
* Correo de contacto
* Teléfono
* Dirección
* Ciudad
* Estado
* Fecha de creación
* Fecha de última actualización

---

# Flujo Principal

1. Super Admin consulta el listado de academias.
2. Selecciona una academia.
3. El sistema consulta la información.
4. El sistema muestra el detalle completo.

---

# Flujos Alternativos

## AF-001

La academia no existe.

Resultado:

El sistema informa que la academia no fue encontrada.

---

# Criterios de Aceptación

## CA-001 Consulta exitosa

Dado una academia existente

Cuando el Super Admin consulta su detalle

Entonces el sistema muestra toda la información disponible.

---

## CA-002 Academia inexistente

Dado una academia inexistente

Cuando el usuario intenta consultarla

Entonces el sistema informa que no existe.

---

# Casos de Error

## ER-001

Academia no encontrada.

---

# Permisos Requeridos

* Academy.Read

---

# Auditoría

Registrar:

* Usuario
* Fecha
* Hora
* Academia consultada

---

# Consideraciones Técnicas

No aplica.

---

# Fuera de Alcance

* Métricas de uso
* Estadísticas de la academia

---

# Dependencias

HU-008 Crear Academia

---

# Métricas de Éxito

* Consultas realizadas

---

# Notas

La información debe reflejar el estado actual de la academia.

Referencia técnica: 419ded4
