# HU-006 - Delete Player Document

**File:** `HU-006-delete-player-document.md`

## User Story

As an **Owner/Admin of the tenant**,
I want to delete a document associated with a player,
so that I can remove documents that are no longer valid, were uploaded by mistake, or should no longer be available in the player's digital record.

---

## Business Context

Occasionally, academy staff may upload an incorrect document, duplicate an existing document, or receive a request to remove an outdated file.

The system must allow authorized users to safely remove documents while preserving data consistency and maintaining an audit trail of the operation.

Deleting a document must prevent it from appearing in future document listings and must ensure that unauthorized users cannot access it.

---

## Scope

This story includes:

- Deleting an existing player document.
- Validating tenant ownership.
- Preventing unauthorized deletion.
- Removing the physical file from storage.
- Soft deleting the document metadata.
- Registering the deletion audit information.
- Returning a successful response after the operation completes.

This story does not include:

- Recovering deleted documents.
- Permanently removing audit records.
- Bulk deletion.
- Automatic document expiration.
- Version history management.

These actions are covered by future features.

---

## Preconditions

- The user is authenticated.
- The authenticated user is an Owner/Admin of the tenant.
- The player exists.
- The document exists.
- The player belongs to the authenticated user's tenant.
- The document belongs to the specified player.
- The document has not already been deleted.

---

## Main Flow

1. The Owner/Admin accesses the player's documents.
2. The Owner/Admin selects **Delete** for a document.
3. The system requests confirmation.
4. The Owner/Admin confirms the operation.
5. The system validates tenant ownership.
6. The system validates that the document belongs to the specified player.
7. The system removes the physical file from storage.
8. The system marks the document metadata as deleted.
9. The system updates the audit information.
10. The system returns a successful response.
11. The document no longer appears in active document lists.

---

## Acceptance Criteria

### AC-001 - Delete an existing document

**Given** an authenticated Owner/Admin

**And** the player belongs to the same tenant

**And** the document exists

**When** the Owner/Admin confirms the deletion

**Then** the system must remove the physical file

**And** soft delete the document metadata

**And** return a successful response.

---

### AC-002 - Document no longer appears in listings

**Given** a document has been deleted

**When** the player documents are requested

**Then** the deleted document must not be returned.

---

### AC-003 - Reject deletion from another tenant

**Given** the document belongs to another tenant

**When** the deletion is requested

**Then** the system must deny the operation

**And** must not reveal whether the document exists.

---

### AC-004 - Reject unknown document

**Given** the requested document does not exist

**When** deletion is requested

**Then** the system must return a document-not-found response.

---

### AC-005 - Reject duplicated deletion

**Given** the document has already been deleted

**When** deletion is requested again

**Then** the system must return a document-not-found response.

---

### AC-006 - Remove physical file

**Given** the document exists

**When** deletion succeeds

**Then** the associated file must be removed from storage.

---

### AC-007 - Register deletion audit

**Given** the document is deleted

**When** the operation completes

**Then** the system must register:

- Deleted by.
- Deleted at.

---

### AC-008 - Storage deletion failure

**Given** the document metadata exists

**And** the storage service cannot remove the physical file

**When** deletion is attempted

**Then** the system must reject the operation

**And** must not mark the metadata as deleted

**And** must return an error response.

---

### AC-009 - Metadata persistence failure

**Given** the physical file has already been removed

**But** the metadata cannot be persisted

**When** the operation fails

**Then** the system must return an error response

**And** register an application error for manual investigation.

---

## Business Rules

- Every document belongs to exactly one player.
- Documents can only be deleted through their associated player.
- Tenant ownership must be validated before deletion.
- Deleted documents must not appear in active queries.
- Document deletion must update the audit information.
- Physical storage paths must never be exposed.
- The deletion operation must behave as a consistent operation.
- Audit information must be preserved after deletion.
- Deleted documents cannot be deleted again.

---

## Suggested API Contract

```http
DELETE /api/v1/players/{playerId}/documents/{documentId}
```

### Path Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| playerId | UUID | Yes | Player identifier. |
| documentId | UUID | Yes | Document identifier. |

---

### Successful Response

```json
{
  "data": {
    "id": "document-id",
    "deletedAt": "2026-07-29T16:45:00-05:00",
    "deletedBy": "user-id"
  },
  "meta": {}
}
```

---

## Suggested Error Responses

### Document not found

```json
{
  "errors": [
    {
      "code": "DOCUMENT_NOT_FOUND",
      "field": null,
      "message": "The requested document was not found."
    }
  ]
}
```

---

### Unauthorized tenant

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

### Storage error

```json
{
  "errors": [
    {
      "code": "DOCUMENT_DELETE_FAILED",
      "field": null,
      "message": "The document could not be deleted."
    }
  ]
}
```

Internal storage details must never be exposed.

---

## Error Scenarios

- Player does not exist.
- Document does not exist.
- Document belongs to another tenant.
- User lacks permission.
- Storage service unavailable.
- Metadata persistence failure.
- Unexpected storage or database error.

---

## Audit Requirements

The system must register:

- Document identifier.
- Player identifier.
- Tenant identifier.
- Deleted by.
- Deleted at.

Application logs must also register failed deletion attempts.

---

## Security Considerations

- The tenant must be resolved from the authenticated context.
- Storage paths must never be returned by the API.
- Only authenticated Owner/Admin users may delete documents.
- The system must validate that the document belongs to the specified player.
- The API must not reveal whether another tenant owns the requested document.

---

## Definition of Done

- Documents can be deleted.
- Tenant isolation is enforced.
- Deleted documents no longer appear in listings.
- Physical files are removed from storage.
- Audit information is updated.
- Unauthorized deletions are rejected.
- Automated tests cover:
  - Successful deletion.
  - Document not found.
  - Unauthorized tenant.
  - Storage failure.
  - Metadata persistence failure.
- API documentation is updated.