# Modelo de datos: Catálogo compartido de parentescos

Esta feature no introduce persistencia ni migraciones. El catálogo inicial es global, estático y definido por código.

## Relationship

Representa un parentesco oficial que puede ser utilizado por varios módulos.

### Campos conceptuales

| Campo | Tipo conceptual | Requerido | Regla |
|---|---|---:|---|
| `value` | string | Sí | Identificador técnico estable. |
| `label` | string | Sí | Etiqueta visible para el usuario. |

### Valores iniciales

| `value` | `label` |
|---|---|
| `FATHER` | Padre |
| `MOTHER` | Madre |
| `GRANDFATHER` | Abuelo |
| `GRANDMA` | Abuela |
| `TUTOR` | Tutor |
| `BROTHER` | Hermano |
| `SISTER` | Hermana |
| `OTHER` | Otro |

## RelationshipCatalog

Representa la colección global y ordenada de opciones disponibles.

### Reglas

- No tiene `academy_id` en esta iteración.
- No tiene estado persistido.
- No tiene identidad independiente ni ciclo de vida administrativo.
- `value` debe permanecer estable una vez publicado.
- `label` es la representación visible y puede requerir actualización coordinada si cambia.
- El orden de las opciones forma parte del comportamiento observable del catálogo.

## Consumidores

Player, LegalGuardian y futuras funcionalidades consumen el catálogo. Ninguno debe crear una copia normativa de sus valores.

## Evolución futura

Una futura tabla maestra requerirá una especificación independiente que defina identidad, unicidad, activación, auditoría, alcance global o tenant y compatibilidad con los valores ya publicados.
