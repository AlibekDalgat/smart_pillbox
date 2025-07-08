docker-compose up -d

docker exec -ti yii2-php php yii migrate

docker exec -it yii2-php php /app/yii user/create user@example.com "Иван Иванов" securepassword

docker exec -it yii2-php php /app/yii reminder/check