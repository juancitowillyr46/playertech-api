# HU-011 Renombrar Referencia de Acudiente

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-011 |
| Épica | EP-009-membership-management Gestión de Matrículas |
| Prioridad | Alta |
| MVP | Sí |
| Estado | Draft |
| Actor Principal | Academic Administrator |

---

# Objetivo

Usar `responsibleGuardianId` en lugar de `primaryGuardianId` en el contrato de matrícula.

---

# Historia de Usuario

Como administrador de academia

Quiero usar una referencia más neutral para el acudiente de matrícula

Para reflejar que la persona asociada representa al responsable elegido en la inscripción.

---

# Reglas de Negocio

* El contrato no debe asumir un acudiente principal fijo si el negocio no lo garantiza.
* La referencia del acudiente debe reflejar responsabilidad de matrícula, no jerarquía implícita.

---

# Criterios de Aceptación

* Dado un contrato actualizado, cuando creo una matrícula, entonces el payload y response usan `responsibleGuardianId`.
* Dado un contrato anterior, cuando se revisa la documentación, entonces el campo viejo `primaryGuardianId` no aparece como contrato canónico.

