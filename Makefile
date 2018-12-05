SOURCE_ENV = set -a; source .env;

up:
	docker-compose up -d
	docker-compose logs -f

down:
	docker-compose down

restart:
	make down
	make up

logs:
	docker-compose logs -f

reload:
	$(SOURCE_ENV) \
	docker exec -it $$DOCKER_CONTAINER_NAME php artisan laravels reload

migrate:
	$(SOURCE_ENV) \
	docker exec -it $$DOCKER_CONTAINER_NAME php artisan migrate

migrate-refresh:
	$(SOURCE_ENV) \
	docker exec -it $$DOCKER_CONTAINER_NAME php artisan migrate:refresh

migrate-rollback:
	$(SOURCE_ENV) \
	docker exec -it $$DOCKER_CONTAINER_NAME php artisan migrate:rollback
