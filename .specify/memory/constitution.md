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

