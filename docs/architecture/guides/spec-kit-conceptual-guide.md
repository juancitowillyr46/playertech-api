# Spec Kit Conceptual Guide

Esta guia existe para reforzar conceptos de Spec-Driven Development y GitHub Spec Kit sin entrar a implementar features, cambiar contratos o escribir codigo.

Su proposito es servir como lectura rapida y repetible cuando necesites recordar:

- como se organiza la documentacion;
- para que sirve cada archivo;
- como se relacionan epic, HU, spec y trazabilidad;
- que vive en `specs/` y que vive en `docs/`;
- cuando una cosa es guia, contrato o implementacion.

---

## 1. Idea central

Spec Kit no es solo una carpeta de markdown.

Es una forma de trabajar donde:

- la intencion se documenta primero;
- la decision se ordena antes de ejecutar;
- la implementacion sigue al contrato;
- y la trazabilidad no depende de recordar conversaciones previas.

La documentacion no reemplaza al codigo.
La documentacion explica, organiza y conserva contexto.

---

## 2. Capas de documentacion

### `specs/`

Contiene las fuentes canónicas del proyecto.

Aqui viven:

- arquitectura general;
- seguridad;
- API base;
- base de datos;
- estrategia de pruebas;
- orden de ejecucion;
- estado actual;
- especificaciones por feature.

### `docs/backlog/`

Contiene la intención de negocio.

Aqui viven:

- epicas;
- historias de usuario;
- prioridades;
- clasificacion funcional;
- duplicados historicos si todavia no se han archivado.

### `docs/architecture/`

Contiene decisiones tecnicas persistentes.

Aqui viven:

- ADRs;
- auditorias;
- politicas;
- memorias persistentes;
- guias de organizacion documental.

### `docs/domains/`

Contiene documentos centrales por dominio puro.

Sirve para entender:

- el limite conceptual del dominio;
- el modelo de negocio estable;
- las fronteras entre modulos.

### `docs/flows/`

Contiene flujos funcionales concretos.

Sirve para documentar:

- procesos de negocio;
- secuencias de pasos;
- UX funcional;
- comportamiento con estados o polling.

### `docs/audit/`

Contiene memoria historica de auditoria y normalizacion.

No define contrato vigente.

### `docs/traceability/`

Contiene trazabilidad entre:

- epic;
- HU;
- spec;
- contrato;
- test;
- estado actual.

---

## 3. Epic, HU y Spec

### Epic

La epic agrupa una capacidad grande de negocio.

Ejemplo:

- `EP-001 Gestion de academias`

La epic responde:

- que problema grande resolvemos;
- cual es el valor de negocio;
- que conjunto de historias pertenece al mismo paraguas.

### Historia de Usuario

La HU representa una porcion concreta, testeable y entregable.

Ejemplo:

- `HU-013 Subir escudo institucional`

La HU responde:

- que quiere el usuario;
- por que lo necesita;
- como saber que quedo bien;
- que comportamiento observable debe existir.

### Spec

El spec consolida el contrato estable de la feature.

Ejemplo:

- `specs/001-academy/spec.md`

El spec responde:

- que cubre la feature;
- que historias la componen;
- que requisitos tiene;
- que entidades participan;
- que criterios de exito la validan.

---

## 4. Given / When / Then

Ese lenguaje sirve para desmenuzar una historia en escenarios de aceptacion.

Ejemplo:

- Given una academia valida
- When el admin sube el escudo
- Then el backend guarda la referencia del media

Su proposito es hacer la historia verificable.

No sustituye a la epic ni al spec.
Solo vuelve la HU mas precisa.

---

## 5. Cuando una cosa va en cada nivel

### Va en Epic cuando

- el tema es amplio;
- la capacidad de negocio es grande;
- el flujo tiene varias historias relacionadas;
- el objetivo es de alto nivel.

Ejemplo:

- gestion de academias;
- gestion de jugadores;
- gestion financiera;
- gestion de identidad.

### Va en HU cuando

- es un slice concreto;
- se puede probar de forma independiente;
- no cambia toda la narrativa del dominio;
- forma parte de una epic existente.

Ejemplo:

- subir escudo;
- agregar coordenadas a una sede;
- importar jugadores;
- listar categorias activas.

### Matriz de decisión

Usa esta regla para decidir si algo va como HU, subfeature o épica nueva.

#### A. Va como HU cuando

- Es una porción concreta del módulo.
- No cambia el problema principal del dominio.
- Se puede probar de forma aislada.
- Añade un comportamiento puntual a una feature ya existente.

Ejemplos:

- Academy: subir escudo institucional.
- Venue: agregar coordenadas geográficas.
- Player: subir foto.
- Category: listar opciones activas.

#### B. Va como subfeature cuando

- Sigue perteneciendo al mismo módulo, pero ya tiene más de una HU relevante.
- Tiene un mini flujo o capacidad propia.
- Empieza a necesitar su propio `spec.md`, `plan.md`, `tasks.md`.

Ejemplos:

- Player Import Async.
- Venue Geo Location.
- Academy Tenant Signup.

#### C. Va como épica nueva cuando

- El problema de negocio ya es distinto.
- Tiene muchas HUs propias.
- Merece su propia narrativa de negocio.
- Otro flujo tendría que entenderlo sin depender del módulo base.

Ejemplos:

- Financial Management.
- Player Import Platform.
- Geo Coverage for Venues.
- Sport Mode.

### Va en Spec cuando

- quieres dejar el contrato estable del modulo o feature;
- necesitas reunir varias historias relacionadas;
- quieres definir el lenguaje tecnico y funcional oficial;
- quieres que otra persona pueda entender la feature sin leer todo el historial.

---

## 6. Comandos `specify` orientativos

