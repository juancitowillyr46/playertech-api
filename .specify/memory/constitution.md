# PlayerTech SDD Constitution

Este documento define las reglas verificables para operar el proyecto bajo Spec-Driven Development.

## Principio I. Spec antes que código

- Regla: ninguna funcionalidad nueva se implementa sin una especificación aprobada.
- Justificación: evita rework y decisiones implícitas.
- Evidencia requerida: epic, HU, spec, contrato y criterio de aceptación.
- Condición de aprobación: la intención funcional puede ser entendida sin leer el código.
- Excepciones permitidas: correcciones menores sin impacto de contrato.
- Validación: revisión del backlog y del spec antes de codificar.

## Principio II. Trazabilidad obligatoria

- Regla: toda épica, HU, requisito, prueba y cambio de código debe referenciar identificadores relacionados.
- Justificación: permite reconstruir contexto sin depender del chat.
- Evidencia requerida: enlaces cruzados entre backlog, specs, contrato y `specs/14-current-state.md`.
- Condición de aprobación: cada cambio importante puede rastrearse de intención a ejecución.
- Excepciones permitidas: notas técnicas de bajo impacto.
- Validación: auditoría documental periódica.

## Principio III. Requisitos verificables

- Regla: los requisitos deben expresarse de forma comprobable y no ambigua.
- Justificación: un requisito no verificable no puede convertirse en prueba.
- Evidencia requerida: criterios de aceptación, ejemplos y reglas de negocio.
- Condición de aprobación: otro lector puede validar el requisito sin inferir intención oculta.
- Excepciones permitidas: hipótesis marcadas explícitamente como abiertas.
- Validación: revisión de claridad antes de implementar.

## Principio IV. Historias de Usuario completas

- Regla: cada HU debe incluir actor, necesidad, valor, criterios, reglas, excepciones y permisos.
- Justificación: evita backlog incompleto o narrativas vagas.
- Evidencia requerida: HU con estructura homogénea y enlazada al epic.
- Condición de aprobación: la HU describe comportamiento esperable de principio a fin.
- Excepciones permitidas: historias internas de soporte técnico.
- Validación: checklist de completitud.

## Principio V. Separación entre negocio y tecnología

- Regla: `spec` describe qué necesita el producto y por qué; `plan` describe cómo se implementa.
- Justificación: evita mezclar intención con solución.
- Evidencia requerida: documento funcional separado del plan técnico.
- Condición de aprobación: negocio y tecnología pueden leerse independientemente.
- Excepciones permitidas: notas de transición con vínculo explícito.
- Validación: revisión de estructura documental.

## Principio VI. API First

- Regla: cambios de API deben definirse antes de implementar.
- Justificación: el contrato estabiliza frontend, QA y backend.
- Evidencia requerida: referencia HTTP, ejemplos y notas de compatibilidad.
- Condición de aprobación: el contrato puede probarse contra la implementación.
- Excepciones permitidas: hotfixes con documentación posterior inmediata.
- Validación: comparación entre spec, contrato y pruebas.

## Principio VII. Seguridad y aislamiento

- Regla: autenticación, autorización, roles, permisos y aislamiento deben considerarse en toda decisión.
- Justificación: el sistema es multi-tenant y sensible a contexto.
- Evidencia requerida: reglas de acceso y tenant isolation documentadas.
- Condición de aprobación: no existen flujos críticos sin considerar seguridad.
- Excepciones permitidas: ninguna para endpoints públicos, pero deben estar justificados.
- Validación: auditoría de seguridad y pruebas de acceso.

## Principio VIII. Calidad y pruebas

- Regla: cada requisito verificable debe relacionarse con pruebas.
- Justificación: el SDD se sostiene con evidencia automatizada.
- Evidencia requerida: unit, integration o functional tests según corresponda.
- Condición de aprobación: la prueba demuestra el comportamiento esperado.
- Excepciones permitidas: documentación histórica o visión de producto.
- Validación: cobertura de tests sobre cambios relevantes.

## Principio IX. Compatibilidad

