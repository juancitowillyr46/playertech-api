# HU-003 - Consultar el catálogo de nacionalidades

## Historia de Usuario

Como usuario autenticado de la academia,
quiero consultar el catálogo oficial de nacionalidades,
para seleccionar valores válidos y consistentes en jugadores y futuras funcionalidades relacionadas.

---

## Contexto de negocio

La nacionalidad puede utilizarse en formularios, importaciones y futuras capacidades del dominio. El backend debe ofrecer una fuente única para evitar listas paralelas entre módulos y clientes.

---

## Alcance

Esta historia incluye:

- Consultar las nacionalidades disponibles.
- Devolver el valor técnico y la etiqueta visible.
- Mantener un orden estable de las opciones.
- Reutilizar el catálogo desde Player y otras funcionalidades futuras.
- Exponer el catálogo mediante un endpoint autenticado.

Esta historia no incluye:

- Crear, editar o eliminar nacionalidades.
- Configurar el catálogo por academia.
- Persistir el catálogo en una tabla maestra.
- Administrar traducciones.

---

## Precondiciones

- El usuario está autenticado.
- El usuario tiene acceso al contexto de la academia.
- La aplicación tiene disponible el catálogo compartido.

---

## Flujo principal

1. El cliente solicita el catálogo de nacionalidades.
2. El backend valida la autenticación y autorización del usuario.
3. El backend obtiene las opciones desde el catálogo compartido.
4. El backend devuelve cada opción con `value` y `label`.
5. El cliente utiliza las opciones en formularios, importaciones o validaciones.

---

## Criterios de aceptación

### AC-001 - Consultar el catálogo

**Dado** un usuario autenticado con acceso a una academia

**Cuando** solicita el catálogo de nacionalidades

**Entonces** el sistema debe devolver una respuesta exitosa

**Y** debe incluir una colección de opciones.

---

### AC-002 - Devolver valor y etiqueta

**Dado** que el catálogo contiene nacionalidades

**Cuando** se devuelve la colección

**Entonces** cada opción debe incluir:

- `value`: valor técnico estable.
- `label`: etiqueta visible para el usuario.

---

### AC-003 - Devolver los valores oficiales

**Cuando** se consulta el catálogo

**Entonces** debe incluir exactamente los valores soportados por la versión vigente:

- `COLOMBIAN` - Colombiano(a).
- `PERUVIAN` - Peruano(a).
- `CHILEAN` - Chileno(a).
- `ECUADORIAN` - Ecuatoriano(a).
- `MEXICAN` - Mexicano(a).
- `SPANISH` - Español(a).

---

### AC-004 - Mantener un orden estable

**Dado** el catálogo vigente

**Cuando** se consulta más de una vez

**Entonces** las opciones deben conservar el mismo orden mientras no exista una decisión de negocio que lo modifique.

---

### AC-005 - Reutilización entre módulos

**Dado** que Player u otras funcionalidades requieren una nacionalidad

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
- Las etiquetas no deben duplicarse en Player ni en los clientes.
- El catálogo inicial es global y estático.
- La modificación del catálogo requiere actualizar la especificación, las pruebas y los consumidores afectados.
- Esta historia no define administración ni persistencia del catálogo.

---

## Contrato HTTP propuesto

```http
GET /api/v1/academy/nationalities/options
```

### Respuesta exitosa

```json
{
  "data": [
    {
      "value": "COLOMBIAN",
      "label": "Colombiano(a)"
    },
    {
      "value": "PERUVIAN",
      "label": "Peruano(a)"
    }
  ],
  "meta": {}
}
```

La especificación técnica deberá confirmar la ruta definitiva y su compatibilidad con cualquier endpoint anterior que ya haya sido publicado.

---

## Dependencias y consumidores

- `Player`.
- Importación de jugadores.
- Futuras funcionalidades que requieran nacionalidad cerrada.
