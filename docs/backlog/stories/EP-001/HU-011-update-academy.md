# HU-011 Actualizar Academia

## Información General

| Campo            | Valor                       |
| ---------------- | --------------------------- |
| ID               | HU-011                      |
| Épica            | EP-001 Gestión de Academias |
| Prioridad        | Alta                        |
| MVP              | Sí                          |
| Estado           | Done                        |
| Actor Principal  | Super Admin                 |
| Actor Secundario | Tenant Academy Admin        |

---

# Objetivo

Permitir actualizar la información de una academia registrada.
El Super Admin puede actualizar cualquier academia desde la plataforma y el tenant puede actualizar sólo su propia academia desde `/api/v1/academy/me`.

---

# Problema de Negocio

La información de contacto y configuración de una academia puede cambiar con el tiempo.

---

# Historia de Usuario

Como Super Admin

Quiero actualizar una academia

Para mantener la información actualizada.

---

# Valor de Negocio

Garantiza la calidad y vigencia de los datos registrados.

---

# Contexto

Las academias pueden cambiar información administrativa sin afectar la operación del sistema.
El contexto tenant queda aislado por `academy_id` y no puede modificar una academia distinta a la propia.

---

# Dominios Involucrados

* Academy

---

# Reglas de Negocio

## BR-001

La academia debe existir.

## BR-002

No se puede modificar el identificador de la academia.

## BR-003

El nombre de la academia es obligatorio.

## BR-004

El correo de contacto es obligatorio.

---

# Datos Actualizables

* Nombre
* Correo de contacto
* Teléfono
* Dirección
* Ciudad
* Logo

---

# Flujo Principal

1. Super Admin selecciona una academia.
2. Modifica la información.
3. El sistema valida los cambios.
4. El sistema actualiza la academia.
5. El sistema confirma la operación.

---

# Flujos Alternativos

## AF-001

Información inválida.

Resultado:

El sistema informa los errores encontrados.

---

## AF-002

Academia inexistente.

Resultado:

El sistema rechaza la actualización.

---

# Criterios de Aceptación

## CA-001 Actualización exitosa

Dado una academia existente

Cuando el usuario actualiza información válida

Entonces el sistema guarda los cambios.

---

## CA-002 Datos obligatorios

Dado que existen datos obligatorios

Cuando el usuario intenta guardar información incompleta

Entonces el sistema informa los errores.

---

## CA-003 Academia inexistente

Dado una academia inexistente

Cuando se intenta actualizar

Entonces el sistema rechaza la operación.

---

# Casos de Error

## ER-001

Academia no encontrada.

## ER-002

Correo inválido.

## ER-003

Nombre vacío.

---

# Permisos Requeridos

* Academy.Update
* Academy.Profile.Update

---

# Auditoría

Registrar:

* Usuario
* Fecha
* Hora
* Valores anteriores
* Valores nuevos

---

# Consideraciones Técnicas

No aplica.

---

# Fuera de Alcance

* Cambio de tenant
* Cambio de identificador interno

---

# Dependencias

HU-008 Crear Academia

---

# Métricas de Éxito

* Actualizaciones realizadas

---

# Notas

La actualización no debe afectar la información histórica asociada a la academia.
La versión tenant-scoped del flujo se ejecuta contra `/api/v1/academy/me`.
La implementación actual delega en Application Layer con CQRS para mantener los controladores delgados.

Referencia técnica: ff61ec1
