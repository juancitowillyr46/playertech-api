# AGENTS.md

## Propósito

Este archivo define las reglas de trabajo para agentes y colaboradores en PlayerTech.

Su objetivo es mantener consistencia técnica, trazabilidad y alineación con la arquitectura, el backlog y el estado actual del sistema.

---

## Prioridad de lectura

Antes de proponer o ejecutar cambios, revisar en este orden:

1. `specs/14-current-state.md`
2. `specs/12-execution-order.md`
3. `specs/01-arquitecture.md`
4. `specs/03-security.md`
5. `specs/04-api.md`
6. `specs/06-database.md`
7. `specs/08-dev-standards.md`
8. `specs/11-testing-strategy.md`
9. `specs/15-module-creation-guide.md`
10. `specs/17-environment-guide.md`
11. `docs/product/00-product.md`
12. `docs/product/01-vision.md`
13. `docs/product/02-target-customers.md`
14. `docs/product/03-roadmap.md`
15. `docs/domain/02-domains.md`
16. `docs/backlog/future-epics.md`
17. `docs/backlog/epics/*`
18. `docs/backlog/stories/*`
19. `docs/arquitecture/*`
20. `docs/commands/command.md`

---

## Principios del proyecto

- PlayerTech es un monolito modular.
- La aplicación es multi-tenant por `academy_id`.
- La separación entre `Domain`, `Application`, `Infrastructure` y `Presentation` es obligatoria.
- El backend vive en `app/`.
- La infraestructura local vive en `docker/`.
- La documentación funcional y técnica vive en `specs/` y `docs/`.
- `README.md` es el punto de entrada del repositorio.
- `Academy` es el módulo de referencia técnica para nuevos módulos.
- `ROLE_ROOT` opera en contexto de plataforma y no debe tratarse como usuario tenant.
- Todo usuario tenant debe tener `academy_id`.
- La auditoría y el soft delete son reglas base del sistema.

---

## Reglas de trabajo

- No escribir código si el usuario pidió solo análisis.
- No asumir decisiones de arquitectura si ya están documentadas.
- Trabajar de forma incremental y verificable.
- No mezclar refactors grandes con cambios funcionales sin necesidad.
- Mantener trazabilidad en `specs/14-current-state.md` cuando el cambio sea relevante.
- Respetar el backlog y el orden de ejecución definido en `specs/12-execution-order.md`.
- Si hay conflicto entre documentos, priorizar:
  1. `specs/14-current-state.md`
  2. `specs/12-execution-order.md`
  3. `specs/01-arquitecture.md`
  4. el backlog funcional
- No introducir tecnologías, capas o patrones nuevos sin justificación explícita.

---

## Arquitectura obligatoria

- Monolito modular.
- CQRS en `Application`.
- Dominio puro, sin Symfony ni Doctrine.
- Controllers delgados.
- Repositorios como contratos en `Domain` e implementaciones en `Infrastructure`.
- XML Mapping exclusivo para Doctrine en los módulos funcionales.
- Value Objects tipados.
- Custom Doctrine Types para IDs UUID.
- Problem Details para errores HTTP.
- Tenant isolation por `academy_id`.
- Soft delete para entidades de negocio.
- Auditoría con `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`.

---

## Validación y pruebas

- Toda validación debe ocurrir antes de ejecutar el caso de uso.
- Unit tests obligatorios para:
  - Value Objects
  - Entities
  - Aggregates
  - Domain rules
- Integration tests obligatorios para:
  - Repositories
  - Doctrine mappings
  - Tenant filters
  - Database constraints
- Functional tests obligatorios para:
  - Login
  - Autorización
  - Endpoints de API
  - Contratos HTTP
- Verificar siempre que no existan fugas cross-tenant.
- No dar por válida una implementación sin cobertura mínima cuando aplique.

---

## Entorno y ejecución

- El trabajo del proyecto debe pensarse para Docker.
- No asumir ejecución local fuera del entorno contenedorizado salvo lectura o edición documental.
- Usar `local`, `test` y `prod` como entornos separados.
- No hardcodear credenciales ni URLs dentro de tests.
- Mailpit es la herramienta base para validar correo en local.

---

## Convenciones de desarrollo

- Preferir simplicidad primero.
- Preferir composición sobre herencia.
- Escribir código explícito y legible.
- Evitar `Manager`, `Helper`, `Utils`, `Processor` y nombres genéricos.
- No usar lógica de negocio en controllers, DTOs o repositories.
- No exponer entidades Doctrine directamente en respuestas HTTP.
- No usar atributos Doctrine en el dominio salvo decisión técnica explícita documentada.
- No usar valores mágicos cuando exista una constante, enum o Value Object.

---

## Módulos y trazabilidad

- El módulo `Academy` es la referencia para construir nuevos módulos.
- Los nuevos módulos deben seguir la guía de `specs/15-module-creation-guide.md`.
- Antes de implementar un módulo nuevo, revisar las HUs relacionadas y el estado actual.
- Todo cambio importante debe dejar trazabilidad en:
  - `specs/14-current-state.md`
  - el epic correspondiente
  - las historias afectadas

---

## Reglas para ADR

- Los ADR deben ser consistentes en numeración, nombre y estado.
- Un ADR debe indicar claramente:
  - contexto
  - decisión
  - consecuencias
  - estado
- Evitar notas informales si el documento pretende ser una decisión arquitectónica formal.
- Si una decisión es temporal, marcarlo explícitamente como tal.

---

## Uso de agentes

- Este archivo define el comportamiento esperado de agentes sobre el repositorio.
- Si un agente necesita contexto adicional, debe leer primero la documentación del proyecto antes de modificar código.
- Si una instrucción del usuario entra en conflicto con estas reglas, pedir confirmación o explicar la discrepancia.
- Si una tarea requiere cambiar arquitectura, documentación o flujo de ejecución, hacerlo de forma coordinada y trazable.

---

## Qué no hacer

- No reescribir el sistema completo sin necesidad.
- No saltarse la documentación existente.
- No mover lógica entre capas sin justificación.
- No asumir que un hallazgo antiguo sigue vigente sin revisar el estado actual.
- No dejar cambios sin registrar cuando alteren la base técnica o el comportamiento funcional.
