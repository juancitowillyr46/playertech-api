# Especificación de funcionalidad: Catálogo compartido de parentescos

**Feature Branch**: `026-relationship-catalog`

**Creado**: 2026-08-02

**Estado**: Draft

**Entrada**: Épica `EP-025 - Catálogos maestros compartidos` y `HU-002 - Consultar el catálogo de parentescos`.

## Clarifications

### Session 2026-08-02

- Q: ¿Qué estrategia se aplicará a la ruta actual de parentescos frente a una ruta neutral compartida? → A: Definir la ruta neutral como contrato oficial y migrar los consumidores que apliquen.
- Q: ¿Qué usuarios pueden consultar el catálogo? → A: Cualquier usuario autenticado con contexto válido de academia; no se exige exclusivamente el rol `Owner/Admin`.

## Escenarios de usuario y pruebas

### Historia de usuario 1 - Consultar parentescos (Prioridad: P1)

Como usuario autenticado de la academia, quiero consultar el catálogo oficial de parentescos para seleccionar valores válidos y consistentes en jugadores, acudientes y futuras funcionalidades relacionadas.

**Por qué esta prioridad**: El catálogo es una dependencia compartida de varias funcionalidades. Una fuente única evita valores y etiquetas divergentes entre módulos y clientes.

**Prueba independiente**: Un usuario autorizado puede solicitar el catálogo y recibir todas las opciones vigentes con su valor técnico y etiqueta visible, sin depender de que exista un jugador, acudiente o relación concreta.

**Escenarios de aceptación**:

1. **Dado** un usuario autenticado con acceso a una academia, **cuando** solicita el catálogo de parentescos, **entonces** el sistema devuelve una colección exitosa de opciones usando el envelope API vigente.
2. **Dado** el catálogo vigente, **cuando** se devuelve una opción, **entonces** incluye los campos `value` y `label`.
3. **Dado** un cliente que consulta el catálogo varias veces, **cuando** no existe un cambio publicado del catálogo, **entonces** recibe las opciones en el mismo orden.
4. **Dado** un usuario autenticado con contexto de academia, **cuando** solicita el catálogo, **entonces** el sistema permite la consulta sin exigir que tenga el rol `Owner/Admin`.
5. **Dado** un usuario no autenticado o sin contexto de academia, **cuando** solicita el catálogo, **entonces** el sistema rechaza la solicitud según las reglas estándar de autenticación y autorización.
6. **Dado** Player, LegalGuardian o una funcionalidad futura que requiera un parentesco, **cuando** consultan o validan opciones, **entonces** utilizan el catálogo compartido y no una lista paralela.

### Casos límite

- El catálogo debe devolver una colección válida aunque el consumidor todavía no tenga registros de Player, LegalGuardian o relaciones.
- Una solicitud sin autenticación debe rechazarse sin exponer información adicional del catálogo más allá del error estándar.
- Una solicitud de un usuario sin permisos de la academia debe rechazarse con el mecanismo estándar de autorización.
- No se deben aceptar valores fuera del catálogo vigente en los consumidores que utilicen esta referencia.
- La incorporación, retiro o cambio de una opción requiere actualizar la especificación, las pruebas y los consumidores afectados.

## Requisitos

### Requisitos funcionales

