# Ubuntu Setup Guide

Guía rápida para levantar PlayerTech en Ubuntu desde un `git clone` hasta la instalación de dependencias del proyecto.

---

## 1. Requisitos Previos

Antes de empezar, verifica que tengas instalado:

- `git`
- `docker`
- `docker compose`
- `php` compatible con el proyecto, si vas a ejecutar tareas fuera del contenedor
- `curl`
- permisos para ejecutar Docker o pertenencia al grupo `docker`

---

## 2. Clonar el Repositorio

```bash
git clone <URL_DEL_REPO>
cd playertech
```

Si el proyecto está en un disco externo, entra a la ruta donde lo montaste.

---

## 3. Verificar Docker Engine

Arranca Docker en Ubuntu:

```bash
sudo systemctl start docker
sudo systemctl enable docker
```

Verifica que el servicio esté activo:

```bash
sudo systemctl status docker
```

Si quieres usar Docker sin `sudo`:

```bash
sudo usermod -aG docker $USER
newgrp docker
```

Prueba que Docker responde:

```bash
docker ps
```

---

## 4. Levantar el Stack Local

Desde la raíz del proyecto:

```bash
docker compose -f docker/docker-compose.yml up -d --build
```

Esto levanta:

- `app`
- `mysql`
- `mailpit`

Si quieres confirmar que el stack quedó arriba:

```bash
docker compose -f docker/docker-compose.yml ps
```

---

## 5. Verificar el Contenedor de la App

```bash
docker ps
```

Debes ver un contenedor similar a:

- `docker-app-1`

Si el contenedor no aparece, revisa los logs:

```bash
docker compose -f docker/docker-compose.yml logs -f app
```

---

## 6. Instalar Dependencias del Proyecto

Instala dependencias PHP dentro del contenedor:

```bash
docker exec docker-app-1 bash -lc 'cd /var/www/html && composer install'
```

Si necesitas volver a instalar dependencias luego de un cambio en `composer.lock`:

```bash
docker exec docker-app-1 bash -lc 'cd /var/www/html && composer install --no-interaction'
```

Verifica que el runtime de Symfony quedó completo:

```bash
docker exec docker-app-1 bash -lc 'cd /var/www/html && test -f vendor/autoload_runtime.php && echo OK'
```

---

## 7. Aplicar Migraciones

```bash
docker exec docker-app-1 bash -lc 'cd /var/www/html && php bin/console doctrine:migrations:migrate --no-interaction'
```

Si quieres revisar el estado de migraciones antes o después:

```bash
docker exec docker-app-1 bash -lc 'cd /var/www/html && php bin/console doctrine:migrations:status'
```

---

## 8. Generar Llaves JWT

El login JWT requiere llaves RSA dentro de `config/jwt/`.

Genera o regenera las llaves dentro del contenedor:

```bash
docker exec docker-app-1 bash -lc 'cd /var/www/html && php bin/console lexik:jwt:generate-keypair --overwrite --no-interaction'
```

Verifica que los archivos existan:

```bash
docker exec docker-app-1 bash -lc 'cd /var/www/html && ls -l config/jwt'
```

Si el directorio queda con propietario distinto a `www-data`, ajústalo:

```bash
docker exec docker-app-1 bash -lc 'cd /var/www/html && chown -R www-data:www-data config/jwt && chmod 640 config/jwt/private.pem && chmod 644 config/jwt/public.pem'
```

---

## 9. Sembrar Catálogo Público de Categories

PlayerTech usa un catálogo público de onboarding para categorías.

Ejecuta:

```bash
docker exec docker-app-1 bash -lc 'cd /var/www/html && php bin/console app:category:seed-onboarding'
```

Este comando llena `onboarding_categories` con el rango público usado por el frontend.

Si necesitas reparar la tabla en una base ya migrada, vuelve a ejecutarlo sin temor a romper el esquema:

```bash
docker exec docker-app-1 bash -lc 'cd /var/www/html && php bin/console app:category:seed-onboarding'
```

---

## 10. Verificar la API

Health check:

