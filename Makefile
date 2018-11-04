up:
	docker-compose up -d

down:
	docker-compose down

restart:
	make down
	make up

logs:
	docker-compose logs -f
