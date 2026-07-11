# HU-016 Registrar Origen de Creacion del Tenant

## Informacion General

| Campo | Valor |
| --- | --- |
| ID | HU-016 |
| Epica | EP-001 Gestion de Academias |
| Prioridad | Media |
| MVP | Si |
| Estado | Proposed |
| Actor Principal | Super Admin |
| Actor Secundario | Tenant Prospect |

---

# Objetivo

Permitir identificar si una academia fue creada desde el signup publico del tenant o desde la plataforma del Super Admin, para mejorar la trazabilidad operativa, el soporte y la analitica del onboarding.

---

# Problema de Negocio

Hoy existen dos caminos de alta de tenants: el signup publico y el provisioning desde plataforma. Sin un origen explicito, resulta mas dificil auditar, segmentar y dar seguimiento a cada academia segun su flujo de creacion.

---

# Historia de Usuario

Como Super Admin

Quiero saber si una academia fue creada desde signup publico o desde la plataforma

Para tener trazabilidad clara del origen del tenant y operar cada flujo de forma consistente.

---

# Valor de Negocio

Mejora la trazabilidad, facilita soporte, permite analitica de onboarding y ayuda a distinguir tenants autogestionados de tenants provisionados por plataforma.

---

# Contexto

El origen de creacion no reemplaza la informacion principal de negocio de la academia.

Debe funcionar como dato de trazabilidad tecnica y operativa, util para consultas, reportes y decisiones de onboarding.

---

# Dominios Involucrados

* Academy
* Identity

---

# Reglas de Negocio

## BR-001

Toda academia debe registrar su origen de creacion.

## BR-002

El signup publico debe marcar el origen como `PUBLIC_SIGNUP`.

## BR-003

La creacion desde plataforma debe marcar el origen como `PLATFORM`.

## BR-004

El origen de creacion debe ser consultable en respuestas HTTP cuando aplique.

## BR-005

El origen de creacion no debe alterar el comportamiento funcional principal de la academia.

---

# Datos Requeridos

## Obligatorios

* registrationSource

## Valores Permitidos

* `PUBLIC_SIGNUP`
* `PLATFORM`

---

# Flujo Principal

1. El sistema recibe un flujo de alta de tenant.
2. El backend identifica si el flujo proviene de signup publico o de plataforma.
3. El sistema persiste el origen de creacion en la academia.
4. El sistema devuelve el origen en la respuesta cuando el contrato lo incluya.

---

# Flujos Alternativos

## AF-001

El origen no puede determinarse.

Resultado:

El sistema rechaza la operacion o asigna un valor tecnico definido por negocio.

---

# Criterios de Aceptacion

## CA-001 Origen publico

Dado que el tenant se registra desde el signup publico

Cuando el sistema crea la academia

Entonces el origen queda registrado como `PUBLIC_SIGNUP`.

## CA-002 Origen plataforma

Dado que el Super Admin provisiona un tenant

Cuando el sistema crea la academia

Entonces el origen queda registrado como `PLATFORM`.

## CA-003 Trazabilidad consultable

Dado que una academia ya fue creada

Cuando se consulta su informacion

Entonces el sistema puede exponer el origen de creacion si el contrato lo requiere.

---

# Casos de Error

## ER-001

Origen de creacion invalido o ausente.

---

# Permisos Requeridos

* Academy.Create

## Alcance

Esta historia aplica tanto a plataforma como a tenant signup, segun el flujo ejecutado.

---

# Auditoria

El sistema debe registrar:

* Origen de creacion del tenant
* Usuario que inicio el flujo
* Fecha
* Hora

---

# Consideraciones Tecnicas

* El origen puede persistirse como campo tecnico en `Academy`.
* El valor debe ser seteado por el caso de uso, no por el cliente.
* La convencion de respuesta debe mantenerse en `camelCase`.

---

# Fuera de Alcance

* Reglas comerciales por origen.
* Cambios de comportamiento funcional por origen.
* Reporteria avanzada.

---

# Dependencias

* EP-001 Gestion de Academias
* EP-014 Alta de Tenant
* EP-015 Provisionar Tenant desde Plataforma

---

# Metricas de Exito

* Academias con origen de creacion identificado.
* Reduccion de ambiguedad en soporte y auditoria.

---

# Notas

Esta historia solo documenta la necesidad de trazabilidad del origen de alta; la implementacion se definira en una iteracion posterior.
