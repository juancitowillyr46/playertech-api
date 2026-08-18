# HU-009 Ajustar Contrato de Matrícula

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-009 |
| Épica | EP-009-membership-management Gestión de Matrículas |
| Prioridad | Alta |
| MVP | Sí |
| Estado | Draft |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir crear una matrícula enviando `playerId`, `responsibleGuardianId` y `categoryId`.

---

# Historia de Usuario

Como administrador de academia

Quiero crear la matrícula con el contrato actualizado

Para registrar correctamente la inscripción administrativa del jugador.

---

# Reglas de Negocio

* La matrícula debe recibir el jugador, el acudiente responsable y la categoría de inscripción.
* La academia debe resolverse desde el contexto autenticado.
* El contrato debe mantener aislamiento por tenant.

---

# Criterios de Aceptación

* Dado un payload válido, cuando creo la matrícula, entonces el sistema la registra correctamente.
* Dado un payload sin `categoryId`, cuando intento crear la matrícula, entonces el sistema rechaza la operación.
* Dado un payload sin `responsibleGuardianId`, cuando intento crear la matrícula, entonces el sistema rechaza la operación.

