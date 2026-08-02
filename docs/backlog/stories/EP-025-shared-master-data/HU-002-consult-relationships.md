# HU-002 - Consultar el catálogo de parentescos

## Historia de Usuario

Como usuario autenticado de la academia,
quiero consultar el catálogo oficial de parentescos,
para seleccionar valores válidos y consistentes en jugadores, acudientes y futuras funcionalidades relacionadas.

---

## Contexto de negocio

Los parentescos son utilizados por diferentes funcionalidades del sistema. El backend debe ofrecer una fuente única para que los consumidores no mantengan listas duplicadas de valores y etiquetas.

---

## Alcance

Esta historia incluye:

- Consultar los parentescos disponibles.
- Devolver el valor técnico y la etiqueta visible.
- Mantener un orden estable de las opciones.
- Reutilizar el catálogo desde Player, LegalGuardian y futuros consumidores.
- Exponer el catálogo mediante un endpoint autenticado.

Esta historia no incluye:

- Crear, editar o eliminar parentescos.
- Configurar el catálogo por academia.
- Persistir el catálogo en una tabla maestra.
- Administrar traducciones.
- Administrar documentos físicos.

---

## Precondiciones

- El usuario está autenticado.
- El usuario tiene acceso al contexto de la academia.
- La aplicación tiene disponible el catálogo compartido.

---

## Flujo principal

1. El cliente solicita el catálogo de parentescos.
2. El backend valida la autenticación y autorización del usuario.
3. El backend obtiene las opciones desde el catálogo compartido.
4. El backend devuelve cada opción con `value` y `label`.
5. El cliente utiliza las opciones en formularios, importaciones o relaciones de acudientes.

---

## Criterios de aceptación

### AC-001 - Consultar el catálogo

**Dado** un usuario autenticado con acceso a una academia

**Cuando** solicita el catálogo de parentescos

**Entonces** el sistema debe devolver una respuesta exitosa

**Y** debe incluir una colección de opciones.

---

### AC-002 - Devolver valor y etiqueta

**Dado** que el catálogo contiene parentescos

**Cuando** se devuelve la colección

**Entonces** cada opción debe incluir:

- `value`: valor técnico estable.
- `label`: etiqueta visible para el usuario.

---

### AC-003 - Devolver los valores oficiales

**Cuando** se consulta el catálogo

**Entonces** debe incluir exactamente los tipos soportados por la versión vigente:

- `FATHER` - Padre.
- `MOTHER` - Madre.
- `GRANDFATHER` - Abuelo.
- `GRANDMA` - Abuela.
- `TUTOR` - Tutor.
- `BROTHER` - Hermano.
- `SISTER` - Hermana.
- `OTHER` - Otro.

---

### AC-004 - Mantener un orden estable

**Dado** el catálogo vigente

**Cuando** se consulta más de una vez

**Entonces** las opciones deben conservar el mismo orden mientras no exista una decisión de negocio que lo modifique.

---

### AC-005 - Reutilización entre módulos

**Dado** que Player o LegalGuardian requieren un parentesco

**Cuando** validan o presentan las opciones disponibles

**Entonces** deben utilizar el catálogo compartido

**Y** no deben definir una lista paralela de valores o etiquetas.

---

### AC-006 - Rechazar acceso no autorizado

**Dado** un usuario no autenticado o sin permisos suficientes

**Cuando** solicita el catálogo

**Entonces** el sistema debe rechazar la solicitud usando el mecanismo estándar de autenticación y autorización.

---

## Reglas de negocio

- Los valores técnicos son identificadores estables del contrato.
- Las etiquetas no deben duplicarse en Player, LegalGuardian ni en los clientes.
- El catálogo inicial es global y estático.
- La modificación del catálogo requiere actualizar la especificación, las pruebas y los consumidores afectados.
- Esta historia no define administración ni persistencia del catálogo.

---

## Contrato HTTP propuesto

```http
GET /api/v1/academy/relationships/options
```

### Respuesta exitosa

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

La especificación técnica deberá confirmar la ruta definitiva y su compatibilidad con cualquier endpoint anterior que ya haya sido publicado.

---

## Dependencias y consumidores

- `Player`.
- `LegalGuardian`.
- Futuras funcionalidades relacionadas con parentesco.
