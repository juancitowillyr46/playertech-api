# HU-012 Actualizar Respuesta de Matrícula

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-012 |
| Épica | EP-009-membership-management Gestión de Matrículas |
| Prioridad | Alta |
| MVP | Sí |
| Estado | Draft |
| Actor Principal | Frontend Consumer |

---

# Objetivo

Recibir en el response de matrícula los campos actualizados del contrato.

---

# Historia de Usuario

Como frontend consumidor de la API

Quiero obtener la matrícula con `responsibleGuardianId` y `categoryId`

Para mostrar la información correcta de la inscripción sin depender de campos ambiguos.

---

# Reglas de Negocio

* El response debe reflejar el contrato canónico del feature.
* El response debe conservar trazabilidad del estado de la matrícula.

---

# Criterios de Aceptación

* Dado una matrícula creada, cuando consulto el response, entonces veo `responsibleGuardianId` y `categoryId`.
* Dado un cliente frontend, cuando consume el endpoint, entonces no depende de `primaryGuardianId` como contrato canónico.

