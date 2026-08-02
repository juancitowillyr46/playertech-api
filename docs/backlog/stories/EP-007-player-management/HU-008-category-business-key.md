# HU-008 Clave de Negocio de Categoria

## Informacion General

| Campo | Valor |
| --- | --- |
| ID | HU-008 |
| Epica | EP-007 Gestion de Jugadores |
| Prioridad | Alta |
| MVP | Si |
| Estado | Done |
| Actor Principal | Academic Administrator |

---

# Objetivo

Definir una clave de negocio estable para las categorias que permita referenciarlas sin depender del UUID tecnico, especialmente en importaciones masivas y contratos de integracion.

---

# Historia de Usuario

Como administrador de academia

Quiero identificar las categorias mediante una clave de negocio estable

Para poder seleccionarlas y referenciarlas en procesos operativos sin exponer identificadores tecnicos.

---

# Valor de Negocio

* Simplifica la operacion diaria.
* Evita depender de UUIDs en procesos de importacion.
* Hace mas robusto el contrato de integracion de jugadores.

---

# Reglas de Negocio

* Cada categoria debe tener una `category_key` unica por academia.
* La `category_key` debe ser estable y legible.
* El sistema debe validar el formato de la clave antes de persistir.
* La categoria debe seguir siendo aislada por `academy_id`.

---

# Criterios de Aceptacion

* Dado una categoria valida, cuando la creo, entonces el sistema exige `category_key`.
* Dado una categoria existente con la misma clave en la misma academia, cuando intento crearla, entonces el sistema rechaza la operacion.
* Dado un import de jugadores, cuando el archivo usa `category_key`, entonces el sistema puede resolver la categoria correcta sin UUID.

---

# Alcance MVP

* `category_key` en create y update de categorias.
* Respuesta API con `category_key`.
* Unicidad por academia.
* Consumo por importacion masiva de jugadores.

---

# Decision Tecnica

* Contrato API en `snake_case`.
* Persistencia en `category_key` con mapping XML.
* Unico por `academy_id + category_key`.