La idea de `specify` es arrancar el flujo documental desde el nivel correcto, no escribir todo manualmente en una sola pieza.

### Si ya existe el módulo

Cuando la capacidad nueva pertenece a un módulo ya existente:

```bash
specify describe
specify clarify
specify plan
specify tasks
```

Uso recomendado:

- `describe`: capturar la intención de la HU o subfeature.
- `clarify`: resolver ambigüedades y cerrar criterios.
- `plan`: decidir cómo se organizará el trabajo.
- `tasks`: descomponer en pasos implementables.

Ejemplo:

```bash
specify describe "Player: subir foto"
specify clarify
specify plan
specify tasks
```

### Si vas a crear una subfeature

Cuando la capacidad ya tiene varias HUs y merece carpeta propia:

```bash
specify describe "Player Import Async"
specify clarify
specify plan
specify tasks
```

### Si vas a crear una épica nueva

Cuando el problema de negocio es suficientemente grande:

```bash
specify describe "Financial Management"
specify clarify
specify plan
specify tasks
```

### Regla práctica

- `describe` te ayuda a capturar la intención.
- `clarify` te ayuda a cerrar dudas.
- `plan` te ayuda a estructurar.
- `tasks` te ayuda a bajar a ejecución.

Si el tema aún cabe en una HU, no lo subas a épica.
Si ya necesita varias HUs y un mini flujo, muévelo a subfeature.
Si cambia el problema de negocio, crea una épica nueva.

---

## 7. Documentacion vs implementacion

### Documentacion

Es la guia.

Sirve para:

- ordenar el pensamiento;
- definir alcance;
- dejar trazabilidad;
- reducir ambiguedad;
- alinear frontend, backend y QA.

### Implementacion

Es el codigo real.

Sirve para:

- persistir datos;
- aplicar reglas;
- responder endpoints;
- ejecutar casos de uso.

### Tests

Son la verificacion.

Sirven para:

- confirmar que la implementacion cumple;
- proteger el contrato;
- detectar regresiones.

---

## 8. Relacion entre archivos de una feature

Una feature completa suele leerse asi:

1. `docs/backlog/epics/EP-xxx.md`
2. `docs/backlog/stories/EP-xxx/HU-yyy-*.md`
3. `specs/NNN-feature/spec.md`
4. `specs/NNN-feature/plan.md`
5. `specs/NNN-feature/research.md`
6. `specs/NNN-feature/data-model.md`
7. `specs/NNN-feature/contracts/`
8. `specs/NNN-feature/tasks.md`
9. `specs/14-current-state.md`
10. `docs/traceability/matrix.md`

---

## 8. Regla mental para no confundirse

### Epic
Cuenta la historia grande.

### HU
Describe un comportamiento concreto.

### Spec
Define la version estable del contrato.

### Flow
Explica el proceso funcional o UX.

### Domain doc
Aclara el dominio puro.

### Architecture doc
Conserva decisiones tecnicas y memoria.

### Traceability
Conecta todo.

---

## 9. Ejemplo con Academy

### Epic

- `EP-001 Gestion de academias`

### HU

- `HU-013 Subir escudo institucional`
- `HU-015 Provisionar tenant desde plataforma`

### Spec

- `specs/001-academy/spec.md`

### Flow

- `docs/flows/player/player-import-flow-spec.md` no aplica aqui;
- para Academy, el flujo relevante estaria en su documento de flujo si lo necesitas.

### Domain doc

- `docs/domains/academy/academy-domain-spec.md`

### Traceability

- `specs/14-current-state.md`
- `docs/traceability/matrix.md`

---

## 10. Ejemplo con Venue

### Epic

- `EP-002 Venue`

### HU

- `HU-019 Extender datos de contacto`
- `HU-020 Agregar coordenadas geograficas`

### Spec

- `specs/002-venue/spec.md`

### Domain doc

- `docs/domains/venue/venue-domain-spec.md`

### Decision

Si las coordenadas solo amplian un comportamiento existente, siguen siendo HU dentro de la misma epic.

Si despues aparecen mapas, geocoding y cobertura territorial, puede nacer una subfeature o una epic nueva.

---

## 11. Ejemplo con Player

### Epic

- `EP-007 Gestion de Jugadores`

### HU

- `HU-007 Importar jugadores en lote`
- `HU-009 Subir foto del jugador`

### Spec

- `specs/007-player/spec.md`

### Flow

- `docs/flows/player/player-import-flow-spec.md`
- `docs/flows/player/player-import-ux-spec.md`

### Domain doc

- `docs/domains/player/player-domain-spec.md`

---

## 12. Cuando abrir una nueva epic

Abre una epic nueva cuando:

- el problema ya no cabe natural en la epic actual;
- hay un nuevo lenguaje de negocio;
- la capacidad requiere muchas HUs propias;
- la trazabilidad se vuelve mas clara separandola;
- el dominio cambia de forma relevante.

---

## 13. Cuando basta con una HU

Usa una HU cuando:

- la mejora es puntual;
- el modulo ya existe;
- no hay nuevo lenguaje de negocio;
- el cambio se entiende como extension natural del dominio.

Ejemplos:

- coordenadas de sede;
- campo extra en player;
- option list para category;
- mejora de media o metadata.

---

## 14. Qué recordar siempre

- La epic organiza.
- La HU concreta.
- El spec estandariza.
- El flow explica el proceso.
- El domain doc aclara el dominio.
- La traceabilidad conecta la historia con la realidad.
- La implementacion sigue al contrato.
- Los tests confirman el contrato.

---

## 15. Uso recomendado

Lee esta guia cuando necesites:

- refrescar conceptos;
- decidir si algo es epic, HU o spec;
- entender donde documentar una idea;
- recordar que vive en `docs/` y que vive en `specs/`;
- separar guia, contrato e implementacion.
