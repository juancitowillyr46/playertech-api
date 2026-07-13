# HU-009 Crear miembro de staff con acceso

## Información General

| Campo           | Valor                               |
| --------------- | ----------------------------------- |
| ID              | HU-009                              |
| Épica           | EP-021 Gestión de Staff y Cuerpo Técnico |
| Prioridad       | Alta                                |
| MVP             | Sí                                  |
| Estado          | Draft                               |
| Actor Principal | Academy Admin                       |

---

# Objetivo

Permitir crear un miembro de staff y su cuenta de acceso en una sola operación.

---

# Historia de Usuario

Como administrador de academia

Quiero crear un miembro de staff con sus datos personales y su acceso al sistema

Para registrar una sola vez a la persona, asignarle el rol funcional correspondiente y habilitar su ingreso al SaaS mediante invitación o contraseña inicial.

---

# Dominios Involucrados

* Identity
* Staff

---

# Reglas de Negocio

## BR-001

La persona creada debe pertenecer a la academia actual.

## BR-002

La operación debe crear el usuario del sistema y el registro de staff en una sola transacción de negocio.

## BR-003

El rol funcional del usuario debe asignarse al momento de la creación.

## BR-004

El acceso inicial puede resolverse de dos maneras:

* por invitación enviada al correo
* por creación directa de contraseña desde plataforma

## BR-005

Si se opta por invitación, la cuenta queda pendiente de activación.

## BR-006

Si se opta por creación directa de contraseña, la cuenta queda activa al finalizar la operación.

## BR-007

No debe existir un miembro de staff duplicado para el mismo correo dentro de la misma academia.

---

# Datos Requeridos

## Obligatorios

* fullName
* email
* role
* staffStatus o staffType

## Condicionales

* password y passwordConfirmation, solo si la creación es directa desde plataforma
* sendInvitation, si se desea forzar el flujo por correo

---

# Flujo Principal

1. El administrador ingresa al módulo de staff.
2. Selecciona crear nuevo miembro.
3. Ingresa datos personales y rol funcional.
4. El sistema crea el usuario y el staff asociado.
5. El sistema decide si envía invitación o crea la clave inicial.
6. El sistema confirma la creación y deja el registro disponible.

---

# Criterios de Aceptación

## CA-001

Dado datos válidos

Cuando el administrador crea un miembro de staff

Entonces el sistema crea el usuario y el registro de staff en una sola operación.

---

## CA-002

Dado que se configure invitación por correo

Cuando finaliza la creación

Entonces el sistema envía la invitación y deja la cuenta pendiente de activación.

---

## CA-003

Dado que se configure creación directa de contraseña

Cuando finaliza la creación

Entonces el sistema crea el acceso listo para usar.

---

## CA-004

Dado un correo ya registrado en la misma academia

Cuando intenta crear el miembro de staff

Entonces el sistema rechaza la operación.

---

# Dependencias

* EP-003 Gestión de Usuarios y Accesos, para creación y activación de la cuenta.

---

# Permisos Requeridos

* Staff.Create
* Identity.UserCreate
* Identity.UserInvite
