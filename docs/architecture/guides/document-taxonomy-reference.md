# Document Taxonomy Reference

Esta referencia clasifica la documentacion del proyecto PlayerTech para distinguir entre canon, guia, contrato y feature spec.

Su objetivo es evitar confusiones semanticas entre:

- documentos que definen la verdad base del backend;
- documentos que explican como trabajar;
- documentos que exponen contratos;
- documentos que describen features concretas.

---

## 1. Canon

### Definicion

Documento que fija una verdad transversal del proyecto.

### Para que sirve

- arquitectura;
- seguridad;
- API base;
- base de datos;
- estandares;
- estado actual;
- memoria persistente;
- modelo de dominio;
- estrategia de pruebas;
- entorno;
- observabilidad.

### Ejemplos

- `docs/product/product-vision.md`
- `docs/architecture/architecture-overview.md`
- `docs/domains/domain-overview.md`
- `docs/security/security-overview.md`
- `docs/contracts/api-principles.md`
- `docs/database/database-standards.md`
- `specs/14-current-state.md`
- `docs/contracts/api-reference.md`
- `docs/domains/billing/financial-domain-model.md`

---

## 2. Guia

### Definicion

Documento que explica como trabajar, como organizar o como entender el sistema.

### Para que sirve

- reforzar conceptos;
- orientar implementacion;
- explicar convenciones;
- documentar decisiones de uso;
- servir como onboarding tecnico.

### Ejemplos

- `docs/architecture/guides/development-standards.md`
- `docs/architecture/guides/project-setup-guide.md`
- `docs/architecture/guides/testing-strategy.md`
- `docs/architecture/guides/execution-order-guide.md`
- `docs/architecture/guides/user-story-rebuild-guide.md`
- `docs/architecture/guides/module-creation-guide.md`
- `docs/architecture/guides/environment-guide.md`
- `docs/architecture/guides/observability-local-guide.md`
- `docs/architecture/guides/spec-kit-conceptual-guide.md`

---

## 3. Contrato

### Definicion

Documento que describe la forma operativa de consumo o de salida.

### Para que sirve

- requests;
- responses;
- payloads;
- paginacion;
- codigos de respuesta;
- ejemplos HTTP;
- compatibilidad con frontend y QA.

### Ejemplos

- `docs/contracts/api-reference.md`
- `docs/contracts/api-principles.md`
- `specs/[###-feature]/contracts/README.md`

---

## 4. Feature Spec

### Definicion

Documento que describe una capacidad concreta del sistema.

### Para que sirve

- agrupar historias de usuario relacionadas;
- definir alcance funcional;
- documentar el modelo de datos de una feature;
- mantener contratos y tareas ordenadas.

### Ejemplos

- `specs/001-academy/`
- `specs/002-venue/`
- `specs/003-identity/`
- `specs/007-player/`
- `specs/009-membership/`
- `specs/012-charge-payment/`

---

## 5. Capas de soporte

### Memoria y estado

- `docs/architecture/memory/project-memory.md`
- `docs/audit/*`
- `docs/traceability/*`

### Dominio

- `docs/domains/*`

### Flujos

- `docs/flows/*`

### Backlog

- `docs/backlog/*`

---

## 6. Regla practica

- Si fija una verdad transversal, es canon.
- Si explica como trabajar, es guia.
- Si define un request o una response, es contrato.
- Si describe una capacidad de negocio concreta, es feature spec.

---

## 7. Lectura recomendada

1. Leer el canon.
2. Leer la guia relevante.
3. Revisar el contrato si aplica.
4. Consultar la feature spec.
5. Verificar estado actual y trazabilidad.
