# Especificación de Feature: Gestión de documentos del jugador

**Feature Branch**: `024-player-document-management`

**Creado**: 2026-07-30

**Estado**: Borrador

**Entrada**: Descripción del usuario: "Implementar EP-024 - Gestión de documentos del jugador."

## Aclaraciones

### Sesión 2026-07-30

- Q: En el reemplazo de un documento, ¿se permite actualizar también el tipo de documento y las observaciones, o solo el archivo? → A: El reemplazo cambia el archivo y también permite actualizar `documentType` y observaciones en la misma operación.

## Escenarios de usuario y pruebas *(obligatorio)*

### Historia de usuario 1 - Listar documentos del jugador (Prioridad: P1)

Como Owner/Admin del tenant autenticado, quiero consultar los documentos activos asociados a un jugador para entender rápidamente qué registros están disponibles.

**Motivo de la prioridad**: El listado de documentos es el punto de entrada de las demás acciones y debe existir antes de que cargar, ver, descargar, reemplazar o eliminar documentos sea útil.

**Prueba independiente**: Puede probarse consultando la sección documental de un jugador y verificando documentos activos paginados, estados vacíos y aislamiento por tenant.

**Escenarios de aceptación**:

1. **Dado** un Owner/Admin autenticado y un jugador del mismo tenant, **cuando** se solicite el listado, **entonces** el sistema devuelve únicamente sus documentos activos con metadatos de paginación.
2. **Dado** un jugador sin documentos activos, **cuando** se solicite el listado, **entonces** el sistema devuelve una colección vacía e indica que no hay documentos disponibles.
3. **Dado** un jugador de otro tenant, **cuando** se solicite el listado, **entonces** el sistema deniega el acceso sin revelar si el jugador existe.

---

### Historia de usuario 2 - Cargar documento del jugador (Prioridad: P2)

Como Owner/Admin del tenant autenticado, quiero cargar un documento para un jugador para que la academia almacene archivos de soporte en su registro.

**Motivo de la prioridad**: La carga crea los documentos que luego podrán consultarse, descargarse, reemplazarse o eliminarse.

**Prueba independiente**: Puede probarse cargando un archivo soportado para un jugador del tenant y confirmando la creación correcta de metadata y almacenamiento.

**Escenarios de aceptación**:

1. **Dado** un Owner/Admin autenticado y un jugador válido del mismo tenant, **cuando** se envíen un archivo soportado y el tipo documental, **entonces** el sistema almacena el archivo, guarda su metadata y lo asocia al jugador.
2. **Dado** un request sin archivo o sin tipo documental, **cuando** se envíe, **entonces** el sistema lo rechaza y no almacena información.
3. **Dado** un archivo que supera el tamaño máximo o usa un formato no soportado, **cuando** se valide, **entonces** el sistema lo rechaza antes del almacenamiento permanente.

---

### Historia de usuario 3 - Ver y descargar documento (Prioridad: P3)

Como Owner/Admin del tenant autenticado, quiero abrir un documento en línea o descargarlo como adjunto para revisarlo o compartirlo cuando sea necesario.

**Motivo de la prioridad**: Son las acciones principales después de crear un documento y son necesarias para la operación administrativa diaria.

**Prueba independiente**: Puede probarse accediendo al mismo documento mediante las acciones de vista y descarga, verificando entrega y control de acceso.

**Escenarios de aceptación**:

1. **Dado** un documento de un jugador del tenant, **cuando** el usuario lo abra en línea, **entonces** se muestra sin forzar la descarga cuando el formato lo permite.
2. **Dado** un documento de un jugador del tenant, **cuando** el usuario lo descargue, **entonces** se devuelve como adjunto usando el nombre original sanitizado.
3. **Dado** un documento de otro tenant o un archivo faltante, **cuando** se solicite, **entonces** el sistema deniega el acceso o devuelve un error controlado.

---

### Historia de usuario 4 - Reemplazar y eliminar documento (Prioridad: P4)

Como Owner/Admin del tenant autenticado, quiero reemplazar un documento sin cambiar su identificador y eliminar los que ya no sean necesarios para mantener actualizado el registro.

**Motivo de la prioridad**: El reemplazo y la eliminación completan el ciclo de vida, pero dependen de una carga existente y son posteriores al listado y la consulta.

**Prueba independiente**: Puede probarse reemplazando un documento y conservando su identificador, y eliminándolo para verificar que desaparece del listado y que se remueve el archivo.

**Escenarios de aceptación**:

1. **Dado** un documento de un jugador del tenant, **cuando** se reemplace con un archivo válido, **entonces** conserva su identificador y actualiza la metadata, incluido el tipo y las observaciones cuando se envíen.
2. **Dado** un documento de un jugador del tenant, **cuando** se elimine, **entonces** la metadata se elimina lógicamente y el archivo físico se remueve.
3. **Dado** un documento de otro tenant, **cuando** se solicite reemplazarlo o eliminarlo, **entonces** el sistema deniega la operación sin exponerlo.

---

### Historia de usuario 5 - Validar cargas documentales (Prioridad: P5)

Como Owner/Admin del tenant autenticado, quiero que el sistema valide las cargas antes del almacenamiento permanente para conservar únicamente archivos soportados y seguros en el registro del jugador.

**Motivo de la prioridad**: La validación protege la calidad del almacenamiento y debe controlar la carga, aunque es una preocupación de soporte y no un flujo independiente.

**Prueba independiente**: Puede probarse enviando archivos inválidos y confirmando que el sistema los rechaza antes de persistirlos.

**Escenarios de aceptación**:

