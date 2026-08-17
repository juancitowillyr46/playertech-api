# HU-006 - Seleccionar equipos disponibles

| Campo | Valor |
| --- | --- |
| Épica | EP-010 Player Team Assignment |
| Tipo | Historia de Usuario |
| Prioridad | Media |
| MVP | Sí |
| Estado | Draft |
| Actor Principal | Academic Administrator |

---

# Objetivo

Permitir al frontend mostrar un autocomplete liviano de equipos disponibles para asociar a un jugador dentro de la academia autenticada.

---

# Historia de Usuario

Como administrador de academia

Quiero consultar un selector liviano de equipos disponibles

Para asignar rápidamente un jugador a uno o varios equipos sin cargar el listado completo ni traer información innecesaria.

---

# Respuesta Esperada

```json
{
  "data": [
    {
      "id": "uuid",
      "name": "Team A",
      "categoryName": "Sub 15",
      "status": "ACTIVE"
    }
  ],
  "meta": {}
}
```

---

# Alcance

* Listar sólo equipos de la academia actual.
* Soportar búsqueda parcial mediante `q`.
* Retornar un payload liviano para autocomplete.
* Exponer solamente equipos activos.
* Mantener el contrato apto para consumo progresivo mientras el usuario escribe.

---

# Reglas de Negocio

* La búsqueda debe ser parcial y pensada para autocomplete.
* El resultado debe excluir equipos inactivos.
* La respuesta debe ser liviana para no afectar la UX.
* El contrato debe servir como base para la selección de equipos en el flujo de asignación deportiva.

---

# Criterios de Aceptación

* Dado equipos activos en la academia, cuando el frontend consulta el selector, entonces el sistema devuelve coincidencias livianas para autocomplete.
* Dado un texto parcial, cuando el frontend consulta el selector con `q`, entonces el sistema responde con coincidencias parciales.
* Dado un equipo inactivo o fuera del tenant, cuando se consulta el selector, entonces el sistema no lo expone en la respuesta.

---

# Contrato Relacionado

* `GET /api/v1/academy/teams/options?q={texto}`

---

# Permisos Requeridos

* Team.Read
