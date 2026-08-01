# Modelo de datos: Gestión de documentos del jugador

## PlayerDocument

Representa un documento asociado a un jugador dentro de un tenant.

### Campos

- `id`: unique document identifier
- `academyId`: tenant propietario del registro
- `playerId`: jugador propietario
- `documentType`: clasificación documental soportada
- `originalFileName`: nombre original sanitizado enviado por el usuario
- `storageName`: nombre interno único de almacenamiento
- `mimeType`: tipo MIME validado
- `fileSize`: tamaño del archivo en bytes
- `fileExtension`: extensión almacenada para entrega y validación
- `observations`: observaciones opcionales
- `uploadedBy`: usuario autenticado que creó el registro
- `updatedBy`: usuario autenticado que realizó el último reemplazo o actualización
- `status`: activo o eliminado
- `auditTrail`: timestamps y actores de creación/actualización
- `deletedAt`: timestamp de soft delete
- `deletedBy`: actor que realizó el soft delete

### Reglas de validación

- Debe pertenecer exactamente a un jugador.
- Debe pertenecer exactamente a un tenant mediante el jugador.
- `documentType` debe ser uno de los valores del catálogo compartido definido por `EP-025`: `CE`, `CC`, `TI`, `PPT`, `PASSPORT`, `RC`.
- Los formatos se limitan a PDF, JPG, JPEG y PNG.
- El tamaño máximo es 3.145.728 bytes.
- `storageName` debe generarse internamente y no puede ser controlado por el usuario.
- `originalFileName` debe sanitizarse antes de persistirlo.
- Los registros eliminados deben excluirse de los listados activos.

### Ciclo de vida

1. **Request de carga en borrador**: el usuario envía un archivo y metadata.
2. **Validado**: el request supera las verificaciones de tenant, tipo, tamaño y formato.
3. **Almacenado**: el archivo se guarda de forma privada y se crea el registro de metadata.
4. **Activo**: el documento está disponible para listar, ver, descargar, reemplazar o eliminar.
5. **Reemplazado**: conserva el identificador y actualiza el archivo y la metadata seleccionada.
6. **Eliminado**: la metadata recibe soft delete y el archivo se elimina del almacenamiento.

## PlayerDocumentCollection

Representa el resultado paginado interno usado para construir la respuesta estándar
definida en `docs/architecture/ADR-004-paginated-list-endpoints.md`.

### Campos

- `items`: documentos de la página solicitada
- `pagination`: metadata de paginación

### Metadata de paginación

- `page`: número de página actual
- `perPage`: tamaño de página
- `total`: total de documentos activos
- `totalPages`: total de páginas disponibles
- `hasNext`: indica si existe otra página
- `hasPrev`: indica si existe una página anterior

La respuesta HTTP pública transforma este resultado interno en `data` y `meta`, usando
`per_page`, `total_pages`, `has_next` y `has_prev` dentro de `meta`.

## Relaciones

- Un `PlayerDocument` pertenece a un `Player`.
- Un `Player` pertenece a un tenant (`academyId`).
- Un tenant puede tener muchos jugadores.
- Un jugador puede tener muchos documentos, incluso varios del mismo tipo.

## Reglas de error y consistencia

- Si falla la persistencia de metadata después de almacenar el archivo, el archivo debe eliminarse.
- Si falta un archivo durante vista, descarga, reemplazo o eliminación, la operación debe fallar con un error controlado.
- Un tenant diferente debe detectarse antes de exponer contenido o metadata.
- El reemplazo debe conservar el identificador del documento.
- La eliminación debe preservar la auditoría mientras remueve el archivo físico.
