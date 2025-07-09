build:
	docker-compose build

run:
	docker-compose up -d

migrate:
	docker exec -ti yii2-php php yii migrate --interactive=0

add-test-user:
	docker exec -it yii2-php php /app/yii user/create user@example.com "Алибек Далгатов" securepassword

remind:
	docker exec -it yii2-php php /app/yii reminder/check
