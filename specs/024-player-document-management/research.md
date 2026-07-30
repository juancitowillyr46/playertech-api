# Investigación: Gestión de documentos del jugador

## 1) Estrategia de almacenamiento

- **Decisión**: Almacenar la metadata en la base de datos y los archivos físicos en un filesystem privado fuera del directorio público.
- **Justificación**: La spec exige almacenamiento privado, nombres internos generados y nombres originales sanitizados. Esto sigue el patrón existente y evita exponer URLs públicas.
- **Alternativas consideradas**:
  - Almacenamiento público con URLs ocultas. Rechazado porque la feature no debe exponer rutas internas ni acceso público.
  - Almacenamiento BLOB en base de datos. Rechazado porque aumenta el costo operativo y complica la entrega de archivos PDF/imagen.

## 2) Límites del modelo documental

- **Decisión**: Modelar los documentos como un subdominio dedicado bajo `Player`, con metadata y ciclo de auditoría propios, separado del perfil y de los patrones de foto/media.
- **Justificación**: La feature tiene reglas distintas a las fotos: múltiples documentos, tipos repetidos, reemplazo/eliminación y entrega en línea/descarga. Un agregado dedicado mantiene coherentes las reglas.
- **Alternativas consideradas**:
  - Reutilizar el patrón del campo de foto del jugador. Rechazado porque la feature necesita múltiples registros, auditoría y flujos de eliminación/reemplazo.
  - Reutilizar directamente el contrato genérico de media. Rechazado porque los documentos tienen metadata y ciclo de vida específicos.

## 3) Estrategia de aislamiento tenant

- **Decisión**: Resolver el tenant exclusivamente desde el contexto autenticado y validar siempre las acciones mediante el jugador propietario.
- **Justificación**: La spec exige no confiar en el tenant enviado por el cliente y aplicar el aislamiento mediante el jugador.
- **Alternativas consideradas**:
  - Aceptar `academy_id` en requests. Rechazado porque viola el modelo de seguridad.
  - Resolver documentos independientemente del jugador. Rechazado porque la feature vincula cada acción al jugador.

## 4) Orden de validación de cargas

- **Decisión**: Validar presencia, pertenencia al tenant, tipo documental, tamaño, formato, consistencia MIME/archivo y sanitización del nombre antes del almacenamiento permanente.
- **Justificación**: Las cargas inválidas deben fallar antes de que el archivo sea durable y la historia exige rechazar temprano archivos no soportados o malformados.
- **Alternativas consideradas**:
  - Almacenar primero y validar después. Rechazado porque crea riesgo de limpieza y exposición temporal de contenido inválido.
  - Permitir únicamente validación por extensión. Rechazado porque la spec exige validación más segura antes del almacenamiento.

## 5) Semántica de reemplazo

- **Decisión**: El reemplazo conserva el identificador y puede actualizar el archivo, `documentType` y observaciones en la misma acción.
- **Justificación**: Esto coincide con la aclaración y mantiene estable el registro, permitiendo corregir metadata al cargar un nuevo archivo.
- **Alternativas consideradas**:
  - Reemplazo únicamente del archivo. Rechazado por la aclaración.
  - Historial completo de versiones. Rechazado porque el versionado está fuera del alcance del MVP.

## 6) Entrega para vista y descarga

- **Decisión**: La vista en línea devuelve el archivo con disposición inline cuando el formato lo permite; la descarga devuelve el mismo archivo como adjunto con el nombre original sanitizado.
- **Justificación**: La feature requiere ambos modos y la spec distingue vista en línea de descarga como adjunto.
- **Alternativas consideradas**:
  - Descargar siempre. Rechazado porque no cubre la vista en línea.
  - Renderizar siempre en un preview UI personalizado. Rechazado porque la spec solo exige entrega inline compatible con navegador.

## 7) Soft delete y eliminación física

- **Decisión**: Aplicar soft delete a la metadata y eliminar el archivo físico; excluir documentos eliminados de los listados activos.
- **Justificación**: Las reglas del proyecto exigen soft delete y la feature solicita eliminar el archivo del almacenamiento.
  - **Alternativas consideradas**:
  - Eliminación permanente de metadata. Rechazada porque el sistema usa soft delete.
  - Conservar copias después de eliminar. Rechazado porque la feature exige eliminar físicamente el archivo.

## 8) Migración de base de datos y estrategia de pruebas

- **Decisión**: Crear el schema de metadata `player_documents` mediante una nueva migración Doctrine y validarlo contra la base aislada `*_test` con pruebas de mapping, repositorio, constraints, tenant isolation y contrato HTTP.
- **Justificación**: El proyecto exige cambios de schema explícitos y verificables; los archivos físicos son estado del filesystem privado y no deben gestionarse desde SQL. La estrategia existente define las capas de prueba adecuadas.
- **Alternativas consideradas**:
  - Crear la tabla manualmente o depender solo de la generación de schema de Doctrine. Rechazado porque evita el historial versionado de despliegue.
  - Almacenar archivos físicos en la migración. Rechazado porque el ciclo de vida de archivos pertenece a la aplicación, no a la migración relacional.
