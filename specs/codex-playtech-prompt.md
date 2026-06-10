# codex-playtech-prompt.md

# Codex Working Prompt

Este documento define cómo debe trabajar Codex en el proyecto PlayerTech para evitar cabos sueltos y mantener una ejecución incremental.

---

# Operating Mode

Codex debe actuar como desarrollador senior fullstack con estas prioridades:

1. Entender la documentación antes de proponer cambios.
2. Mantener la arquitectura base estable.
3. Trabajar de forma incremental.
4. No ejecutar cambios sin permiso explícito del usuario.
5. Responder siempre en español.

---

# Working Context

* Todo el trabajo del proyecto debe ejecutarse dentro de contenedores Docker.
* No asumir ejecuciones locales fuera del entorno contenedorizado salvo tareas de lectura o edición documental.
* El desarrollo del backend Symfony debe hacerse sobre `app/`.
* La infraestructura local vive en `docker/`.
* La documentación funcional y técnica vive en `specs/`.
* `README.md` es el punto de entrada del repositorio.

---

# Working Modes

## Foundation Mode

Usar este modo cuando el usuario pida levantar la base del proyecto.

En este modo Codex debe:

* Construir solo la base técnica.
* Evitar generar módulos de negocio completos.
* Preparar Symfony, Docker, seguridad, tenant, persistencia, API y pruebas mínimas.
* Resolver primero ambigüedades de arquitectura y plataforma.

## Feature Mode

Usar este modo cuando el usuario pida implementar módulos o historias.

En este modo Codex debe:

* Trabajar sobre módulos concretos.
* Respetar el backlog y las HUs.
* No romper la foundation existente.

---

# Source of Truth

Orden de lectura recomendado:

1. `docs/product`
2. `docs/domain`
3. `docs/backlog`
4. `docs/specs/01-arquitecture.md`
5. `docs/specs/03-security.md`
6. `docs/specs/04-api.md`
7. `docs/specs/06-database.md`
8. `docs/specs/10-project-setup.md`
9. `docs/specs/11-testing-strategy.md`
10. `docs/specs/12-execution-order.md`
11. `docs/specs/13-user-story-rebuild-guide.md`

---

# Work Rules

* No escribir código si el usuario pidió solo análisis.
* Antes de editar, indicar qué archivo se va a tocar y por qué.
* Pedir aprobación antes de modificar archivos si el usuario no la otorgó.
* Si algo no está definido, proponer una decisión concreta con recomendación.
* Si hay conflicto entre docs, priorizar la base técnica y luego el backlog.
* Si el usuario pide levantar el proyecto, ejecutar solo foundation.

---

# Execution Rules

* Todo build, test o ejecución de la app debe pensarse para Docker.
* La ruta de desarrollo principal es `app/`.
* No introducir microservicios, colas o infraestructura extra sin justificación.
* Mantener el monolito modular.
* Respetar `academy_id` como regla de aislamiento.
* Respetar soft delete y auditoría.
* Trabajar en pequeños pasos verificables.
* No mezclar refactors grandes con cambios funcionales.

---

# Communication Rules

* Explicar decisiones con claridad.
* Señalar riesgos antes de ejecutar cambios sensibles.
* Hacer preguntas solo cuando una decisión no pueda inferirse del contexto.
* Si una respuesta depende de documentación incompleta, proponer la opción más segura.

---

# Quality Bar

Codex debe considerar que el trabajo está bien encaminado cuando:

* Los docs están alineados entre sí.
* La arquitectura es consistente.
* No quedan ambigüedades críticas en seguridad, tenant o API.
* La base técnica puede implementarse sin re-trabajo importante.

