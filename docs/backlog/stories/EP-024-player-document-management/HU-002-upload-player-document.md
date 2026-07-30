# HU-002 - Upload Player Document

**File:** `HU-002-upload-player-document.md`

## User Story

As an **Owner/Admin of the tenant**,
I want to upload a document associated with a player,
so that the academy can keep it permanently available in the player's digital record.

## Business Context

The academy frequently receives player documents physically, through WhatsApp, by email, or as scanned copies.

The Owner/Admin needs to store these files directly in the player's record so they can be accessed later without requesting them again from the player's guardian.

The system must ensure that each uploaded file is associated with the correct player, stored securely, and registered with enough metadata to be identified and managed later.

## Scope

This story includes:

* Selecting a player.
* Selecting a supported document type.
* Uploading a supported file.
* Registering document metadata.
* Storing the file in the configured storage system.
* Registering the authenticated user as the uploader.
* Returning the created document information.
* Cleaning up the stored file when metadata persistence fails.
* Validating that the uploaded file does not exceed 3 MB.

This story does not include:

* Previewing the document.
* Downloading the document.
* Replacing an existing document.
* Deleting a document.
* Extracting information using OCR.
* Validating document authenticity.
* Keeping a version history.
* Validating expiration dates.
* Preventing multiple documents of the same type.

These actions are covered by separate user stories or future features.

## Preconditions

* The user is authenticated.
* The authenticated user is an Owner/Admin of the tenant.
* The player exists.
* The player belongs to the authenticated user's tenant.
* The uploaded file satisfies the validation rules.
* The selected document type is supported by the system.
* The uploaded file does not exceed the maximum allowed size of 3 MB.

## Required Information

* Player identifier.
* Document type.
* File.

## Optional Information

* Observations.

Issue date, expiration date, and description are outside the initial MVP unless they are introduced explicitly in the domain model.

## Main Flow

1. The Owner/Admin accesses the player's documents section.
2. The Owner/Admin selects **Upload document**.
3. The system displays the upload form.
4. The Owner/Admin selects the document type.
5. The Owner/Admin selects a file.
6. The Owner/Admin optionally enters observations.
7. The Owner/Admin confirms the upload.
8. The system validates the player, tenant ownership, document type, and file.
9. The system generates a unique internal storage name.
10. The system stores the file.
11. The system creates and persists the document metadata.
12. The system registers the authenticated user and upload date.
13. The system returns the created document.
14. The interface confirms that the document was uploaded successfully.

## Supported Document Types

The initial supported document types are:

| Value      | Document type                   |
| ---------- | ------------------------------- |
| `CE`       | Cédula de extranjería           |
| `CC`       | Cédula de ciudadanía            |
| `TI`       | Tarjeta de identidad            |
| `PPT`      | Permiso por Protección Temporal |
| `PASSPORT` | Pasaporte                       |
| `RC`       | Registro civil                  |

The `documentType` field must use one of the values defined in this catalog.

The system must reject any value that is not included in the supported document type catalog.

## Acceptance Criteria

### AC-001 - Upload a valid PDF

**Given** an authenticated Owner/Admin of the tenant
**And** the player belongs to the same tenant
**And** a valid PDF file is selected
**And** a supported document type is selected
**When** the Owner/Admin submits the upload form
**Then** the system must store the file
**And** create the document metadata
**And** associate the document with the player.

### AC-002 - Upload a valid image

**Given** a supported image file is selected
**And** a supported document type is selected
**When** the Owner/Admin submits the upload form
**Then** the system must store the image
**And** create the document metadata
**And** associate the document with the player.

### AC-003 - Require document type

**Given** the Owner/Admin selected a file
**But** did not select a document type
**When** the upload is submitted
**Then** the system must reject the request
**And** indicate that the document type is required
**And** must not store the file.

### AC-004 - Require file

**Given** the Owner/Admin selected a document type
**But** did not attach a file
**When** the upload is submitted
**Then** the system must reject the request
**And** indicate that the file is required.

### AC-005 - Preserve original filename

**Given** a valid file is uploaded
**When** the document is created
**Then** the system must preserve the sanitized original filename as document metadata.

### AC-006 - Generate internal storage name

**Given** a valid file is uploaded
**When** the file is stored
**Then** the system must generate a unique internal storage name
**And** must not rely exclusively on the original filename
**And** must not allow the client to define the storage path.

### AC-007 - Register uploader

**Given** the upload is successful
**When** the document metadata is created
**Then** the authenticated user's identifier must be registered as the uploader.

### AC-008 - Register upload date

**Given** the upload is successful
**When** the document metadata is created
**Then** the system must register the upload date and time.

### AC-009 - Tenant isolation

**Given** the player belongs to another tenant
**When** the user attempts to upload a document
**Then** the system must deny the operation
**And** must not store the file
**And** must not create document metadata.

### AC-010 - Accept supported document types

**Given** the Owner/Admin submits a document
**When** the selected `documentType` is one of the supported values
**Then** the system must accept the document type.

Supported values:

* `CE`
* `CC`
* `TI`
* `PPT`
* `PASSPORT`
* `RC`

### AC-011 - Reject unsupported document type

**Given** the Owner/Admin submits a document
**And** the provided `documentType` is not one of the supported values
**When** the upload is processed
**Then** the system must reject the request
**And** return an `UNSUPPORTED_DOCUMENT_TYPE` validation error
**And** must not store the file
**And** must not create document metadata.

### AC-012 - Clean up file when metadata persistence fails

**Given** the file was successfully stored
**But** the document metadata could not be persisted
**When** the upload operation fails
**Then** the system must attempt to remove the stored file
**And** must register an application error if the file cannot be removed
**And** must not return a successful response.