```bash
curl http://localhost/api/v1/health
```

Catálogo público:

```bash
curl http://localhost/api/v1/public/categories
```

Verificación completa recomendada:

```bash
curl -i http://localhost/api/v1/health
curl -i http://localhost/api/v1/public/categories
```

Si aparece un `500`, revisa primero permisos de `var/log` dentro del contenedor:

```bash
docker exec docker-app-1 bash -lc 'cd /var/www/html && ls -ld var var/log var/cache && ls -l var/log'
```

---

## 11. Verificar Mailpit

Abre:

```text
http://localhost:8025
```

Ahí verás correos de invitación, activación y recuperación.

Si quieres validarlo desde el contenedor:

```bash
docker exec docker-app-1 bash -lc 'php -r "echo \"Mailpit ready\\n\";"'
```

---

## 12. Listar Versiones de PHP en Ubuntu

Para ver qué versiones de PHP tienes disponibles en Ubuntu:

```bash
update-alternatives --list php
```

Si ese comando no devuelve nada, prueba:

```bash
ls /usr/bin/php*
```

Y para ver la versión activa:

```bash
php -v
```

Si necesitas seleccionar una versión instalada:

```bash
sudo update-alternatives --config php
```

---

## 13. Flujo Recomendado de Arranque

Cuando empieces sesión en Ubuntu:

```bash
sudo systemctl start docker
cd /ruta/al/proyecto/playertech
docker compose -f docker/docker-compose.yml up -d --build
docker exec docker-app-1 bash -lc 'cd /var/www/html && composer install'
docker exec docker-app-1 bash -lc 'cd /var/www/html && php bin/console doctrine:migrations:migrate --no-interaction'
docker exec docker-app-1 bash -lc 'cd /var/www/html && php bin/console lexik:jwt:generate-keypair --overwrite --no-interaction'
docker exec docker-app-1 bash -lc 'cd /var/www/html && php bin/console app:category:seed-onboarding'
docker exec docker-app-1 bash -lc 'cd /var/www/html && test -f vendor/autoload_runtime.php && echo OK'
curl http://localhost/api/v1/health
curl http://localhost/api/v1/public/categories
```

Si el entorno se recrea y aparecen errores de log o cache, corrige ownership dentro del contenedor:

```bash
docker exec docker-app-1 bash -lc 'cd /var/www/html && chown -R www-data:www-data var/log var/cache && chmod -R ug+rwX var/log var/cache'
```

---

## 14. Comandos Útiles

Entrar al contenedor:

```bash
docker exec -it docker-app-1 bash
```

Ver logs:

```bash
docker logs -f docker-app-1
```

Ver estado de migraciones:

```bash
docker exec docker-app-1 bash -lc 'cd /var/www/html && php bin/console doctrine:migrations:status'
```

Ver health desde el contenedor:

```bash
docker exec docker-app-1 bash -lc 'cd /var/www/html && curl -s http://localhost/api/v1/health'
```

Ver catálogo público desde el contenedor:

```bash
docker exec docker-app-1 bash -lc 'cd /var/www/html && curl -s http://localhost/api/v1/public/categories'
```

Reparar permisos de runtime:

```bash
docker exec docker-app-1 bash -lc 'cd /var/www/html && chown -R www-data:www-data var/log var/cache && chmod -R ug+rwX var/log var/cache'
```

---

## 15. Nota Operativa

El proyecto está pensado para ejecutarse dentro de Docker.  
Usa PHP local solo para tareas puntuales como inspección de versión o utilidades del sistema.

### Verificación mínima de entorno sano

Antes de dar por listo el entorno, confirma estos cuatro puntos:

1. `docker compose -f docker/docker-compose.yml ps` muestra `app`, `mysql` y `mailpit` activos.
2. `composer install` termina sin errores dentro de `docker-app-1`.
3. `php bin/console doctrine:migrations:migrate --no-interaction` termina sin errores dentro de `docker-app-1`.
4. `curl http://localhost/api/v1/health` y `curl http://localhost/api/v1/public/categories` responden `200 OK`.
