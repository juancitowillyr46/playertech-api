# Guía rápida: Gestión de documentos del jugador

## Propósito

Validar de extremo a extremo la gestión documental de un jugador para un Owner/Admin del tenant.

## Prerrequisitos

- Un backend PlayerTech ejecutándose en el entorno Docker estándar.
- Un usuario autenticado con rol Owner/Admin para un tenant.
- Al menos un jugador perteneciente al tenant autenticado.
- Un segundo tenant o jugador conocido de otro tenant para las pruebas de acceso.
- Archivos de prueba en los formatos soportados:
  - PDF
  - JPG
  - JPEG
  - PNG
- Un archivo mayor a 3 MB para validación negativa.

## Escenarios de validación

### 0) Preparar schema y entorno de pruebas

1. Iniciar el entorno Docker estándar.
2. Ejecutar la migración Doctrine en el entorno correspondiente.
3. Ejecutar `php bin/console doctrine:mapping:info` dentro del contenedor de aplicación.
4. Ejecutar la prueba de integración del repositorio contra la base aislada `*_test`.

Resultado esperado:

- Existe la tabla `player_documents` con sus foreign keys, campos de auditoría, soft delete e índices por tenant.
- La migración no gestiona ni crea archivos documentales físicos.
- Las pruebas no apuntan a las bases local ni productiva.

### 1) Listar documentos

1. Solicitar el listado de documentos del jugador.
2. Confirmar que la respuesta está paginada.
3. Confirmar que solo se devuelven documentos activos.
4. Confirmar que se devuelve el estado vacío cuando no hay documentos.

Resultado esperado:

- Se listan los documentos activos del jugador del tenant.
- La metadata de paginación está presente.
- No es posible acceder al jugador de otro tenant.

### 2) Cargar un documento válido

1. Enviar una carga multipart con `documentType`, `file` y `observations` opcional.
2. Usar un formato soportado y un tamaño menor o igual a 3 MB.
3. Confirmar que la respuesta incluye la metadata creada.

Resultado esperado:

- El archivo se almacena de forma privada.
- El nombre original se conserva únicamente como metadata sanitizada.
- El documento creado queda asociado al jugador seleccionado.

### 3) Rechazar cargas inválidas

1. Enviar una carga sin archivo.
2. Enviar una carga sin tipo documental.
3. Enviar una carga con un archivo mayor a 3 MB.
4. Enviar una carga con formato no soportado.

Resultado esperado:

- Cada request inválido es rechazado.
- No se crea ningún archivo permanente ni registro documental.

### 4) Ver en línea

1. Solicitar la vista en línea de un documento PDF o imagen almacenado.
2. Confirmar que la respuesta usa entrega inline.

Resultado esperado:

- El archivo se abre en línea cuando el formato lo permite.
- La ruta física de almacenamiento no se expone.

### 5) Descargar como adjunto

1. Solicitar el endpoint de descarga de un documento almacenado.
2. Confirmar que la respuesta usa entrega como adjunto.
3. Confirmar que el nombre coincide con el nombre original sanitizado.

Resultado esperado:

- El navegador descarga el archivo como adjunto.
- El nombre original se conserva.

### 6) Reemplazar un documento

1. Solicitar el reemplazo de un documento existente.
2. Proporcionar un archivo nuevo y, si es necesario, un tipo documental y observaciones nuevos.
3. Confirmar que el identificador permanece igual.

Resultado esperado:

- El documento continúa vinculado al mismo jugador.
- El archivo y la metadata seleccionada se actualizan.
- El archivo anterior se elimina después del éxito.

### 7) Eliminar un documento

1. Solicitar la eliminación de un documento existente.
2. Confirmar que el documento desaparece de los listados activos.

Resultado esperado:

- La metadata recibe soft delete.
- El archivo físico se elimina.
- El documento no vuelve a aparecer como activo.

## Artefactos de referencia

- Spec: [`spec.md`](./spec.md)
- Data model: [`data-model.md`](./data-model.md)
- Contracts: [`contracts/`](./contracts)
- Migration and test conventions: [`docs/database/database-standards.md`](../../docs/database/database-standards.md), [`docs/database/migration-standards.md`](../../docs/database/migration-standards.md), [`docs/architecture/guides/testing-strategy.md`](../../docs/architecture/guides/testing-strategy.md)
