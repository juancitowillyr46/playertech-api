# HU-013 Subir Escudo Institucional de Academia

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-013 |
| Épica | EP-001 Gestión de Academias |
| Prioridad | Media |
| MVP | Sí |
| Estado | Done |
| Actor Principal | Super Admin |
| Actor Secundario | Tenant Academy Admin |

---

# Objetivo

Permitir asociar y actualizar el escudo institucional de una academia usando un formato de imagen adecuado para evitar fondos blancos o pérdidas de calidad.

---

# Historia de Usuario

Como Super Admin

Quiero subir el escudo institucional de una academia

Para mantener la identidad visual de la institución en la plataforma.

Como Tenant Academy Admin

Quiero actualizar el escudo institucional de mi academia

Para mantener consistente la imagen de mi tenant.

---

# Valor de Negocio

Mejora la presentación visual de cada academia y fortalece la identidad institucional dentro de PlayerTech.

---

# Contexto

El escudo forma parte del perfil visual de la academia y debe poder actualizarse sin afectar la operación funcional del tenant.

---

# Dominios Involucrados

* Academy

---

# Reglas de Negocio

## BR-001

La academia debe existir.

## BR-002

El usuario con contexto tenant sólo puede modificar el escudo de su propia academia.

## BR-003

El Super Admin puede modificar el escudo de cualquier academia desde plataforma.

## BR-004

El archivo debe aceptar formatos `png`, `jpg`, `jpeg` o `svg`.

## BR-005

Cuando se use imagen rasterizada, debe preferirse transparencia para evitar fondos blancos indeseados.

## BR-006

El escudo anterior puede ser reemplazado por uno nuevo.

---

# Flujo Principal

1. El usuario accede al endpoint correspondiente.
2. Selecciona un archivo de imagen válido.
3. El sistema valida el formato.
4. El sistema asocia el escudo a la academia.
5. El sistema confirma la actualización.

---

# Criterios de Aceptación

## CA-001 Carga exitosa

Dado una academia existente

Cuando se sube un archivo válido

Entonces el sistema actualiza el escudo de la academia.

## CA-002 Validación de formato

Dado un archivo no permitido

Cuando se intenta subir

Entonces el sistema rechaza la operación.

## CA-003 Aislamiento tenant

Dado un usuario tenant autenticado

Cuando intenta modificar otra academia

Entonces el sistema rechaza la operación.

---

# Alcance MVP

* Subida de escudo institucional.
* Reemplazo del archivo anterior.
* Validación básica de formatos permitidos.

---

# Consideraciones Técnicas

* Endpoint implementado para tenant: `POST /api/v1/academy/me/shield`
* Endpoint sugerido para plataforma: `PATCH /api/v1/platform/academies/{academyId}/shield`
* La API debe devolver la referencia del archivo almacenado, no binario.

---

# Fuera de Alcance

* Editor de imágenes.
* Recorte automático.
* Redimensionamiento avanzado.

