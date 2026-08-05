# Contracts: Player Guardian Feature

This forlder should contain HTTP examples and contract notes forr the
player-guardian relation.

Expected items:

- associate guardian
- change primary guardian
- remove guardian association
- listar player guardians

## Removal contract note

The removal contract deletes the `player_guardians` relation physically.
It is not a soft-delete flow.