- **FR-001**: El sistema DEBE ofrecer un catálogo oficial de parentescos reutilizable por Player, LegalGuardian y futuras funcionalidades.
- **FR-002**: El catálogo DEBE incluir los valores `FATHER`, `MOTHER`, `GRANDFATHER`, `GRANDMA`, `TUTOR`, `BROTHER`, `SISTER` y `OTHER` en la versión inicial.
- **FR-003**: El sistema DEBE devolver para cada opción un `value` técnico estable y un `label` visible.
- **FR-004**: El sistema DEBE mantener un orden estable para las opciones mientras no exista un cambio de catálogo publicado.
- **FR-005**: El sistema DEBE exponer el catálogo mediante un endpoint autenticado para cualquier usuario con contexto válido de academia, sin restringir la consulta exclusivamente a `Owner/Admin`.
- **FR-006**: La respuesta DEBE utilizar el envelope API vigente, incluyendo la colección en `data` y los metadatos correspondientes cuando aplique.
- **FR-007**: Los módulos consumidores NO DEBEN duplicar los valores ni las etiquetas del catálogo compartido.
- **FR-008**: El contrato DEBE documentar la compatibilidad de la ruta actualmente publicada y la ruta neutral propuesta antes de implementarse cualquier cambio de URL.
- **FR-009**: El sistema DEBE permitir validar que un valor utilizado por un consumidor pertenece al catálogo vigente.
- **FR-010**: La modificación del catálogo DEBE mantener alineados backlog, especificación, contrato, pruebas, Postman y consumidores afectados.

### Fuera de alcance

- Persistir los parentescos en una tabla maestra.
- Crear, editar o eliminar parentescos desde la aplicación.
- Configurar opciones por academia.
- Permitir que un tenant agregue parentescos dinámicos.
- Administrar traducciones o versiones del catálogo.
- Administrar documentos físicos o archivos.

### Entidades clave

- **Parentesco**: opción oficial reutilizable que representa un valor técnico estable y una etiqueta visible.
- **Catálogo de parentescos**: colección global y estática de parentescos disponibles para los módulos consumidores.
- **Consumidor del catálogo**: funcionalidad de Player, LegalGuardian o futura feature que necesita presentar o validar un parentesco.

## Contrato HTTP

La ruta oficial del catálogo será:

```http
GET /api/v1/academy/relationships/options
```

La ruta anterior que será reemplazada, si existiera alguna implementación previa, deberá documentarse como contrato legado antes de su retiro.

La respuesta exitosa debe conservar el envelope vigente:

```json
{
  "data": [
    {
      "value": "FATHER",
      "label": "Padre"
    },
    {
      "value": "MOTHER",
      "label": "Madre"
    },
    {
      "value": "GRANDFATHER",
      "label": "Abuelo"
    },
    {
      "value": "GRANDMA",
      "label": "Abuela"
    },
    {
      "value": "TUTOR",
      "label": "Tutor"
    },
    {
      "value": "BROTHER",
      "label": "Hermano"
    },
    {
      "value": "SISTER",
      "label": "Hermana"
    },
    {
      "value": "OTHER",
      "label": "Otro"
    }
  ],
  "meta": {}
}
```

El endpoint no recibe un identificador de tenant desde el cliente. El acceso debe resolverse mediante el contexto autenticado. La consulta requiere autenticación y contexto válido de academia, pero no exige el rol `Owner/Admin`.

## Criterios de éxito

### Resultados medibles

- **SC-001**: El 100% de las opciones devueltas contiene simultáneamente `value` y `label` válidos.
- **SC-002**: El 100% de los consumidores iniciales utiliza los valores oficiales sin mantener listas paralelas en su contrato o reglas de negocio.
- **SC-003**: Una consulta autorizada devuelve el catálogo completo en una sola respuesta, sin requerir consultas por tipo de parentesco.
- **SC-004**: Las solicitudes no autenticadas o no autorizadas son rechazadas en el 100% de los casos de prueba definidos.
- **SC-005**: Los cambios futuros del catálogo pueden rastrearse desde la HU hasta el contrato, las pruebas y los consumidores afectados.

## Supuestos

- El catálogo inicial es global y estático para todas las academias.
- La autenticación y el contexto de academia existentes se reutilizan; cualquier usuario autenticado con contexto válido puede consultar el catálogo.
- El envelope API vigente se mantiene sin introducir una nueva forma de respuesta.
- Postman continúa siendo la referencia operativa ejecutable mientras no exista una documentación Swagger/OpenAPI interactiva.
- La futura tabla maestra será una historia independiente y definirá posteriormente si el catálogo puede configurarse por academia.
