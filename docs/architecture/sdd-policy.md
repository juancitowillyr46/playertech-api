# SDD Adoption Policy

Este documento define como se adopta Spec-Driven Development en PlayerTech cuando el proyecto lo trabaja una sola persona y un agente.

La meta es combinar trazabilidad real con velocidad de iteracion.

---

## Objetivo

- Mantener una fuente canónica para cada decision relevante.
- Evitar duplicacion documental innecesaria.
- Escalar formalidad solo cuando el cambio lo amerite.
- Hacer que futuras features nazcan con trazabilidad y contrato desde el inicio.

---

## Niveles de trabajo

### 1. SDD Liviano

Usar para:

- correcciones pequenas;
- ajustes de copy o doc;
- cambios internos sin impacto de contrato;
- refactors locales sin cambio funcional.

Requiere:

- nota breve en `specs/14-current-state.md` si el cambio es relevante;
- revisión de `specs/16-api-reference.md` si afecta HTTP;
- actualizacion del backlog si cambia el alcance funcional.

### 2. SDD Completo

Usar para:

- nuevas features;
- cambios de reglas de negocio;
- cambios de contrato HTTP;
- cambios multi-tenant o de autorizacion;
- cambios que requieran nuevas pruebas o modifiquen comportamiento visible.

Requiere:

- identificador de cambio;
- objetivo;
- alcance;
- reglas afectadas;
- criterios de aceptacion;
- contratos afectados;
- plan de prueba;
- trazabilidad en `specs/14-current-state.md`.

---

## Regla de escalamiento

Si un cambio cumple cualquiera de estas condiciones, debe tratarse como SDD completo:

- modifica un endpoint visible;
- introduce o cambia una regla de negocio;
- afecta seguridad, tenant isolation o auditoria;
- cambia una transicion de estado;
- impacta una historia del backlog;
- requiere pruebas nuevas o actualizadas.

Si no cumple ninguna, puede tratarse como SDD liviano.

---

## Fuentes de verdad

- `specs/` contiene reglas vigentes y contratos canónicos.
- `docs/backlog/` contiene intencion funcional, historias y priorizacion.
- `docs/contracts/api-reference.md` contiene el indice operativo de contratos.
- `specs/16-api-reference.md` contiene la referencia HTTP principal.
- `specs/14-current-state.md` contiene la bitacora de cambios relevantes.
- `docs/architecture/` contiene ADR, auditorias y plantillas de cambio.

---

## Flujo para futuras features

### Paso 1

Definir el cambio y decidir si es liviano o completo.

### Paso 2

Si es completo, crear el documento de cambio usando la plantilla correspondiente.

### Paso 3

Actualizar backlog, specs y referencia HTTP antes o junto con el codigo.

### Paso 4

Implementar o ajustar pruebas segun el contrato o la regla nueva.

### Paso 5

Registrar el cierre en `specs/14-current-state.md`.

---

## Reglas para el agente

- No inventar reglas de negocio no documentadas.
- No tratar una inferencia como verdad definitiva.
- No duplicar contratos entre documentos sin necesidad.
- No bajar la formalidad de un cambio importante solo por velocidad.
- No elevar un cambio pequeno a proceso completo sin razon.

---

## Resultado esperado

Con esta politica, cada feature futura debe entrar al repo con el nivel minimo de documentacion que necesita para ser mantenible sin convertir el proyecto en burocracia.
