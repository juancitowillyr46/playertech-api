# Import Template Contract

## Propósito

Documento de referencia para la descarga oficial de la plantilla Excel.

## Comportamiento esperado

- Responde con un archivo `.xlsx`.
- Incluye las hojas `Referencias` y `Datos`.
- La hoja `Referencias` debe mostrar primero las categorías activas del tenant.
- La hoja `Referencias` debe incluir un bloque de instrucciones, una tabla de formatos correctos y tablas de referencia para `documentType`, `nationality`, `dominantFoot` y `gender`.
- La tabla de referencia de `documentType` debe consumir el catálogo compartido definido por `EP-025`.
- La hoja `Datos` debe incluir solo encabezados y celdas vacías.
- Usa datos válidos del tenant autenticado.
