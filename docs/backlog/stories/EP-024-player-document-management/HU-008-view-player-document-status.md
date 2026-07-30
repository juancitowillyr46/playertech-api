# HU-008 - View Player Document Status

**File:** `HU-008-view-player-document-status.md`

## User Story

As an **Owner/Admin of the tenant**,
I want to view the document status of a player,
so that I can quickly determine whether the player has uploaded documents without reviewing the complete document list.

---

## Business Context

Academy staff frequently need to know whether a player's documentation has already been uploaded before requesting additional documents from the player's guardian.

Instead of opening the complete document list, the system should provide a simple status indicating whether documents are available.

This status helps identify players who still require documentation while keeping the MVP independent of mandatory document rules.

---

## Scope

This story includes:

- Calculating the document status for a player.
- Displaying the current document status.
- Returning the status through the API.
- Determining the status based only on active documents.
- Ignoring deleted documents.

This story does not include:

- Identifying missing mandatory document types.
- Document expiration validation.
- Document quality validation.
- OCR validation.
- Automatic reminders.
- Compliance scoring.
- Dashboard statistics.

These capabilities may be implemented in future releases.

---

## Preconditions

- The user is authenticated.
- The authenticated user is an Owner/Admin of the tenant.
- The player exists.
- The player belongs to the authenticated user's tenant.

---

## Status Values

The initial MVP supports the following document statuses:

| Value | Description |
|--------|-------------|
| `NO_DOCUMENTS` | The player has no active documents. |
| `DOCUMENTS_AVAILABLE` | The player has one or more active documents. |

Only these values are valid in the MVP.

---

## Main Flow

1. The Owner/Admin accesses the player's information.
2. The system validates tenant ownership.
3. The system retrieves the player's active documents.
4. The system determines the document status.
5. The system returns the calculated status.
6. The interface displays the current document status.

---

## Acceptance Criteria

### AC-001 - Player without documents

**Given** the player has no active documents

**When** the document status is requested

**Then** the system must return `NO_DOCUMENTS`.

---

### AC-002 - Player with documents

**Given** the player has one or more active documents

**When** the document status is requested

**Then** the system must return `DOCUMENTS_AVAILABLE`.

---

### AC-003 - Ignore deleted documents

**Given** the player only has deleted documents

**When** the document status is calculated

**Then** the system must return `NO_DOCUMENTS`.

---

### AC-004 - Tenant isolation

**Given** the player belongs to another tenant

**When** the document status is requested

**Then** the system must deny the operation

**And** must not reveal whether the player exists.

---

### AC-005 - Player not found

**Given** the requested player does not exist

**When** the document status is requested

**Then** the system must return a player-not-found response.

---

### AC-006 - Automatic status update

**Given** a document is uploaded or deleted

**When** the document status is requested again

**Then** the system must reflect the current status without requiring manual updates.

---

## Business Rules

- The document status is calculated dynamically.
- Only active documents participate in the calculation.
- Deleted documents must be ignored.
- A player with at least one active document has the status `DOCUMENTS_AVAILABLE`.
- A player without active documents has the status `NO_DOCUMENTS`.
- Tenant ownership must be validated before calculating the status.
- The status must never be stored as a persistent value in the database.
- The status must always represent the current state of the player's documents.

---

## Suggested API Contract

```http
GET /api/v1/players/{playerId}/document-status
```

### Path Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `playerId` | UUID | Yes | Player identifier. |

---

### Successful Response

```json
{
  "data": {
    "playerId": "player-id",
    "status": "DOCUMENTS_AVAILABLE"
  },
  "meta": {}
}
```

---

## Suggested Error Responses

### Player not found

```json
{
  "errors": [
    {
      "code": "PLAYER_NOT_FOUND",
      "field": null,
      "message": "The requested player was not found."
    }
  ]
}
```

---

### Unauthorized access

```json
{
  "errors": [
    {
      "code": "ACCESS_DENIED",
      "field": null,
      "message": "You do not have permission to perform this operation."
    }
  ]
}
```

---

## Error Scenarios

- Player does not exist.
- Player belongs to another tenant.
- Authenticated user does not have permission.
- Unexpected database error.

---

## Audit Requirements

Viewing a document status does not require a persistent audit record in the MVP.

Application logs may register:

- Authenticated user.
- Tenant identifier.
- Player identifier.
- Request timestamp.

---

## Security Considerations

- Tenant ownership must be validated before calculating the status.
- Only authenticated Owner/Admin users may access the document status.
- Deleted documents must never influence the calculated status.
- The API must not expose information about players belonging to other tenants.

---

## Definition of Done

- Document status is calculated dynamically.
- Only active documents are considered.
- Deleted documents are ignored.
- Tenant isolation is enforced.
- Status values are limited to:
  - `NO_DOCUMENTS`
  - `DOCUMENTS_AVAILABLE`
- Automated tests cover:
  - Player without documents.
  - Player with documents.
  - Only deleted documents.
  - Unauthorized tenant.
  - Player not found.
- API documentation is updated.