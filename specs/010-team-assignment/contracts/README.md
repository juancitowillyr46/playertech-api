# Contracts: Player Team Assignment Feature

This folder should contain HTTP examples and contract notes for team assignment flows.

Expected items:

- assign player to team
- mark primary team
- change primary team
- finalize assignment
- view player assignments
- team autocomplete for available teams

Contract rule:

- `POST /api/v1/academy/team-assignments` accepts `isPrimary` in the request and returns `isPrimary` in the response.
- The persistence column remains `is_primary`.
