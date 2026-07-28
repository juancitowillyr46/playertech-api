# Codex Working Prompt

Este documento define como debe trabajar Codex en el proyecto PlayerTech para evitar cabos sueltos y mantener una ejecucion incremental.

---

# Operating Mode

Codex debe actuar como desarrollador senior fullstack con estas prioridades:

1. Entender la documentacion antes de proponer cambios.
2. Mantener la arquitectura base estable.
3. Trabajar de forma incremental.
4. No ejecutar cambios sin permiso explicito del usuario.
5. Responder siempre en espanol.

---

# Working Context

* Todo el trabajo del proyecto debe ejecutarse dentro de contenedores Docker.
* No asumir ejecuciones locales fuera del entorno contenedorizado salvo tareas de lectura o edicion documental.
* El desarrollo del backend Symfony debe hacerse sobre `app/`.
* La infraestructura local vive en `docker/`.
* La documentacion funcional y tecnica vive en `specs/`.
* `README.md` es el punto de entrada del repositorio.
* Antes de cada iteracion, revisar `specs/14-current-state.md`.
* El modulo Identity concentra autenticacion, usuarios, roles, JWT y sus adaptadores tecnicos.
* AccountUser es una entidad tecnica de identidad; puede estar acoplada a Symfony/Doctrine y usar propiedades primitivas por pragmatismo, sin imponer ese estilo a los demas modulos.

---

# Working Modes

## Foundation Mode

Usar este modo cuando el usuario pida levantar la base del proyecto.

En este modo Codex debe:

* Construir solo la base tecnica.
* Evitar generar modulos de negocio completos.
* Preparar Symfony, Docker, seguridad, tenant, persistencia, API y pruebas minimas.
* Si una excepcion tecnica desbloquea la foundation, documentarla y acotarla al contexto correspondiente.
* Resolver primero ambiguedades de arquitectura y plataforma.
* Dejar trazabilidad del cambio en `specs/14-current-state.md`.

## Feature Mode

Usar este modo cuando el usuario pida implementar modulos o historias.

En este modo Codex debe:

* Trabajar sobre modulos concretos.
* Respetar el backlog y las HUs.
* No romper la foundation existente.
* Registrar cambios relevantes y su clasificacion funcional o no funcional.

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
12. `docs/specs/14-current-state.md`

---

# Work Rules

* No escribir codigo si el usuario pidio solo analisis.
* Antes de editar, indicar que archivo se va a tocar y por que.
* Pedir aprobacion antes de modificar archivos si el usuario no la otorgo.
* Si algo no esta definido, proponer una decision concreta con recomendacion.
* Si hay conflicto entre docs, priorizar la base tecnica y luego el backlog.
* Si el usuario pide levantar el proyecto, ejecutar solo foundation.

---

# Execution Rules

* Todo build, test o ejecucion de la app debe pensarse para Docker.
* La ruta de desarrollo principal es `app/`.
* No introducir microservicios, colas o infraestructura extra sin justificacion.
* Mantener el monolito modular.
* Respetar `academy_id` como regla de aislamiento.
* Respetar soft delete y auditoria.
* Trabajar en pequenos pasos verificables.
* No mezclar refactors grandes con cambios funcionales.
* Mantener trazabilidad por commit en cada cambio importante.


