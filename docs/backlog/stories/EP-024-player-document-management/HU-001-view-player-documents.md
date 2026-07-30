# HU-001 - View Player Documents

**File:** `HU-001-view-player-documents.md`

## User Story

As an **Owner/Admin of the tenant**,
I want to view the documents associated with a player,
so that I can quickly verify which documents are available in the player's digital record.

---

## Business Context

During competitions, registrations, training sessions, or administrative processes, the academy may need immediate access to a player's documents.

Currently, these documents may be dispersed across WhatsApp conversations, emails, physical folders, or personal devices. This makes them difficult to locate when they are urgently required.

The system must provide a centralized, paginated view of all documents associated with a player.

---

## Scope

This story includes:

- Displaying the documents associated with a player.
- Displaying the most relevant document metadata.
- Returning the document list using pagination.
- Returning pagination metadata.
- Showing an empty state when the player has no documents.
- Restricting access to the tenant that owns the player.
- Providing access to the actions available for each document.

This story does not include:

- Uploading documents.
- Replacing documents.
- Deleting documents.
- Previewing document content.
- Downloading document files.
- Filtering by document type.
- Searching by filename.
- User-defined sorting.

These actions are covered by separate user stories.

---

## Preconditions

- The user is authenticated.
- The authenticated user is an Owner/Admin of the tenant.
- The player exists.
- The player belongs to the authenticated user's tenant.

---

## Main Flow

1. The Owner/Admin accesses the player's detail page.
2. The Owner/Admin opens the **Documents** section.
3. The system requests the first page of the player's documents.
4. The system validates tenant ownership.
5. The system retrieves the requested page of active documents.
6. The system returns the document list together with the pagination metadata.
7. The Owner/Admin can navigate through the available pages.

---

## Information to Display

For each document, the system should display:

- Document identifier.
- Document type.
- Original filename.
- File format.
- File size.
- Upload date.
- User who uploaded the document.
- Optional observations.
- Available actions.

---

## Acceptance Criteria

### AC-001 - Display player documents

**Given** an authenticated Owner/Admin of the tenant

**And** the player belongs to the same tenant

**And** the player has uploaded documents

**When** the Owner/Admin accesses the player's documents section

**Then** the system must display the active documents associated with that player.

---

### AC-002 - Display document metadata

**Given** a document is associated with the player

**When** the document list is displayed

**Then** the system must show:

- Document type.
- Original filename.
- File format.
- File size.
- Upload date.
- User who uploaded the document.
- Optional observations.

---

### AC-003 - Display documents ordered by upload date

**Given** the player has multiple documents

**When** the document list is displayed

**Then** the documents must be ordered by upload date in descending order.

---

### AC-004 - Empty document list

**Given** the player has no documents

**When** the Owner/Admin accesses the documents section

**Then** the system must return an empty collection

**And** indicate that no documents have been uploaded.

---

### AC-005 - Tenant isolation

**Given** an authenticated user attempts to access a player from another tenant

**When** the documents are requested

**Then** the system must deny access

**And** must not reveal whether the player or documents exist.

---

### AC-006 - Player not found

**Given** the requested player does not exist

**When** the Owner/Admin requests the player's documents

**Then** the system must return a player-not-found response.

---

### AC-007 - Display available actions

**Given** a document is displayed

**When** the Owner/Admin views the document

**Then** the interface must expose the available actions supported by the implemented stories.

---

### AC-008 - Return paginated results

**Given** the player has more documents than the configured page size

**When** the Owner/Admin requests a page

**Then** the system must return only the documents for the requested page

**And** include the pagination metadata.

---

### AC-009 - Default pagination

**Given** no pagination parameters are provided

**When** the Owner/Admin requests the document list

**Then** the system must use:

- page = 1
- per_page = 20

---

### AC-010 - Pagination metadata

**Given** the document list is returned

**When** the response is generated

**Then** the system must include:

- page
- per_page
- total
- total_pages
- has_next
- has_prev

---

### AC-011 - Request page outside available range

**Given** the requested page is greater than the total number of available pages

**When** the Owner/Admin requests that page

**Then** the system must return an empty collection

**And** preserve the pagination metadata.

---

## Business Rules

- A document can only be accessed through its associated player.
- A player may have zero or multiple documents.
- Only documents belonging to the authenticated tenant may be returned.
- Deleted documents must not appear in the active document list.
- The document list must return document metadata only.
- The default page size must be **20** records.
- The default page number must be **1**.
- The maximum page size must be configurable.
- Pagination must be applied after validating tenant ownership.
- The total number of documents must exclude deleted documents.

---

## Suggested API Contract

```http
GET /api/v1/players/{playerId}/documents?page=1&per_page=20
```

### Query Parameters

| Parameter | Type | Required | Default | Description |
|-----------|------|----------|---------|-------------|
| page | integer | No | 1 | Requested page number. |
| per_page | integer | No | 20 | Number of records per page. |

---

### Successful Response

```json
{
  "data": [
    {
      "id": "document-id",
      "playerId": "player-id",
      "documentType": "IDENTITY_CARD",
      "originalFilename": "mateo-identity-card.pdf",
      "mimeType": "application/pdf",
      "size": 245760,
      "uploadedAt": "2026-07-29T15:30:00-05:00",
      "uploadedBy": "user-id",
      "observations": null
    }
  ],
  "meta": {
    "page": 1,
    "per_page": 20,
    "total": 134,
    "total_pages": 7,
    "has_next": true,
    "has_prev": false
  }
}
```

---

## Error Scenarios

- Player does not exist.
- Player belongs to another tenant.
- Authenticated user does not have permission.
- Invalid pagination parameters.
- Unexpected storage error.
- Unexpected database error.

---

## Audit Requirements

Viewing a document list does not require a persistent audit record in the MVP.

Application logs may register:

- Authenticated user.
- Tenant identifier.
- Player identifier.
- Requested page.
- Requested page size.
- Request date and time.

---

## Definition of Done

- Documents can be listed for a player.
- Results are paginated.
- Pagination metadata is returned.
- Default pagination values are applied.
- Tenant isolation is enforced.
- Empty states are supported.
- Document metadata is returned correctly.
- Automated tests cover:
  - First page.
  - Intermediate page.
  - Last page.
  - Empty result.
  - Unauthorized access.
  - Player not found.
- API documentation is updated.