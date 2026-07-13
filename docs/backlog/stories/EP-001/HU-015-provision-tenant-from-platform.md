# HU-015 Provisionar Tenant Listo desde Plataforma

## Informacion General

| Campo | Valor |
| --- | --- |
| ID | HU-015 |
| Epica | EP-001 Gestion de Academias |
| Prioridad | Alta |
| MVP | Si |
| Estado | Done |
| Actor Principal | Super Admin |
| Actor Secundario | N/A |

---

# Objetivo

Permitir que el Super Admin provisiona un tenant desde la plataforma mediante `POST /api/v1/platform/academies`, dejando siempre el tenant listo para operar en una sola operacion backend.

---

# Problema de Negocio

La plataforma necesita un flujo administrativo que cree un tenant completo y operativo sin pasos intermedios ni provisionales. No debe existir un tenant sin academia, sin usuario owner/admin inicial, sin correo de bienvenida y sin su primer equipo inicial.

---

# Historia de Usuario

Como Super Admin

Quiero crear un tenant listo desde la plataforma

Para registrar academias de forma consistente y operativa sin depender del signup publico.

---

# Valor de Negocio

Reduce friccion operativa, unifica el alta de tenants en plataforma y permite que el root admin cree organizaciones listas para usar desde el primer momento.

---

# Contexto

Esta historia no reemplaza el signup publico del tenant.

Se enfoca en el flujo backend de plataforma para crear tenants de forma controlada desde `ROLE_ROOT`, usando la API de academias como punto de entrada.

---

# Dominios Involucrados

* Academy
* Identity
* Team
* Category

---

# Reglas de Negocio

## BR-001

El Super Admin puede crear un tenant desde la plataforma.

## BR-002

El sistema debe crear la academia.

## BR-003

El sistema debe crear el usuario owner/admin inicial asociado a la academia.

## BR-004

El sistema debe enviar un correo de bienvenida o activacion al usuario admin inicial.

## BR-005

El sistema debe crear el primer equipo del tenant.

## BR-006

El correo del usuario admin inicial debe ser unico.

## BR-007

La categoria usada para el primer equipo debe existir y estar activa.

---

# Datos Requeridos

## Obligatorios

* Nombre de la academia
* Correo de contacto de la academia
* Nombre del admin inicial
* Correo del admin inicial
* categoryId
* teamName

## Opcionales

* Teléfono
* Pais
* Departamento
* Ciudad
* Direccion

---

# Flujo Principal

1. El Super Admin inicia la creacion del tenant desde la plataforma.
2. El sistema valida los datos enviados.
3. El sistema crea la academia.
4. El sistema crea el usuario owner/admin inicial.
5. El sistema envía el correo de bienvenida o activacion al usuario admin inicial.
6. El sistema crea el primer equipo de la academia.
7. El sistema confirma que el tenant quedó listo para operar.

---

# Flujos Alternativos

## AF-001

El correo de contacto o el correo del admin inicial ya existe.

Resultado:

El sistema rechaza la operacion.

## AF-002

La categoria del primer equipo no existe o esta inactiva.

Resultado:

El sistema rechaza la creacion del tenant.

## AF-003

Faltan datos obligatorios.

Resultado:

El sistema informa los errores encontrados.

---

# Criterios de Aceptacion

## CA-001 Provisionamiento completo exitoso

Dado que el Super Admin envía datos válidos

Cuando crea un tenant desde plataforma

Entonces el sistema crea la academia, el usuario owner/admin inicial, el correo de bienvenida o activacion y el primer equipo.

## CA-002 Tenant listo

Dado que el flujo se ejecuta correctamente

Cuando finaliza la creación

Entonces el sistema deja el tenant listo para operar sin pasos adicionales.

## CA-003 Correo de bienvenida o activacion

Dado que el flujo se ejecuta correctamente

Cuando se provisiona el tenant

Entonces el sistema genera el correo correspondiente para el admin inicial.

## CA-004 Equipo inicial

Dado que el flujo se ejecuta correctamente

Cuando se provisiona el tenant

Entonces el sistema crea el primer equipo de la academia.

---

# Casos de Error

## ER-001

Correo duplicado.

## ER-002

Datos obligatorios incompletos.

## ER-003

Categoria inexistente o inactiva.

---

# Permisos Requeridos

* Academy.Create

## Alcance

Esta historia corresponde exclusivamente al contexto de plataforma (`ROLE_ROOT`).

---

# Auditoria

El sistema debe registrar:

* Usuario root que inicia la provision
* Fecha
* Hora
* Academia creada
* Usuario owner/admin creado

---

# Consideraciones Tecnicas

* El flujo debe resolverse desde la API de plataforma, idealmente manteniendo una sola entrada de provisionamiento.
* El envio de correo debe seguir desacoplado por mensajes asíncronos.
* La logica de creacion de academia, usuario inicial y primer equipo debe quedar en Application, no en el controller.
* El provisionamiento debe ejecutarse en cascada; si falla un paso esencial, el sistema no debe dejar el tenant a medias.

---

# Fuera de Alcance

* Signup publico del tenant.
* Provisionamiento parcial.
* Facturacion.
* DIAN.
* Planes comerciales.

---

# Dependencias

* EP-001 Gestion de Academias
* EP-003 Gestion de Usuarios de Plataforma/Tenant
* EP-014 Alta de Tenant

---

# Metricas de Exito

* Tenants creados desde plataforma.
* Tenants creados listos para operar.
* Correo de bienvenida/activacion enviado.
* Primer equipo creado.

---

# Notas

Esta historia define la evolucion backend de `POST /api/v1/platform/academies` para soportar el provisioning completo de tenants desde el root admin.
