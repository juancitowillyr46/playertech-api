# EP-025 - Catálogos maestros compartidos

## Objetivo

Como producto,
quiero centralizar los catálogos maestros que son utilizados por varios módulos,
para que Player, LegalGuardian y las futuras funcionalidades compartan valores consistentes y una única fuente de verdad.

---

## Problema de negocio

Algunos datos de referencia, como los tipos de documento, pueden ser utilizados por diferentes funcionalidades de la aplicación. Si cada módulo define sus propios valores y etiquetas, pueden aparecer inconsistencias entre formularios, importaciones, documentos y respuestas de la API.

Esto puede provocar:

- Valores diferentes para el mismo tipo de documento.
- Etiquetas inconsistentes entre frontend y backend.
- Validaciones duplicadas en varios módulos.
- Mayor esfuerzo para incorporar o retirar opciones.
- Contratos difíciles de mantener entre funcionalidades relacionadas.

---

## Objetivos del MVP

El MVP de esta épica deberá:

- Definir un catálogo oficial de tipos de documento.
- Centralizar sus valores y etiquetas en el backend.
- Exponer el catálogo mediante un endpoint autenticado.
- Permitir su reutilización por Player, LegalGuardian, importaciones y gestión documental.
- Mantener una fuente canónica para las especificaciones y contratos relacionados.

En esta primera iteración el catálogo será estático y estará definido por código mediante un enum compartido.

---

## No hace parte del MVP

- Crear la tabla maestra persistida.
- Crear un CRUD administrativo para catálogos.
- Permitir configuración por academia.
- Permitir que cada tenant agregue tipos de documento.
- Administrar traducciones.
- Versionar cambios del catálogo.
- Administrar documentos físicos o archivos.

---

## Valor de negocio

Una fuente compartida reduce la duplicación y permite que las funcionalidades que trabajan con identidad utilicen los mismos valores. También prepara la evolución futura hacia tablas maestras sin hacer que Player o LegalGuardian sean propietarios del catálogo.

---

## Alcance funcional inicial

El catálogo inicial debe incluir los siguientes tipos de documento:

| Valor | Etiqueta |
|---|---|
| `CE` | Cédula de extranjería |
| `CC` | Cédula de ciudadanía |
| `TI` | Tarjeta de identidad |
| `PPT` | Permiso por Protección Temporal |
| `PASSPORT` | Pasaporte |
| `RC` | Registro civil |

El catálogo será consumido inicialmente por:

- Registro y actualización de jugadores.
- Importación de jugadores.
- Gestión documental de jugadores.
- Futuras funcionalidades de `LegalGuardian`.

---

## Historias de Usuario

- HU-001 - Consultar el catálogo de tipos de documento.

---

## Evolución futura

Cuando exista una necesidad funcional confirmada, el catálogo podrá evolucionar hacia una tabla maestra. Esa evolución deberá definir previamente:

- Si el catálogo es global o configurable por academia.
- Quién puede administrarlo.
- Qué campos serán persistidos.
- Cómo se conservará la compatibilidad con valores existentes.
- Cómo se auditarán sus cambios.

La tabla maestra será una historia independiente y no forma parte de la HU-001.

---

## Consideraciones técnicas

- El catálogo debe vivir en `Shared`, no dentro de `Player` ni de `LegalGuardian`.
- Los módulos consumidores no deben duplicar los valores ni las etiquetas.
- La gestión de documentos físicos continúa perteneciendo al módulo que posee la relación de negocio correspondiente.
- El endpoint debe respetar autenticación, autorización y el envelope de respuestas vigente.
- El contrato HTTP debe documentarse en la especificación técnica asociada.

