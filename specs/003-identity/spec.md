# Identity Feature

**Feature Branch**: `003-identity`

**Created**: 2026-07-27

**Status**: Draft

**Entrada**: Base feature para platform y tenant identity, authentication, authorization, admin users, tenant owner bootstrap, auth/me y password reset.

## Escenarios de usuario y pruebas *(mandatory)*

### Historia de Usuario 1 - Authentication and profile access (Priority: P1)

El sistema permite a los usuarios autenticados iniciar sesión y recuperar su identidad actual.

**Por qué esta prioridad**: Identity es el punto de entrada para todos los demás flujos del backend.

**Prueba independiente**: Un usuario puede autenticarse y llamar exitosamente a `/api/v1/auth/me`.

**Escenarios de aceptación**:

1. **Given** credenciales válidas, **When** el usuario inicia sesión, **Then** la API devuelve una sesión JWT válida.
2. **Given** un token válido, **When** el usuario solicita su perfil, **Then** la API devuelve la identidad autenticada.

### Historia de Usuario 2 - Tenant and platform user administration (Priority: P2)

El sistema permite a los administradores de plataforma y academia gestionar el lifecycle de los usuarios sin mezclar `ROLE_ROOT` con el comportamiento tenant-scoped.

**Por qué esta prioridad**: El control operativo de usuarios es necesario para una adopción segura de la plataforma.

**Prueba independiente**: Un admin puede crear, actualizar, habilitar y deshabilitar usuarios en el contexto correcto.

**Escenarios de aceptación**:

1. **Given** un admin de plataforma, **When** crea un root user, **Then** el usuario se crea sin tenant context.
2. **Given** un admin de academia, **When** gestiona usuarios, **Then** los usuarios permanecen aislados a la academia.

### Historia de Usuario 3 - Tenant onboarding support (Priority: P3)

El sistema da soporte al bootstrap del tenant owner y a los flujos de password reset.

**Por qué esta prioridad**: El onboarding y la recuperación son necesarios para un lifecycle de identity completo.

**Prueba independiente**: Un tenant owner puede bootstrapearse y un usuario puede solicitar un password reset.

**Escenarios de aceptación**:

1. **Given** una academia nueva, **When** corre el flujo de owner bootstrap, **Then** se crea la primera cuenta admin.
2. **Given** un usuario autenticado, **When** solicita recuperación de password, **Then** el flujo de recuperación se inicia.

### Casos límite

- ¿Qué ocurre cuando las credenciales son inválidas?
- ¿Cómo maneja el sistema los usuarios deshabilitados?
- ¿Qué ocurre cuando un usuario tenant-scoped no tiene academy context?

## Requisitos *(mandatory)*

### Requisitos funcionales

- **FR-001**: El sistema MUST permitir autenticarse con el contrato de login definido.
- **FR-002**: El sistema MUST exponer la identidad autenticada mediante `/api/v1/auth/me`.
- **FR-003**: El sistema MUST separar los usuarios de plataforma de los usuarios tenant.
- **FR-004**: El sistema MUST permitir la administración de usuarios de plataforma.
- **FR-005**: El sistema MUST permitir la administración de usuarios tenant dentro del scope de la academia.
- **FR-006**: El sistema MUST dar soporte a los flujos de bootstrap del tenant owner.
- **FR-007**: El sistema MUST dar soporte a los flujos de password recovery para usuarios autenticados.

### Entidades clave *(include if feature involves data)*

- **User**: identidad autenticada del sistema con role y tenant context.
- **Role**: scope de autorización para comportamiento de plataforma o tenant.
- **Permission**: capability asociada a roles.

## Criterios de éxito *(mandatory)*

### Resultados medibles

- **SC-001**: Los usuarios pueden completar el flujo de login y `auth/me` sin ambigüedad de contexto.
- **SC-002**: Las operaciones de usuarios de plataforma y tenant son trazables al scope correcto.
- **SC-003**: Los flujos de onboarding y recovery son testeables de forma independiente.

## Suposiciones

- La infraestructura existente de JWT y security se reutiliza.
- El backend sigue siendo multi-tenant y la separación root/tenant es obligatoria.
