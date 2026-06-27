/**
 * ============================================================================
 * Architectural Note ADR (Architecture Decision Records)
 * ============================================================================
 *
 * Actualmente los DTOs de Application utilizan Symfony Validator
 * (#[Assert\...]) como una decisión pragmática para acelerar el desarrollo
 * del MVP y reducir la cantidad de clases de transporte entre Presentation
 * y Application.
 *
 * Esto introduce un acoplamiento ligero entre la capa Application y el
 * componente Symfony Validator, aceptado temporalmente por motivos de
 * productividad.
 *
 * Refactor futuro (Post-MVP):
 *
 * - Crear objetos Request específicos en Presentation/Http/Request.
 * - Mover todas las reglas de validación Symfony a dichos Request.
 * - Convertir los Request en DTOs puros de Application.
 * - Eliminar cualquier dependencia de Symfony dentro de Application.
 *
 * Flujo objetivo:
 *
 * HTTP Request
 *      │
 *      ▼
 * Presentation Request (Symfony Validator)
 *      │
 *      ▼
 * Application DTO (Framework agnostic)
 *      │
 *      ▼
 * Command
 *      │
 *      ▼
 * Handler
 *      │
 *      ▼
 * Domain
 *
 * Con este cambio la arquitectura quedará completamente alineada con
 * Hexagonal Architecture y Clean Architecture, manteniendo el núcleo de la
 * aplicación independiente del framework.
 *
 * Estado:
 * [ ] Pendiente de refactor después del MVP.
 * ============================================================================
 */