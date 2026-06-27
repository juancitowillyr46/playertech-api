# ===========================
# PlayerTech Makefile
# ===========================

APP_CONTAINER=docker-app-1
# docker compose exec app
APP_USER=www-data

# ---------------------------
# Docker
# ---------------------------

up:
	docker compose up -d

down:
	docker compose down

restart:
	docker compose down
	docker compose up -d

logs:
	docker compose logs -f

# ---------------------------
# Shell
# ---------------------------

shell:
	docker exec -it $(APP_CONTAINER) bash

shell-app:
	docker exec -u $(APP_USER) -it $(APP_CONTAINER) bash

# ---------------------------
# Symfony Console
# ---------------------------

console:
	docker exec -u $(APP_USER) -it $(APP_CONTAINER) php bin/console

# ---------------------------
# Composer
# ---------------------------

composer:
	docker exec -u $(APP_USER) -it $(APP_CONTAINER) composer

# ---------------------------
# Cache
# ---------------------------

cache-clear:
	docker exec -u $(APP_USER) -it $(APP_CONTAINER) php bin/console cache:clear

# ---------------------------
# Doctrine
# ---------------------------

migration-diff:
	docker exec -u $(APP_USER) -it $(APP_CONTAINER) php bin/console doctrine:migrations:diff

migration-migrate:
	docker exec -u $(APP_USER) -it $(APP_CONTAINER) php bin/console doctrine:migrations:migrate

schema-validate:
	docker exec -u $(APP_USER) -it $(APP_CONTAINER) php bin/console doctrine:schema:validate

schema-sql:
	docker exec -u $(APP_USER) -it $(APP_CONTAINER) php bin/console doctrine:schema:update --dump-sql

# ---------------------------
# Logs
# ---------------------------

tail-log:
	docker exec -it $(APP_CONTAINER) tail -f /var/www/html/var/log/dev.log