- Regla: cualquier cambio en contrato, persistencia o integración debe analizar migración y compatibilidad.
- Justificación: evita romper consumidores existentes.
- Evidencia requerida: nota de compatibilidad o plan de migración.
- Condición de aprobación: el impacto aguas arriba y abajo está identificado.
- Excepciones permitidas: cambios iniciales sin consumidores externos.
- Validación: revisión de contratos y dependencias.

## Principio X. Documentación viva

- Regla: la documentación debe evolucionar con decisiones e implementación.
- Justificación: evita documentos muertos.
- Evidencia requerida: current state y memoria actualizados.
- Condición de aprobación: el cambio relevante deja rastro documental.
- Excepciones permitidas: ajustes triviales de copy.
- Validación: auditoría periódica.

## Principio XI. No invención

- Regla: los agentes no pueden inventar reglas de negocio.
- Justificación: las inferencias no deben convertirse en verdad.
- Evidencia requerida: fuente documental o código.
- Condición de aprobación: las hipótesis quedan marcadas como abiertas.
- Excepciones permitidas: ninguna para contratos vigentes.
- Validación: revisión contra fuentes canónicas.

## Principio XII. Cambios pequeños y revisables

- Regla: las especificaciones deben dividirse en unidades revisables e independientes.
- Justificación: facilita lectura, implementación y validación.
- Evidencia requerida: docs cortos y acotados por flujo o dominio.
- Condición de aprobación: cada documento tiene propósito único.
- Excepciones permitidas: documentos canónicos de alto nivel.
- Validación: revisión de cohesión y tamaño razonable.

## Principio XIII. Taxonomía documental explícita

- Regla: cada documento debe poder clasificarse como canon, guia, contrato, feature spec, memoria o trazabilidad.
- Justificación: evita que documentos transversales compitan con features o backlog.
- Evidencia requerida: el repositorio debe tener un mapa claro de donde vive cada tipo de documento.
- Condición de aprobación: una nueva sesion puede ubicar cada archivo sin ambiguedad.
- Excepciones permitidas: documentos historicos en `_archive`.
- Validación: revisar que `specs/`, `docs/architecture/`, `docs/contracts/`, `docs/flows/`, `docs/domains/`, `docs/audit/` y `docs/traceability/` respeten su proposito.

## Principio XIV. Stack tecnico base documentado

- Regla: el stack actual de la API debe estar explicitado como referencia persistente.
- Justificación: evita dudas sobre runtime, persistencia y convenciones de implementacion.
- Evidencia requerida: PHP 8.4, Symfony 7.4, Doctrine ORM, MySQL 8+, JWT stateless, Docker, PHPUnit y XML mapping.
- Condición de aprobación: cualquier sesion puede identificar el stack base sin leer el codigo primero.
- Excepciones permitidas: actualizaciones menores de version cuando el proyecto las adopte formalmente.
- Validación: revisar que `docs/architecture/architecture-overview.md`, `docs/architecture/guides/development-standards.md`, `docs/architecture/guides/module-creation-guide.md` y `docs/architecture/guides/environment-guide.md` coincidan con la realidad.

## Principio XV. Clasificación por alcance

- Regla: una necesidad debe clasificarse primero como HU, subfeature o épica nueva antes de documentarse en profundidad.
- Justificación: evita inflar épicas pequeñas o fragmentar problemas de negocio grandes.
- Evidencia requerida: matriz de decisión aplicada y registrada en la guía conceptual o en el plan de la feature.
- Condición de aprobación: otra sesión puede explicar por qué se eligió HU, subfeature o épica sin leer el chat completo.
- Excepciones permitidas: ajustes menores de redacción o correcciones de documentación ya establecida.
- Validación: revisar si la capacidad nueva cambia o no el problema principal del dominio, si necesita mini flujo propio y si merece carpeta especÍfica.

## Principio XVI. Fuentes canónicas y precedencia

