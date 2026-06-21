# HU-008 Crear Academia

## Información General

| Campo            | Valor                       |
| ---------------- | --------------------------- |
| ID               | HU-008                      |
| Épica            | EP-001 Gestión de Academias |
| Prioridad        | Alta                        |
| MVP              | Sí                          |
| Estado           | Done                        |
| Actor Principal  | Super Admin                 |
| Actor Secundario | N/A                         |

---

# Objetivo

Permitir registrar una nueva academia dentro de la plataforma PlayerTech desde el contexto de plataforma.

---

# Problema de Negocio

PlayerTech opera bajo un modelo SaaS Multi-Tenant y requiere registrar nuevas academias para que puedan administrar su operación de manera independiente.

---

# Historia de Usuario

Como Super Admin

Quiero registrar una academia

Para habilitar una nueva organización dentro de la plataforma.

---

# Valor de Negocio

Permite incorporar nuevos clientes al sistema y garantizar el aislamiento de la información entre academias.

---

# Contexto

Toda academia representa un tenant independiente dentro de PlayerTech.

Las academias administran:

* Jugadores
* Acudientes
* Categorías
* Equipos
* Matrículas
* Pagos

---

# Dominios Involucrados

* Academy

---

# Reglas de Negocio

## BR-001

Toda academia debe tener un nombre.

## BR-002

Toda academia debe tener un correo de contacto.

## BR-003

Toda academia se crea inicialmente con estado ACTIVE.

## BR-004

Toda academia debe poseer un identificador único generado por el sistema.

---

# Datos Requeridos

## Obligatorios

* Nombre
* Correo de contacto

## Opcionales

* Teléfono
* Dirección
* Ciudad
* Logo

---

# Flujo Principal

1. Super Admin accede a la opción Crear Academia.
2. Ingresa la información requerida.
3. El sistema valida los datos.
4. El sistema registra la academia.
5. El sistema asigna estado ACTIVE.
6. El sistema confirma la creación.

---

# Flujos Alternativos

## AF-001

El correo ya existe.

Resultado:

El sistema rechaza el registro.

---

## AF-002

Faltan datos obligatorios.

Resultado:

El sistema informa los errores encontrados.

---

# Criterios de Aceptación

## CA-001 Registro exitoso

Dado que el Super Admin posee permisos válidos

Cuando registra una academia con información válida

Entonces el sistema crea la academia exitosamente.

---

## CA-002 Nombre obligatorio

Dado que el nombre es obligatorio

Cuando el usuario intenta registrar una academia sin nombre

Entonces el sistema informa el error correspondiente.

---

## CA-003 Correo obligatorio

Dado que el correo es obligatorio

Cuando el usuario intenta registrar una academia sin correo

Entonces el sistema informa el error correspondiente.

---

## CA-004 Estado inicial

Dado una academia creada exitosamente

Cuando finaliza el proceso de registro

Entonces el sistema asigna el estado ACTIVE.

---

# Casos de Error

## ER-001

Información obligatoria incompleta.

## ER-002

Formato de correo inválido.

---

# Permisos Requeridos

* Academy.Create

## Alcance

Esta historia corresponde exclusivamente al contexto de plataforma (`ROLE_ROOT`).

---

# Auditoría

El sistema debe registrar:

* Usuario creador
* Fecha
* Hora
* Academia creada

---

# Consideraciones Técnicas

No aplica.

---

# Fuera de Alcance

* Facturación SaaS
* Planes comerciales
* Suscripciones

---

# Dependencias

Ninguna

---

# Métricas de Éxito

* Número de academias registradas

---

# Notas

La academia constituye el tenant principal dentro de PlayerTech.

Referencia técnica: 419ded4
