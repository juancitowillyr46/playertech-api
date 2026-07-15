# HU-005 Vincular Soporte Fiscal en PDF

## Información General

| Campo | Valor |
| --- | --- |
| ID | HU-005 |
| Épica | EP-023 Información Tributaria de Academias, Comprobantes y Soporte Fiscal |
| Prioridad | Media |
| MVP | No |
| Estado | New |
| Actor Principal | Academy Admin |

---

# Objetivo

Permitir registrar la referencia a un PDF fiscal descargado desde otra aplicación.

---

# Historia de Usuario

Como administrador de academia

Quiero vincular un soporte fiscal en PDF

Para conservar la trazabilidad sin acoplar PlayerTech a la app que emitió el archivo.

---

# Reglas de Negocio

* El archivo PDF debe referenciar un pago o un comprobante operativo.
* PlayerTech sólo conserva la referencia, nombre, ruta y metadatos mínimos del archivo.
* La emisión fiscal ocurre fuera del sistema.

---

# Criterios de Aceptación

* Dado un comprobante o pago existente, cuando vinculo el PDF, entonces el sistema guarda la referencia.
