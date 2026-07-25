# Ubuntu Setup Guide

Guía rápida para levantar PlayerTech en Ubuntu desde un `git clone` hasta la instalación de dependencias del proyecto.

---

## 1. Requisitos Previos

Antes de empezar, verifica que tengas instalado:

- `git`
- `docker`
- `docker compose`
- `php` compatible con el proyecto, si vas a ejecutar tareas fuera del contenedor

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

---

## 5. Verificar el Contenedor de la App

```bash
docker ps
```

Debes ver un contenedor similar a:

- `docker-app-1`

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

---

## 7. Aplicar Migraciones

```bash
docker exec docker-app-1 bash -lc 'cd /var/www/html && php bin/console doctrine:migrations:migrate --no-interaction'
```

---

## 8. Sembrar Catálogo Público de Categories

PlayerTech usa un catálogo público de onboarding para categorías.

Ejecuta:

```bash
docker exec docker-app-1 bash -lc 'cd /var/www/html && php bin/console app:category:seed-onboarding'
```

Este comando llena `onboarding_categories` con el rango público usado por el frontend.

---

## 9. Verificar la API

Health check:

```bash
curl http://localhost:8081/api/v1/health
```

Catálogo público:

```bash
curl http://localhost:8081/api/v1/public/categories
```

---

## 10. Verificar Mailpit

Abre:

```text
http://localhost:8025
```

Ahí verás correos de invitación, activación y recuperación.

---

## 11. Listar Versiones de PHP en Ubuntu

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

## 12. Flujo Recomendado de Arranque

Cuando empieces sesión en Ubuntu:

```bash
sudo systemctl start docker
cd /ruta/al/proyecto/playertech
docker compose -f docker/docker-compose.yml up -d --build
docker exec docker-app-1 bash -lc 'cd /var/www/html && composer install'
docker exec docker-app-1 bash -lc 'cd /var/www/html && php bin/console doctrine:migrations:migrate --no-interaction'
docker exec docker-app-1 bash -lc 'cd /var/www/html && php bin/console app:category:seed-onboarding'
```

---

## 13. Comandos Útiles

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

---

## 14. Nota Operativa

El proyecto está pensado para ejecutarse dentro de Docker.  
Usa PHP local solo para tareas puntuales como inspección de versión o utilidades del sistema.
