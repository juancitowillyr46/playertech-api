# Quickstart: Catálogo compartido de parentescos

## Prerrequisitos

- Contenedores Docker del proyecto disponibles.
- Un usuario tenant autenticado con `academy_id` válido.
- Un token JWT obtenido mediante el flujo de login existente.
- La colección Postman actualizada.

## Validación estática

Desde el contenedor de la aplicación:

```bash
docker exec docker-app-1 bash -lc 'cd /var/www/html && php -l src/Shared/Domain/Relationship/Relationship.php'
```

Validar que la ruta oficial esté registrada:

```bash
docker exec docker-app-1 bash -lc 'cd /var/www/html && php bin/console debug:router | grep relationships'
```

## Pruebas automatizadas

Ejecutar las pruebas unitarias y funcionales relacionadas:

```bash
docker exec docker-app-1 bash -lc 'cd /var/www/html && vendor/bin/phpunit --filter Relationship --testdox'
```

Resultados esperados:

- El catálogo contiene las ocho opciones oficiales.
- Cada opción contiene `value` y `label`.
- El orden es estable.
- Un usuario tenant autenticado recibe `200 OK`.
- Un usuario no autenticado recibe el error estándar.
- Un usuario sin contexto de academia no puede consultar la ruta tenant.

## Validación de contrato

En Postman ejecutar la solicitud oficial:

```http
GET {{baseUrl}}/api/v1/academy/relationships/options
```

Verificar:

- Estado `200`.
- `data` es un arreglo de ocho elementos.
- Cada elemento tiene `value` y `label`.
- `meta` es un objeto vacío.
- Los valores coinciden con el contrato de [relationship-options.md](./contracts/relationship-options.md).

## Validación de consumidores

- El frontend utiliza la ruta neutral.
- Player y LegalGuardian no mantienen listas duplicadas de parentescos.
- `docs/contracts/api-reference.md` y `specs/14-current-state.md` reflejan la ruta oficial.
