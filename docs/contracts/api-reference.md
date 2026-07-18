# API Contracts Index

Este indice agrupa los contratos HTTP vigentes de PlayerTech para facilitar la sincronizacion entre backend, frontend y QA.

## Canonical Sources

1. [`specs/16-api-reference.md`](../../specs/16-api-reference.md)
2. [`postman/`](../../postman)
3. [`specs/04-api.md`](../../specs/04-api.md) como marco general de la API

## Purpose

- Mantener una sola referencia operativa para la API HTTP.
- Evitar duplicar decisiones de contrato en multiples documentos.
- Servir como punto de entrada rapido para integracion con frontend.

## Current Rules

- `specs/16-api-reference.md` concentra el contrato operativo.
- `postman/` contiene la coleccion importable para validacion manual.
- `specs/04-api.md` conserva convenciones generales, formatos base y principios.
- Cuando un endpoint cambie, deben actualizarse al mismo tiempo el handler, los tests y la referencia operativa.

## Working Note

Si un contrato ya esta estabilizado, debe vivir aqui solo como indice y no como segunda version del mismo contenido.
