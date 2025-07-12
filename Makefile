SRC = ./docker-compose.yml
NAME = MIN_PRODUCT_CATALOG
ENV = .env
VENDOR = vendor

all : ${NAME}

${NAME}: ENV VENDOR
	docker exec product_catalog_api_2 php artisan migrate --force
	docker exec product_catalog_api_2 php artisan db:seed --force

VENDOR:
	docker composer build --no-cache
	docker composer up -d

ENV: cp laravel-api/.env.example laravel-api/.env

clean :
	docker compose -f ${SRC} down
