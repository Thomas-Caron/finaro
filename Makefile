CONTAINER=docker exec -it finaro_php
COMPOSE=docker compose

# Start the Docker containers in detached mode
up:
	$(COMPOSE) up -d

# Stop and remove the Docker containers
down:
	$(COMPOSE) down

# Build the Docker images without using the cache
build:
	$(COMPOSE) build --no-cache
	make up

# Restart the Docker containers
restart: down up

# Install dependencies and set up the application
install:
	$(CONTAINER) chmod -R 777 /var/www/html/var
	make composer-install
	make yarn-install
	make yarn-dev
	make sf-cc

# Open a bash shell in the app container
bash:
	$(CONTAINER) bash

# Run composer install command
composer-install:
	$(CONTAINER) composer install --no-scripts

# Run yarn install command
yarn-install:
	$(CONTAINER) yarn install

# Run yarn dev command
yarn-dev:
	$(CONTAINER) yarn dev

# Run yarn watch command
yarn-watch:
	$(CONTAINER) yarn watch

# Run yarn build command
yarn-build:
	$(CONTAINER) yarn build

# Run cache clear command
sf-cc:
	$(CONTAINER) bin/console cache:clear

# Create a new user
sf-user:
	$(CONTAINER) bin/console app:user:create

# Create a new database migration
sf-migration:
	$(CONTAINER) bin/console make:migration

# Run database migrations
db-migrate:
	$(CONTAINER) bin/console doctrine:migrations:migrate --no-interaction