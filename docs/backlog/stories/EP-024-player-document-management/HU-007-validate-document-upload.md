# HU-007 - Validate Document Upload

**File:** `HU-007-validate-document-upload.md`

## User Story

As an **Owner/Admin of the tenant**,
I want the system to validate a document before it is uploaded,
so that only secure, supported, and complete files are stored in the player's digital record.

---

## Business Context

The academy receives documents from different sources, including mobile devices, scanners, WhatsApp, and email.

Since these files originate from external sources, the system must validate them before permanent storage to protect the application against invalid, corrupted, or malicious files while ensuring consistent document quality.

Validation must occur before the file is permanently stored or document metadata is created.

---

## Scope

This story includes:

- Validating that a file is attached.
- Validating the document type.
- Validating the file size.
- Validating the file extension.
- Validating the detected MIME type.
- Validating that the extension matches the MIME type.
- Sanitizing the original filename.
- Rejecting unsupported document types.
- Rejecting unsupported file formats.
- Rejecting oversized files.
- Rejecting invalid upload requests.

This story does not include:

- OCR validation.
- Authenticity verification.
- Virus scanning.
- Digital signature validation.
- Image quality analysis.
- Automatic document classification.

These capabilities may be implemented in future releases.

---

## Preconditions

- The user is authenticated.
- The authenticated user is an Owner/Admin of the tenant.
- The player exists.
- The player belongs to the authenticated user's tenant.
- The upload request contains multipart/form-data.

---

## Validation Rules

| Validation | Rule |
|------------|------|
| File required | Yes |
| Document type required | Yes |
| Maximum file size | **3 MB (3,145,728 bytes)** |
| Allowed extensions | `.pdf`, `.jpg`, `.jpeg`, `.png` |
| Allowed MIME types | `application/pdf`, `image/jpeg`, `image/png` |
| Empty file | Not allowed |
| Filename | Must be sanitized before persistence |
| MIME/Extension | Must be consistent |

---

## Main Flow

1. The Owner/Admin submits the upload request.
2. The system validates the request structure.
3. The system validates the document type.
4. The system validates that a file is attached.
5. The system validates the file size.
6. The system validates the file extension.
7. The system detects the real MIME type.
8. The system validates that the extension matches the detected MIME type.
9. The system sanitizes the original filename.
10. The system continues with the upload process.

If any validation fails, the upload process must stop immediately.

---

## Acceptance Criteria

### AC-001 - Require file

**Given** the upload request does not contain a file

**When** the request is processed

**Then** the system must reject the request

**And** return a `FILE_REQUIRED` validation error.

---

### AC-002 - Require document type

**Given** the upload request does not contain a document type

**When** the request is processed

**Then** the system must reject the request

**And** return a `DOCUMENT_TYPE_REQUIRED` validation error.

---

### AC-003 - Reject oversized file

**Given** the uploaded file exceeds **3 MB**

**When** validation is performed

**Then** the system must reject the request

**And** return a `FILE_TOO_LARGE` validation error

**And** must not store the file.

---

### AC-004 - Accept supported extensions

**Given** the uploaded file has one of the following extensions:

- `.pdf`
- `.jpg`
- `.jpeg`
- `.png`

**When** validation is performed

**Then** the system must continue with the upload process.

---

### AC-005 - Reject unsupported extension

**Given** the uploaded file has an unsupported extension

**When** validation is performed

**Then** the system must reject the request

**And** return an `UNSUPPORTED_FILE_EXTENSION` validation error.

---

### AC-006 - Accept supported MIME types

**Given** the detected MIME type is one of:

- `application/pdf`
- `image/jpeg`
- `image/png`

**When** validation is performed

**Then** the upload may continue.

---

### AC-007 - Reject unsupported MIME type

**Given** the detected MIME type is not supported

**When** validation is performed

**Then** the system must reject the request

**And** return an `UNSUPPORTED_MIME_TYPE` validation error.

---

### AC-008 - Reject MIME/extension mismatch

**Given** the uploaded file extension does not correspond to the detected MIME type

**When** validation is performed

