# Guía de ejecución de pruebas y validación de despliegue

Esta guía define qué pruebas ejecutar durante el desarrollo, antes de cerrar una feature y en un pipeline CI/CD.

## Selección por impacto

| Capa | Base de datos | Propósito | Momento recomendado |
|---|---:|---|---|
| Unit | No | Reglas de dominio y aplicación aisladas | Después de cada cambio relevante |
| Integration | Sí, `playertech_test` | Repositorios, mappings y constraints | Features con persistencia |
| Functional | Sí, `playertech_test` | Flujos HTTP completos y autorización | Features con endpoints |
| Contract | Opcional | Contratos API y envelope | Cambios de API |
| Migration rehearsal | Base temporal | Crear y actualizar schemas | Antes de integrar o desplegar |

## Pruebas unitarias

Las pruebas unitarias viven en `app/tests/Unit/`, no inician `KernelTestCase`, no acceden a Doctrine ni a MySQL y usan mocks, stubs o fakes para dependencias externas.

```bash
docker exec docker-app-1 bash -lc 'cd /var/www/html && APP_ENV=test composer test:unit'
```

Esta es la prueba frecuente para cambios de Value Objects, entidades, policies, commands y handlers sin infraestructura.

## Pruebas de integración

Se usan para repositorios, XML Mapping, tipos Doctrine, constraints y tenant filters.

Preparar la base de test de forma explícita:

```bash
docker exec docker-app-1 bash -lc 'cd /var/www/html && APP_ENV=test DATABASE_URL=mysql://root:root@mysql:3306/playertech_test?serverVersion=8.0\&charset=utf8mb4 php bin/console doctrine:migrations:migrate --no-interaction'
```

Ejecutar:

```bash
docker exec docker-app-1 bash -lc 'cd /var/www/html && APP_ENV=test composer test:integration'
```

Las pruebas no deben depender de tablas o datos creados por otra prueba.

## Pruebas funcionales

Se usan para login, autorización, tenant isolation, endpoints HTTP, Problem Details, respuestas JSON y flujos completos persistidos.

```bash
docker exec docker-app-1 bash -lc 'cd /var/www/html && APP_ENV=test composer test:functional'
```

Cada escenario debe crear los datos mínimos que necesita y nunca debe utilizar `playertech`.

## Pruebas de contrato

Se ejecutan cuando cambia un endpoint, envelope, error, paginación o respuesta documentada. Deben mantenerse alineadas con `docs/contracts/api-reference.md` y Postman.

```bash
docker exec docker-app-1 bash -lc 'cd /var/www/html && APP_ENV=test composer test:contract'
```

## Uso de `SchemaResetter`

`SchemaResetter` no debe utilizarse como mecanismo normal de aislamiento de cada test. Su reset parcial puede eliminar tablas necesarias para otras pruebas y producir errores dependientes del orden.

- No usarlo en pruebas unitarias.
- No ejecutarlo automáticamente antes de cada feature.
- Reservarlo para pruebas de infraestructura que validen explícitamente creación de schema.
- Para integración y funcionales normales, preparar el schema una vez y limpiar datos de forma acotada.
- Si se requiere aislamiento total, usar una base temporal por suite.

## Validación completa

Al cerrar una feature o modificar seguridad, persistencia o contratos:

```bash
docker exec docker-app-1 bash -lc 'cd /var/www/html && APP_ENV=test composer test:all'
docker exec docker-app-1 bash -lc 'cd /var/www/html && APP_ENV=test php bin/console doctrine:mapping:info'
docker exec docker-app-1 bash -lc 'cd /var/www/html && APP_ENV=test php bin/console doctrine:schema:validate --skip-sync'
```

## Ensayo de migración desde cero

1. Crear una base temporal que termine en `_test`.
2. Ejecutar todas las migraciones desde la primera versión.
3. Validar mappings y schema.
4. Ejecutar integration, functional y contract tests.

Las migraciones deben crear estructura y no insertar datos de negocio salvo seeds explícitos.

## Ensayo de actualización con datos

1. Crear una base temporal con el schema anterior.
2. Insertar datos representativos.
3. Ejecutar las nuevas migraciones.
4. Confirmar que los datos permanecen.
5. Confirmar nuevas columnas, índices y constraints.
6. Ejecutar la suite completa.

Este ensayo simula una actualización productiva sin pérdida de datos.

## Pipeline CI/CD

El pipeline debe ejecutar en este orden:

1. Instalar dependencias.
2. Crear una base temporal de test.
3. Ejecutar migraciones desde cero.
4. Validar mappings y schema.
5. Ejecutar unit tests.
6. Ejecutar integration tests.
7. Ejecutar functional tests.
8. Ejecutar contract tests y Postman cuando el entorno lo soporte.
9. Ejecutar el ensayo de actualización con datos si hubo cambios de migración.

Cualquier fallo de migración, schema, integración o funcional debe bloquear la promoción.

## Regla diaria

- Cambio de dominio o handler sin infraestructura: `test:unit`.
- Cambio de repository, mapping o base de datos: `test:unit` y `test:integration`.
- Cambio de endpoint, seguridad o contrato: `test:unit`, `test:functional` y contract/Postman.
- Cambio de migración: todas las suites y ensayo de actualización con datos.
- Cierre de feature: `test:all`, mapping, schema y `git diff --check`.
