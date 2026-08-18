# HU-007 - Seleccionar equipos disponibles para un jugador

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

Permitir al frontend mostrar un autocomplete contextual de equipos disponibles para un jugador específico, excluyendo los equipos que ya estén asignados activamente a ese jugador.

---

# Historia de Usuario

Como administrador de academia

Quiero consultar un selector liviano de equipos disponibles para un jugador

Para asignarle un nuevo equipo sin repetir relaciones activas ya existentes.

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

* Listar sólo equipos activos de la academia actual.
* Excluir equipos ya asignados activamente al jugador consultado.
* Soportar búsqueda parcial mediante `q`.
* Retornar un payload liviano para autocomplete.
* Mantener el contrato apto para consumo progresivo mientras el usuario escribe.

---

# Reglas de Negocio

* La búsqueda debe ser parcial y pensada para autocomplete.
* El resultado debe excluir equipos inactivos.
* El resultado debe excluir equipos que ya tengan una asignación activa con el jugador.
* La respuesta debe ser liviana para no afectar la UX.
* El contrato debe servir como base para la selección de equipos en el flujo de asignación deportiva.

---

# Criterios de Aceptación

* Dado equipos activos en la academia, cuando el frontend consulta el selector para un jugador, entonces el sistema devuelve coincidencias livianas para autocomplete.
* Dado un texto parcial, cuando el frontend consulta el selector con `q`, entonces el sistema responde con coincidencias parciales.
* Dado un equipo inactivo, fuera del tenant o ya asignado activamente al jugador, cuando se consulta el selector, entonces el sistema no lo expone en la respuesta.

---

# Contrato Relacionado

* `GET /api/v1/academy/players/{playerId}/teams/options?q={texto}`

---

# Permisos Requeridos

* Team.Read
