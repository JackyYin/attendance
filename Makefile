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
	docker exec -it $$DOCKER_CONTAINER_NAME php artisan swoole:http reload