### AC-013 - Reject file exceeding maximum size

**Given** the Owner/Admin selects a file larger than 3 MB  
**When** the upload is submitted  
**Then** the system must reject the request  
**And** return a `FILE_TOO_LARGE` validation error  
**And** indicate that the maximum allowed file size is 3 MB  
**And** must not permanently store the file  
**And** must not create document metadata.

### AC-014 - Reject invalid file

**Given** the selected file does not satisfy the upload validation rules
**When** the Owner/Admin submits the upload
**Then** the system must reject the request
**And** must not permanently store the file
**And** must not create document metadata.

### AC-015 - Allow multiple documents of the same type

**Given** the player already has a document with a specific `documentType`
**When** the Owner/Admin uploads another valid document using the same type
**Then** the system must allow the upload
**And** create a new document record with a unique identifier.

## Business Rules

* Every document must belong to exactly one player.
* Every document must belong indirectly to exactly one tenant through the player.
* A player may have zero or multiple documents.
* A player may have multiple documents with the same `documentType`.
* Document type is mandatory.
* File is mandatory.
* `documentType` must match one of the supported values.
* The original filename must be stored as sanitized metadata.
* The physical storage path must not be exposed publicly.
* The file must be stored using a unique internal identifier.
* The storage path must be generated by the system.
* The uploader must be obtained from the authenticated context.
* The tenant identifier must not be trusted directly from client input.
* Tenant ownership must be validated before storing the file.
* File validation must occur before permanent storage.
* File storage and metadata persistence must behave as a consistent operation.
* Failed uploads must not leave orphaned files whenever immediate cleanup is possible.
* Failure to clean up an orphaned file must be registered in application logs.
* A successful response must only be returned after the document metadata has been persisted successfully.

## Suggested API Contract

```http
POST /api/v1/players/{playerId}/documents
Content-Type: multipart/form-data
```

### Path Parameters

| Parameter  | Type | Required | Description                                      |
| ---------- | ---- | -------: | ------------------------------------------------ |
| `playerId` | UUID |      Yes | Identifier of the player receiving the document. |

### Request Fields

| Field          | Type   | Required | Description                        |
| -------------- | ------ | -------: | ---------------------------------- |
| `documentType` | string |      Yes | Supported document type value.     |
| `file`         | binary |      Yes | PDF or supported image file.       |
| `observations` | string |       No | Optional notes about the document. |

### Example Request Fields

```text
documentType: TI
file: binary
observations: Copy provided by the player's guardian.
```

### Successful Response

```json
{
  "data": {
    "id": "document-id",
    "playerId": "player-id",
    "documentType": "TI",
    "originalFilename": "mateo-identity-card.pdf",
    "mimeType": "application/pdf",
    "size": 245760,
    "uploadedAt": "2026-07-29T15:30:00-05:00",
    "uploadedBy": "user-id",
    "observations": "Copy provided by the player's guardian."
  },
  "meta": {}
}
```

## Suggested Validation Errors

### Missing document type

```json
{
  "errors": [
    {
      "code": "DOCUMENT_TYPE_REQUIRED",
      "field": "documentType",
      "message": "The document type is required."
    }
  ]
}
```

### Unsupported document type

```json
{
  "errors": [
    {
      "code": "UNSUPPORTED_DOCUMENT_TYPE",
      "field": "documentType",
      "message": "The selected document type is not supported."
    }
  ]
}
```

### Missing file

```json
{
  "errors": [
    {
      "code": "FILE_REQUIRED",
      "field": "file",
      "message": "The document file is required."
    }
  ]
}
```

### Metadata persistence failure

```json
{
  "errors": [
    {
      "code": "DOCUMENT_UPLOAD_FAILED",
      "field": null,
      "message": "The document could not be uploaded."
    }
  ]
}
```

Internal storage or database details must not be exposed in the public error response.

## Error Scenarios

* Player does not exist.
* Player belongs to another tenant.
* Authenticated user does not have permission.
* Missing document type.
* Unsupported document type.
* Missing file.
* Unsupported file format.
* File exceeds the maximum allowed size.
* File is empty.
* File cannot be stored.
* Document metadata cannot be persisted.
* Stored file cannot be removed after a persistence failure.
* Unexpected storage or database error.

## Audit Requirements

The system must register:

* Document identifier.
* Player identifier.
* Tenant identifier.
* Document type.
* Original filename.
* MIME type.
* File size.
* Uploaded by.
* Uploaded at.

Application logs must also register failed upload cleanup operations without exposing sensitive document content.

## Security Considerations

* Files must be stored outside the public application directory.
* The client-provided MIME type must not be trusted as the only validation source.
* The storage name must be generated by the system.
* The original filename must not be used directly as the physical storage path.
* The tenant must be derived from the authenticated context.
* The system must validate that the player belongs to the tenant before storing the file.
* Internal storage paths and credentials must never be returned in the API response.

## Definition of Done

* Valid PDF and image documents can be uploaded.
* Supported document types are enforced.
* Unsupported document types are rejected.
* Files are stored outside the public directory.
* Unique internal storage names are generated.
* Original filenames are preserved as sanitized metadata.
* Document metadata is persisted.
* The authenticated user and upload date are registered.
* Tenant isolation is enforced.
* Multiple documents of the same type are allowed.
* Invalid requests do not leave permanently stored files.
* Metadata persistence failures trigger file cleanup.
* Cleanup failures are logged.
* Automated tests cover:

  * Valid PDF upload.
  * Valid image upload.
  * Missing document type.
  * Unsupported document type.
  * Missing file.
  * Invalid file.
  * Player from another tenant.
  * Metadata persistence failure.
  * Orphaned file cleanup.
  * Multiple documents of the same type.
* API documentation is updated.