**Then** the system must reject the request

**And** return a `FILE_CONTENT_MISMATCH` validation error.

---

### AC-009 - Reject empty file

**Given** the uploaded file size is zero bytes

**When** validation is performed

**Then** the system must reject the request

**And** return an `EMPTY_FILE` validation error.

---

### AC-010 - Sanitize original filename

**Given** a valid uploaded file

**When** validation succeeds

**Then** the system must sanitize the original filename before persisting it

**And** preserve only safe characters.

---

### AC-011 - Reject invalid document type

**Given** the provided `documentType` is not supported

**When** validation is performed

**Then** the system must reject the request

**And** return an `UNSUPPORTED_DOCUMENT_TYPE` validation error.

---

### AC-012 - Stop upload after validation failure

**Given** any validation rule fails

**When** the upload request is processed

**Then** the upload process must stop immediately

**And** no document metadata must be created

**And** no file must remain permanently stored.

---

## Business Rules

- Validation must occur before permanent storage.
- Validation must occur before document metadata is persisted.
- Maximum file size is **3 MB (3,145,728 bytes)**.
- Allowed extensions are:
  - `.pdf`
  - `.jpg`
  - `.jpeg`
  - `.png`
- Allowed MIME types are:
  - `application/pdf`
  - `image/jpeg`
  - `image/png`
- Empty files are not allowed.
- The detected MIME type must be trusted over the client-provided MIME type.
- The original filename must be sanitized.
- Unsupported document types must be rejected.
- Validation failures must not create document metadata.
- Validation failures must not permanently store files.

---

## Suggested API Contract

```http
POST /api/v1/players/{playerId}/documents
Content-Type: multipart/form-data
```

Validation is performed automatically before document creation.

---

## Suggested Validation Errors

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

---

### File too large

```json
{
  "errors": [
    {
      "code": "FILE_TOO_LARGE",
      "field": "file",
      "message": "The document file must not exceed 3 MB."
    }
  ]
}
```

---

### Unsupported extension

```json
{
  "errors": [
    {
      "code": "UNSUPPORTED_FILE_EXTENSION",
      "field": "file",
      "message": "The selected file extension is not supported."
    }
  ]
}
```

---

### Unsupported MIME type

```json
{
  "errors": [
    {
      "code": "UNSUPPORTED_MIME_TYPE",
      "field": "file",
      "message": "The uploaded file type is not supported."
    }
  ]
}
```

---

### MIME mismatch

```json
{
  "errors": [
    {
      "code": "FILE_CONTENT_MISMATCH",
      "field": "file",
      "message": "The uploaded file content does not match its extension."
    }
  ]
}
```

---

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

---

## Error Scenarios

- Missing file.
- Missing document type.
- Unsupported document type.
- Unsupported extension.
- Unsupported MIME type.
- File exceeds 3 MB.
- Empty file.
- MIME/extension mismatch.
- Invalid multipart request.

---

## Audit Requirements

Validation failures do not require persistent audit records.

Application logs may register:

- Authenticated user.
- Tenant identifier.
- Player identifier.
- Validation error code.
- Request timestamp.

The system must never log file contents.

---

## Security Considerations

- Never trust the MIME type sent by the client.
- Detect the MIME type from the uploaded file.
- Sanitize the original filename.
- Never use the original filename as the storage path.
- Prevent path traversal attacks.
- Reject executable files regardless of their extension.
- Reject files whose extension does not match their detected content.
- Stop processing immediately after the first validation failure.

---

## Definition of Done

- File presence is validated.
- Document type is validated.
- Maximum file size of **3 MB** is enforced.
- Supported extensions are validated.
- Supported MIME types are validated.
- MIME/extension consistency is validated.
- Original filenames are sanitized.
- Invalid requests never create metadata.
- Invalid requests never leave permanently stored files.
- Automated tests cover:
  - Missing file.
  - Missing document type.
  - Oversized file.
  - Empty file.
  - Invalid extension.
  - Invalid MIME type.
  - MIME mismatch.
  - Invalid document type.
  - Successful validation.
- API documentation is updated.