# HU-014 Perfil Basico de Academia con Ubicacion y Consentimientos

## Informacion General

| Campo | Valor |
| --- | --- |
| ID | HU-014 |
| Epica | EP-001 Gestion de Academias |
| Prioridad | Media |
| MVP | Si |
| Estado | Done |
| Actor Principal | Tenant Academy Admin |
| Actor Secundario | Super Admin |

---

# Objetivo

Permitir que la academia almacene datos basicos de ubicacion y consentimiento legal sin introducir una estructura geografica compleja para el MVP.

---

# Historia de Usuario

Como Tenant Academy Admin

Quiero registrar pais, departamento, ciudad, direccion, telefono y consentimiento legal de mi academia

Para completar el perfil operativo y legal de mi institucion dentro de PlayerTech.

---

# Valor de Negocio

Facilita la puesta en marcha del tenant con informacion suficiente para operacion, contacto y cumplimiento basico sin agregar complejidad prematura.

---

# Contexto

El MVP necesita capturar datos simples y accionables para academias que pueden operar en una o varias ciudades sin requerir un catalogo geografico formal.

---

# Dominios Involucrados

* Academy

---

# Reglas de Negocio

## BR-001

Pais, departamento y ciudad se almacenan como texto plano en el perfil de la academia.

## BR-002

El telefono se normaliza en backend como un unico valor, aunque en UI se capture separado por codigo de pais y numero local.

## BR-003

El consentimiento de terminos y condiciones es obligatorio.

## BR-004

El consentimiento para tratamiento de datos es obligatorio.

## BR-005

El sistema debe registrar una aceptacion simple con fecha cuando el tenant complete el alta.

---

# Criterios de Aceptacion

## CA-001

Dado un tenant administrando su academia

Cuando actualiza el perfil con pais, departamento, ciudad y direccion

Entonces el sistema persiste la informacion basica de ubicacion.

## CA-002

Dado un tenant completando el signup

Cuando no acepta terminos y condiciones o tratamiento de datos

Entonces el sistema rechaza el registro.

## CA-003

Dado un tenant completando el signup

Cuando acepta los consentimientos requeridos

Entonces el sistema registra la academia y deja evidencia de la aceptacion.

---

# Alcance MVP

* Pais, departamento, ciudad y direccion como texto.
* Telefono normalizado en backend.
* Consentimiento legal basico.

---

# Consideraciones Tecnicas

* `country` y `department` se agregan al perfil de `Academy`.
* `acceptedTerms` y `acceptedDataProcessing` se validan en `TenantSignup`.
* No se crea tabla de ubicaciones ni catalogo geograficp para el MVP.

---

# Fuera de Alcance

* Ubigeos o catalogo geografico formal.
* Flujos avanzados de consentimiento.
* Versionado historico de consentimientos.
