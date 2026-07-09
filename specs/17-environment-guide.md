# 17-environment-guide.md

# Environment Guide

Esta guía define cómo se separan y ejecutan los entornos del proyecto PlayerTech.

---

# Environment Model

## Local

Archivo base:

* `app/.env`

Uso:

* desarrollo diario
* contenedor Docker local
* base de datos de desarrollo
* Mailpit para correos

## Test

Archivos base:

* `app/.env.test`
* `app/tests/bootstrap.php`

Uso:

* PHPUnit
* integración contra base MySQL de test
* ejecución en CI/CD

Regla operativa:

* Las suites funcionales e integraciones deben apuntar a `test` para simular el flujo real de CI/CD.
* `local` queda para desarrollo interactivo y verificación manual en Docker.
* Ninguna validación formal de regresión debe depender exclusivamente de `local`.

## Prod

Archivo objetivo futuro:

* `app/.env.prod`

Uso:

* despliegue productivo
* variables reales de infraestructura

---

# Rules

* No hardcodear credenciales o URLs de base de datos dentro de los tests.
* `local` y `test` deben tener configuraciones separadas.
* La base de datos de test debe ser reproducible y aislada.
* Los correos de pruebas no deben depender de un proveedor real.

---

# Test Flow

## Local validation

```bash
docker compose -f docker/docker-compose.yml up -d --build
```

```bash
docker exec docker-app-1 bash -lc 'cd /var/www/html && php bin/console doctrine:migrations:migrate --no-interaction'
```

```bash
docker exec docker-app-1 bash -lc 'cd /var/www/html && vendor/bin/phpunit --testdox'
```

## Targeted test

```bash
docker exec docker-app-1 bash -lc 'cd /var/www/html && vendor/bin/phpunit --filter RegisterTenantHandlerTest --testdox'
```

## Mapping check

```bash
docker exec docker-app-1 bash -lc 'cd /var/www/html && php bin/console doctrine:mapping:info'
```

---

# Current Files

* `app/.env` para desarrollo local.
* `app/.env.test` para PHPUnit y CI.
* `app/tests/bootstrap.php` para preparar la base de test.

---

# Notes

* `app/.env.test` crea una base MySQL de test y la deja lista antes de correr PHPUnit.
* La estrategia de pruebas se documenta en `specs/11-testing-strategy.md`.