1. **Dado** un archivo faltante, vacío, demasiado grande o con formato no soportado, **cuando** se valide la carga, **entonces** el sistema la rechaza.
2. **Dado** un archivo con formato soportado y dentro del límite, **cuando** se valide, **entonces** el sistema permite continuar.
3. **Dado** un nombre original con caracteres inseguros, **cuando** se acepte la carga, **entonces** el sistema conserva una versión sanitizada como metadata.

### Edge Cases

- Un jugador puede tener varios documentos del mismo tipo documental.
- El identificador del tenant siempre debe provenir del contexto autenticado, no del cliente.
- Los archivos deben almacenarse fuera del directorio público usando un nombre interno único generado por el sistema.
- Los nombres originales solo deben conservarse como metadata sanitizada.
- Los tipos documentales se limitan a `CE`, `CC`, `TI`, `PPT`, `PASSPORT` y `RC`.
- Los formatos se limitan a PDF, JPG, JPEG y PNG.
- El tamaño máximo es de 3 MB, definido como 3.145.728 bytes.
- Los documentos con soft delete no deben aparecer en listados activos.
- Si falla la persistencia de metadata después de almacenar el archivo, el sistema debe eliminar el archivo almacenado.
- Si falta un archivo, el sistema debe devolver un error controlado sin exponer rutas internas.

## Requisitos *(obligatorio)*

### Requisitos funcionales

- **FR-001**: El sistema MUST permitir que un Owner/Admin autenticado liste los documentos activos de un jugador dentro del tenant autenticado.
- **FR-002**: El sistema MUST devolver resultados paginados y metadata de paginación al listar documentos.
- **FR-003**: El sistema MUST devolver un resultado vacío cuando un jugador no tenga documentos activos.
- **FR-004**: El sistema MUST denegar el acceso cuando una acción se solicite para un jugador de otro tenant.
- **FR-005**: El sistema MUST permitir que un Owner/Admin autenticado cargue un documento para un jugador del tenant autenticado.
- **FR-006**: El sistema MUST validar el tipo documental antes de aceptar una carga.
- **FR-007**: El sistema MUST validar presencia, contenido, tamaño máximo y formato soportado antes del almacenamiento permanente.
- **FR-008**: El sistema MUST soportar los tipos `CE`, `CC`, `TI`, `PPT`, `PASSPORT` y `RC`.
- **FR-009**: El sistema MUST soportar PDF, JPG, JPEG y PNG.
- **FR-010**: El sistema MUST limitar las cargas a 3 MB, definidos como 3.145.728 bytes.
- **FR-011**: El sistema MUST conservar el nombre original como metadata sanitizada.
- **FR-012**: El sistema MUST almacenar los archivos fuera del directorio público con un nombre interno único generado por el sistema.
- **FR-013**: El sistema MUST asociar cada documento con su jugador propietario y con el usuario autenticado que lo cargó.
- **FR-014**: El sistema MUST permitir que un Owner/Admin autenticado vea un documento en línea cuando el formato lo permita.
- **FR-015**: El sistema MUST permitir que un Owner/Admin autenticado descargue un documento como adjunto.
- **FR-016**: El sistema MUST devolver el content type y comportamiento de entrega correctos para vista y descarga.
- **FR-017**: El sistema MUST permitir reemplazar un documento conservando su identificador.
- **FR-018**: El sistema MUST actualizar el archivo y la metadata cuando se reemplace un documento.
- **FR-019**: El sistema MUST permitir actualizar `documentType` y observaciones junto con el archivo en un reemplazo.
- **FR-020**: El sistema MUST permitir aplicar soft delete y eliminar el archivo físico.
- **FR-021**: El sistema MUST excluir documentos con soft delete de los listados activos.
- **FR-022**: El sistema MUST eliminar el archivo si no puede persistir la metadata después de la carga.
- **FR-023**: El sistema MUST devolver un error controlado cuando falte el archivo solicitado.
- **FR-024**: El sistema MUST no implementar OCR, validación de autenticidad, virus, expiración, versionado, cargas de acudientes, recordatorios ni reglas obligatorias en esta capacidad.

### Entidades principales *(incluir si la feature maneja datos)*

- **Player Document**: Registro de archivo asociado a un jugador, con identificador, tipo, nombre original, nombre interno, tamaño, formato, contexto propietario, auditoría y estado.
- **Document Type**: Clasificación del documento, limitada al catálogo soportado por la academia.
- **Stored File**: Archivo físico guardado fuera del directorio público y referenciado mediante un nombre interno.
- **Upload Validation Result**: Resultado de validar una carga antes del almacenamiento permanente.

## Criterios de éxito *(obligatorio)*

### Resultados medibles

- **SC-001**: Al menos el 95% de las cargas válidas se aceptan únicamente después de superar las validaciones de tipo, tamaño y formato.
- **SC-002**: Al menos el 95% de las solicitudes de vista y descarga de documentos del tenant devuelven el comportamiento correcto y conservan el control de acceso.
- **SC-003**: Los documentos eliminados dejan de aparecer inmediatamente en los listados activos en el 100% de los casos verificados.
- **SC-004**: En el 100% de los casos verificados, el aislamiento tenant impide acceder a documentos de jugadores de otro tenant.
- **SC-005**: En el 100% de los casos verificados, las cargas inválidas se rechazan antes del almacenamiento permanente y no crean registros utilizables.

## Supuestos

- El contexto autenticado ya proporciona la identidad del tenant y el rol del usuario.
- Owner/Admin son los únicos roles tenant autorizados para gestionar documentos en el MVP.
- Se reutilizan el envelope de API, Problem Details, modelo de auditoría y convenciones de Doctrine existentes.
- Un jugador puede tener varios documentos, incluso del mismo tipo.
- La feature no incorpora OCR, autenticidad, virus, expiración, recordatorios ni reglas obligatorias.
- Ya existe almacenamiento no público adecuado para documentos privados.
