# HU-001 Registrar Tenant

## Información General

| Campo            | Valor                |
| ---------------- | -------------------- |
| ID               | HU-001               |
| Épica            | EP-014 Alta de Tenant |
| Prioridad        | Alta                 |
| MVP              | Sí                   |
| Estado           | Done                 |
| Actor Principal  | Tenant Prospect      |
| Actor Secundario | Super Admin          |

---

# Objetivo

Permitir que un tenant se registre con datos simplificados para iniciar su alta en la plataforma.

---

# Problema de Negocio

El onboarding del tenant debe ser ágil, sin depender exclusivamente del flujo manual del Super Admin, pero sin perder control ni validación.

---

# Historia de Usuario

Como Tenant Prospect

Quiero registrar mi academia con datos simplificados

Para iniciar el proceso de alta y activación de mi cuenta.

---

# Valor de Negocio

Reduce fricción de entrada y permite captación directa de clientes.

---

# Contexto

El registro crea la academia y el usuario owner/admin inicial en un estado pendiente de activación.

El Super Admin conserva la capacidad de crear tenants desde la plataforma.

---

# Dominios Involucrados

* Academy
* Identity

---

# Reglas de Negocio

## BR-001

El nombre de la academia es obligatorio.

## BR-002

El correo del tenant es obligatorio y debe ser válido.

## BR-003

El sistema debe crear la academia y el usuario owner/admin inicial.

## BR-004

La cuenta debe quedar pendiente hasta validar el correo.

## BR-005

El Super Admin sigue pudiendo crear tenants por el flujo de plataforma.

---

# Datos Requeridos

## Obligatorios

* Nombre de academia
* Correo del tenant
* Nombre del contacto principal

## Opcionales

* Teléfono
* Ciudad
* Dirección
* Logo

---

# Flujo Principal

1. El tenant completa el formulario simplificado.
2. El sistema valida la información.
3. El sistema crea la academia en estado pendiente.
4. El sistema crea el usuario owner/admin del tenant.
5. El sistema envía correo de activación.
6. El tenant confirma su correo.
7. El sistema habilita el acceso completo.

---

# Flujos Alternativos

## AF-001

El correo ya existe.

Resultado:

El sistema rechaza el registro.

## AF-002

Faltan datos obligatorios.

Resultado:

El sistema informa los errores encontrados.

---

# Criterios de Aceptación

## CA-001 Registro exitoso

Dado que el tenant envía datos válidos

Cuando registra su academia

Entonces el sistema crea la academia y el usuario owner/admin inicial.

## CA-002 Validación de correo

Dado que el correo es obligatorio

Cuando el tenant envía un correo inválido

Entonces el sistema informa el error correspondiente.

## CA-003 Activación pendiente

Dado un tenant recién registrado

Cuando finaliza el proceso de registro

Entonces el sistema deja la cuenta pendiente hasta validar el correo.

---

# Casos de Error

## ER-001

Correo duplicado.

## ER-002

Campos obligatorios faltantes.

---

# Permisos Requeridos

N/A

---

# Auditoría

Registrar:

* Usuario o contacto que inicia el registro
* Fecha
* Hora
* Academia creada

---

# Consideraciones Técnicas

* El alta debe disparar un mensaje asíncrono de activación.
* El envio de correo debe quedar desacoplado del controlador.
* Para desarrollo local se recomienda validar el correo con `Mailpit` o una bandeja de prueba equivalente.
* La configuracion de transporte debe permitir cambiar entre proveedor real y entorno de pruebas por variables de entorno.

---

# Fuera de Alcance

* Facturación SaaS
* Planes comerciales
* Onboarding guiado avanzado

---

# Dependencias

EP-001 Gestión de Academias

---

# Métricas de Éxito

* Tenants registrados
* Correo de activación enviado

---

# Notas

Este flujo complementa, pero no reemplaza, la creación de tenants por parte del Super Admin.
Implementado con `Messenger`, `Mailer` y `Mailpit` para validar el circuito de activación por correo en desarrollo.
