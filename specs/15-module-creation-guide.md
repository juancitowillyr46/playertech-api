# Module Creation Guide

Este documento define la guia operativa para crear nuevos modulos en PlayerTech siguiendo el ejemplo tecnico de `Academy`.

---

# Objetivo

Estandarizar la creacion de modulos para que cualquier nuevo contexto funcional pueda implementarse sin acceso a modelos previos ni decisiones improvisadas.

---

# Core Rules

* Usar monolito modular.
* Mantener separacion estricta entre `Domain`, `Application`, `Infrastructure` y `Presentation`.
* Usar CQRS en la capa de aplicacion.
* Usar XML Mapping exclusivamente para Doctrine.
* No usar attributes ni annotations Doctrine en dominio.
* Usar Value Objects tipados.
* Mantener los controllers delgados.
* Registrar trazabilidad en `specs/14-current-state.md`.

---

# Recommended Module Structure

```text
app/src/Modules/ModuleName/
├── Domain/
│   └── AggregateName/
├── Application/
│   ├── Command/
│   ├── Query/
│   ├── Handler/
│   ├── DTO/
│   └── Response/
├── Infrastructure/
│   ├── Persistence/
│   │   └── Doctrine/
│   │       ├── Mapping/
│   │       └── Type/
│   ├── Console/
│   ├── Security/
│   └── Messaging/
└── Presentation/
    └── Http/
```

---

# Minimal Creation Steps

## 1. Crear estructura de carpetas

```bash
mkdir -p app/src/Modules/ModuleName/{Domain,Application/{Command,Query,Handler,DTO,Response},Infrastructure/{Persistence/Doctrine/{Mapping,Type},Console,Security,Messaging},Presentation/Http}
```

## 2. Definir el Aggregate Root

Crear la entidad de dominio con:

* ID como Value Object UUID.
* comportamiento de negocio.
* invariantes del agregado.
* sin dependencias a Symfony o Doctrine.

## 3. Definir Value Objects

Separar:

* IDs de negocio: `ModuleId`, `UserId`, etc.
* VOs de negocio: `Name`, `Email`, `Status`, `Address`, etc.

Reglas:

* IDs UUID -> Doctrine custom types.
* VOs de negocio -> embeddables XML.

## 4. Crear XML Mapping

Ubicar los XML en:

```text
app/src/Modules/ModuleName/Infrastructure/Persistence/Doctrine/Mapping/
```

Pautas:

* Un archivo XML por entidad o embeddable.
* `id` con `type="module_id"` cuando aplique.
* `embedded` para VOs de negocio.
* Evitar `attribute-overrides` salvo necesidad real y documentada.
* En XML de Doctrine, para desactivar prefijos de `embedded`, usar `use-column-prefix="false"` y no `column-prefix="false"`.
* Validar siempre el mapping dentro del contenedor con `php bin/console doctrine:mapping:info` antes de asumir que el XML quedó correcto.
* Si aparece una columna fantasma como `falsename` o `falsecontact_email`, revisar primero el `embedded` y la cache de Symfony/Doctrine.
* Si el módulo requiere soft delete, declarar `deleted_at` y `deleted_by`, exponer métodos de dominio para borrar y restaurar lógicamente, y registrar un filtro Doctrine que excluya registros borrados.
* En módulos con alcance plataforma/tenant, documentar explícitamente qué operaciones pertenecen a `ROLE_ROOT` y cuáles a contexto tenant antes de implementar código.

## 5. Registrar Doctrine

Agregar el mapping del modulo en `config/packages/doctrine.yaml`.

Si existen VOs compartidos, registrar su mapping tambien.

## 6. Crear Custom Types

Usar una base reusable:

* `AbstractUuidType`
* `ModuleIdType`

Luego crear tipos especificos:

* `AcademyIdType`
* `UserIdType`
* `PlayerIdType`
* `TeamIdType`

## 7. Definir Application Layer

Para cada caso de uso:

* `CreateXCommand`
* `UpdateXCommand`
* `GetXQuery`
* `ListXQuery`
* `CreateXHandler`
* `UpdateXHandler`
* `GetXHandler`
* `ListXHandler`

Los handlers:

* orquestan el caso de uso
* llaman al dominio
* usan repositorios por contrato
* retornan Response Models o Views

## 8. Definir Presentation Layer

Los controllers deben:

* recibir HTTP
* construir el command/query
* delegar al handler
* devolver JSON

No deben contener:

* logica de negocio
* consultas complejas
* reglas de permisos de dominio

## 9. Registrar Servicios

Si el modulo usa interfaces de dominio, registrar alias en `services.yaml`.

Ejemplo:

```yaml
App\Modules\Academy\Domain\Academy\AcademyRepository: '@App\Modules\Academy\Infrastructure\Persistence\AcademyRepository'
```

## 10. Documentar Trazabilidad

Actualizar:

* `specs/14-current-state.md`
* `specs/12-execution-order.md`
* el epic del modulo
* las historias afectadas

---

# Suggested Workflow

1. Crear dominio y VOs.
2. Crear XML Mapping.
3. Crear repository contract e implementation.
4. Crear commands, queries y handlers.
5. Crear controllers finos.
6. Validar con `php bin/console doctrine:mapping:info`.
7. Validar con `php -l`.
8. Probar via `.http`.

---

# Naming Conventions

## Domain

```text
Academy
AcademyId
AcademyStatus
AcademyRepository
```

## Application

```text
CreateAcademyCommand
UpdateAcademyCommand
ListAcademiesQuery
CreateAcademyHandler
```

## Infrastructure

```text
DoctrineAcademyRepository
AcademyIdType
AbstractUuidType
```

## Presentation

```text
AcademyController
AcademyMeController
```

---

# Example Using Academy

`Academy` debe servir como referencia de:

* UUID tipado.
* XML Mapping puro.
* CQRS.
* Tenant vs Platform context.
* Soft delete preparado.
* Auditoria embebida.
* Validacion formal en DTOs.
* Controllers delgados.
* Trazabilidad por commit.

`Academy` no es solo un modulo funcional: es la referencia oficial para construir los demas contextos del sistema.

---

# Email Testing Standard

Para desarrollo local, la herramienta recomendada para probar correos es:

```text
Mailpit
```

Motivos:

* Permite ver correos enviados en una interfaz web local.
* Facilita validar links de activacion y reset de contrasena.
* Evita depender de proveedores externos durante la iteracion.

---

# Do Not

* No crear handlers dentro de controllers.
* No usar primitivos si el dominio exige VO.
* No mezclar rules de plataforma y tenant.
* No usar attributes Doctrine en dominio.
* No repetir logica de validacion en cada controller.
