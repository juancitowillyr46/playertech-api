# Research: Player Import

## Notas de alcance

La importación de jugadores es un flujo asíncrono que pertenece al dominio `Player`, pero no al lifecycle base.

## Resumen de decisiones

- La categoría se selecciona antes de subir el archivo.
- El archivo oficial viene desde backend.
- El job debe poder consultarse por polling.
- El frontend necesita summary y row errors.
- El flujo debe ser compatible con hosting de capacidad reducida.

## Preguntas abiertas

- ¿Conviene devolver el detalle completo de errores en el mismo `GET /import/{jobId}` o separar un endpoint posterior?
- ¿La plantilla debe incluir solo referencias visibles o también validaciones internas?
