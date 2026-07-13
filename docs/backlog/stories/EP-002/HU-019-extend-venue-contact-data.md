# HU-019 Ampliar Datos de Contacto de Sede

## Información General

| Campo           | Valor                   |
| --------------- | ----------------------- |
| ID              | HU-019                  |
| Épica           | EP-002 Gestión de Sedes |
| Prioridad       | Media                   |
| MVP             | Sí                      |
| Estado          | Draft                   |
| Actor Principal | Academic Administrator  |

---

# Objetivo

Permitir registrar y consultar información de contacto adicional de una sede.

---

# Historia de Usuario

Como administrador de academia

Quiero gestionar el teléfono y la dirección de una sede

Para tener información operativa más completa en el detalle, los listados y los formularios.

---

# Dominios Involucrados

* Venue

---

# Reglas de Negocio

## BR-001

El nombre de la sede sigue siendo obligatorio.

## BR-002

El teléfono de la sede es opcional.

## BR-003

La dirección de la sede es opcional.

## BR-004

El teléfono puede registrarse en formato local o internacional normalizado.

---

# Datos Requeridos

## Obligatorios

* Nombre

## Opcionales

* Dirección
* Teléfono
* Ciudad

---

# Flujo Principal

1. El administrador accede al módulo de sedes.
2. Crea o edita una sede.
3. Ingresa teléfono y dirección si lo requiere.
4. El sistema valida los datos.
5. El sistema guarda la sede.
6. El sistema muestra el dato en el detalle y en el listado.

---

# Impacto en Contrato

## Requests

Los endpoints de creación y actualización de sedes deben aceptar los campos:

* `phone`
* `address`

## Responses

El detalle de sede y el listado de sedes deben exponer esos campos cuando existan.

## UI

El front debe poder mostrar teléfono y dirección en:

* formulario de creación
* formulario de edición
* listado de sedes
* detalle de sede

---

# Criterios de Aceptación

## CA-001

Dado una sede con teléfono y dirección

Cuando el administrador consulta el listado o el detalle

Entonces el sistema muestra ambos campos cuando existan.

---

## CA-002

Dado una sede sin teléfono o sin dirección

Cuando el administrador la consulta

Entonces el sistema no marca error y muestra los campos vacíos o nulos según corresponda.

---

## CA-003

Dado información válida

Cuando crea o actualiza una sede con teléfono y dirección

Entonces el sistema guarda los cambios correctamente.

---

# Permisos Requeridos

* Venue.Create
* Venue.Update
* Venue.Read
