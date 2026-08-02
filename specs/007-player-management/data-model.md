# Data Model: Player Feature

**Feature Branch**: `007-player-management`

## Entities

### Player

- Academy-scoped athlete entity.
- Almacena identity, contact, category, photo, status and audit data.
- Expone enriched fields in listar responseonses such as `categoryName`, `genderName`, `age` and `createdAt`.

### PlayerPhoto

- Media reference associated with a player.
- Puede be replaced or removed sin deleting the player aggregate.

## Boundary

- `PlayerImportJob` is documented in `specs/007-player-management/import/data-model.md`.
- The base feature should stay forcused on lifecycle and media behavior.
