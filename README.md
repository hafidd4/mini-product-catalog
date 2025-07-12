

### Docker Setup
Project Mini product catalog

#### Build Docker Images Without Cache

```sh
docker compose build --no-cache
```

#### Start Docker Containers in Detached Mode

```sh
docker compose up -d
```

#### Run Database Migrations

```sh
docker exec product_catalog_api_2 php artisan migrate --force
```

#### Seed the Database

```sh
docker exec product_catalog_api_2 php artisan db:seed --force
```
