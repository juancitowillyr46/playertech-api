# Audit

Este directorio agrupa auditorias, migraciones y reportes de normalizacion documental.
Funciona como memoria histórica y operativa de la limpieza SDD, no como contrato vigente.

## Regla Principal

- La verdad vigente del proyecto vive en `specs/`, `docs/architecture/memory/project-memory.md` y, cuando aplica, en `docs/domains/`.
- `docs/audit/` sólo conserva evidencia, diagnósticos, migraciones y cierres de normalización.
- Si un hallazgo de auditoría sigue vigente como decisión de proyecto, debe reflejarse también en la fuente canónica correspondiente.

## Documentos

- `spec-kit-migration-report.md` reporte base de migracion a Spec Kit.
- `backlog-normalization-plan.md` plan operativo de normalizacion del backlog.
- `final-backlog-normalization-summary.md` cierre ejecutivo de la auditoria.
- `module-code-spec-homologation.md` homologacion transversal codigo/spec por modulo.
- `spec-kit-compliance-audit.md` auditoria final de cumplimiento del estándar.

## Uso

- registrar hallazgos;
- clasificar artefactos;
- conservar decisiones de migracion;
- abrir preguntas cuando haya conflictos entre fuentes.

## Criterio de uso

- Si el reporte describe una decisión vigente, debe reflejarse también en `specs/` o `docs/architecture/memory/project-memory.md`.
- Si el documento solo registra una auditoría o normalización, puede permanecer aquí como histórico operativo.

## Estado Actual

La carpeta ya quedó saneada como bitácora de soporte para la migración a Spec Kit / SDD.
No debe usarse para definir contratos nuevos ni para duplicar documentación de módulos.