- Regla: cada decisión debe tener una única fuente normativa según su alcance: `specs/` para features vigentes, `docs/architecture/` para arquitectura, `docs/contracts/` para API, `docs/domains/` para dominio, `docs/backlog/` para intención funcional y `specs/14-current-state.md` para estado y trazabilidad.
- Justificación: evita contradicciones entre backlog, specs, contratos, ADRs y memoria técnica.
- Evidencia requerida: los documentos secundarios deben enlazar a la fuente canónica y no redefinirla.
- Condición de aprobación: un cambio puede identificar qué documento tiene autoridad sobre cada regla.
- Excepciones permitidas: documentos históricos ubicados en `_archive` o reportes de auditoría que no definan contratos.
- Validación: aplicar el mapa documental y resolver conflictos antes de implementar.

## Principio XVII. Decisiones arquitectónicas aceptadas

- Regla: los ADR en estado `Aceptado` son obligatorios para nuevas features y refactors relevantes; los ADR `Propuestos` no son reglas vigentes.
- Justificación: convierte las decisiones técnicas aprobadas en restricciones verificables sin duplicar su contenido en la constitución.
- Evidencia requerida: el plan y los contratos de una feature deben referenciar los ADR aplicables.
- Condición de aprobación: ninguna implementación contradice un ADR aceptado sin una nueva decisión formal.
- Excepciones permitidas: únicamente mediante un ADR posterior o una actualización formal del ADR existente.
- Validación: revisión de ADRs aplicables durante `speckit-plan` y `speckit-analyze`.

## Principio XVIII. Contrato HTTP canónico

- Regla: los nuevos contratos HTTP deben alinearse con `docs/contracts/api-reference.md`, usar el envelope vigente y documentar compatibilidad cuando corresponda.
- Justificación: evita que cada feature invente una variante de respuesta, naming o paginación.
- Evidencia requerida: contrato de feature, ejemplo HTTP, pruebas funcionales y referencia operativa alineados.
- Condición de aprobación: no existen diferencias no justificadas entre spec, contrato, pruebas y referencia HTTP.
- Excepciones permitidas: ninguna sin decisión arquitectónica explícita.
- Validación: contract tests y análisis cruzado de artefactos.

## Principio XIX. Contexto obligatorio antes de generar artefactos

- Regla: antes de generar plan, modelo, contratos o tareas, se deben revisar la constitución, los ADRs aplicables, la arquitectura, seguridad, testing, current state y backlog relacionado.
- Justificación: las decisiones transversales deben influir en los artefactos desde su generación, no corregirse después manualmente.
- Evidencia requerida: el plan registra las decisiones aplicables y los contratos reflejan sus convenciones.
- Condición de aprobación: los artefactos generados no contienen contradicciones con fuentes canónicas vigentes.
- Excepciones permitidas: ninguna para features con cambio de contrato, persistencia, seguridad o comportamiento.
- Validación: preflight de SpecKit y `speckit-analyze`.

## Principio XX. Trazabilidad requisito-prueba

- Regla: cada requisito verificable debe poder seguirse desde epic o HU hasta spec, contrato, tarea, prueba y current state cuando el cambio sea relevante.
- Justificación: permite validar que una implementación cumple intención y contrato, no solo que compila.
- Evidencia requerida: referencias cruzadas o matriz de trazabilidad.
- Condición de aprobación: no quedan requisitos verificables sin tarea o prueba aplicable.
- Excepciones permitidas: cambios documentales menores sin impacto funcional.
- Validación: revisión de cobertura en `speckit-tasks` y `speckit-analyze`.

## Principio XXI. Política de idioma documental

- Regla: la prosa de la documentación del proyecto y los artefactos generados por SpecKit deben escribirse en español, salvo solicitud explícita en otro idioma.
- Justificación: mantiene la comprensión del negocio y evita mezclar idiomas dentro de un mismo documento.
- Evidencia requerida: specs, planes, research, modelos, quickstarts y tareas con narrativa uniforme en español.
- Condición de aprobación: se conservan en inglés únicamente los identificadores técnicos, nombres de clases, endpoints, campos JSON, rutas y bloques de código canónicos.
- Excepciones permitidas: contratos o documentación externa que deban conservar un formato oficial, siempre que la prosa propia del proyecto siga en español.
- Validación: revisión de idioma durante `speckit-specify`, `speckit-plan`, `speckit-tasks` y `speckit-analyze`.
