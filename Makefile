.PHONY: bash up down

up:
	docker-compose up -d

down:
	docker-compose down -v

bash:
	docker-compose exec web bash
