# Guía de integración de contratos con frontend

Esta guía define cómo comunicar cambios de API al frontend cuando backend y frontend se desarrollan en ramas, repositorios o chats Codex separados.

## Fuente canónica

Los contratos publicados en `specs/*/contracts/` son la fuente canónica para el consumo frontend. El frontend no debe inferir rutas, campos, valores, envelope ni errores leyendo directamente controladores o entidades backend.

Postman es la referencia operativa para ejecutar y comprobar el contrato, pero no reemplaza la especificación escrita.

## Handoff mínimo

Cada cambio de contrato debe comunicarse con estos datos:

- commit backend de referencia
- ruta del contrato en `specs/*/contracts/`
- endpoint afectado
- compatibilidad o cambios incompatibles
- colección o request de Postman relacionado

Ejemplo:

```text
Implementa el consumo frontend del contrato backend existente.

Fuente canónica:
specs/025-document-type-catalog/contracts/document-type-options.md

Endpoint:
GET /api/v1/academy/document-types/options

Commit backend de referencia:
73eae48

Lee el contrato completo antes de escribir código. Respeta el API envelope,
los nombres de campos, los valores estables y los errores definidos. No
inventes rutas ni modifiques el contrato backend. Revisa los patrones HTTP
existentes del frontend y agrega las pruebas correspondientes.
```

## Flujo entre chats Codex

1. Backend actualiza el contrato, la implementación, Postman y la trazabilidad requerida.
2. Backend crea un commit identificable.
3. El chat Codex de frontend lee el contrato desde el commit o desde la ruta compartida.
4. Frontend implementa tipos, cliente HTTP, estado de carga, errores y consumo visual.
5. Frontend valida contra el endpoint real o la colección Postman.
6. Cualquier diferencia se reporta como cambio de contrato, no como ajuste silencioso del consumidor.

## Reglas de compatibilidad

- Agregar campos opcionales no rompe consumidores existentes.
- Renombrar o eliminar campos, cambiar tipos, valores o rutas requiere documentar incompatibilidad.
- Los valores `value` de catálogos deben tratarse como identificadores estables.
- Las etiquetas `label` pueden presentarse en UI, pero no deben utilizarse como identificadores de negocio.
- El frontend no debe duplicar catálogos oficiales si existe un endpoint compartido.

## Validación

Antes de cerrar la integración, frontend debe confirmar:

- autenticación y contexto tenant
- método y ruta exactos
- API envelope
- tipos de respuesta
- estados de carga, vacío y error
- pruebas del cliente o componente afectado

