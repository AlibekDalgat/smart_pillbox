# Тестовое задание
https://docs.google.com/document/d/1HwnqSSITncwAX5mNgbbgHnFR4agbX65f/edit?usp=drive_link&ouid=117970448642527181084&rtpof=true&sd=true
<!-- ToC start -->
# Описание задачи
Разработать Backend для приложение контроля приёма лекарств — «Умная таблетница»

# Требования по функционалу
Используя Yii2 разработать минимальное, но рабочее API для управления лекарствами и напоминаниями.
- Пользователь добавляет лекарства в свой профиль. 
- Настраивает расписание приёма (например: «Аспирин, 2 таблетки, 3 раза в день»).
- Получает уведомления (вывод логов уведомлений в консоли на сервере).
- Отмечает принятые лекарства.
- Ведётся история приёма лекарств.

# Реализация
- Использование актуальной версии Yii2 Framework, версия PHP 8.3.
- В качестве сервера для БД MySQL.
- Контейнеризация с помощью Docker и docker-compose

**Структура проекта:**
```
.
├── app             // дефолтный проект Yii2
│   ├── commands    // команды для добавления пользователя и напоминания пользователей о приёме
│   ├── config      // общие конфигурации приложения
│   ├── controllers // слой действий
│   ├── migrations  // миграция таблиц
│   └── models      // слой объектов бизнес-логики
└── crom            // команда для автоматического запуска консольного приложения
```

# Запуск
```
make build && make run
```
Если приложение запускается впервые, необходимо применить миграции к базе данных (до отправки запроса на сервер):
```
make migrate
```
Добавление тестового пользователя
```
add-test-user
```
Ручной запуск консольной команды для уведомления пользователей о приёме (логи о напоминании в app/runtime/logs/app.log)
```
remind
```

# Примеры
Запросы сгенерированы командой curl
### 1. POST /api/login
**Запрос:**
```
curl --location --request POST 'localhost:8080/api/login' \
--header 'Content-Type: application/json' \
--data-raw '{
    "email": "user@example.com",
    "password": "securepassword"
}'
```
**Тело ответа:**
```
{
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwODAiLCJhdWQiOiJodHRwOi8vbG9jYWxob3N0OjgwODAiLCJqdGkiOiI2ODZlM2IyNjczZjUwIiwiaWF0IjoxNzUyMDU0NTY2LjQ3NzgyNCwiZXhwIjoxNzUyMDU4MTY2LjQ3Nzg0LCJ1aWQiOjF9.tggpHPqkqcdD5kn8IeDUeHvPme_SHWIn0dSYKj6J6aM",
    "user": {
        "id": 1,
        "name": "Алибек Далгатов"
    }
}
```

### 2. POST /api/medicines
**Запрос:**
```
curl --location --request POST 'localhost:8080/api/medicines' \
--header 'Content-Type: application/json' \
--header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwODAiLCJhdWQiOiJodHRwOi8vbG9jYWxob3N0OjgwODAiLCJqdGkiOiI2ODZlM2IyNjczZjUwIiwiaWF0IjoxNzUyMDU0NTY2LjQ3NzgyNCwiZXhwIjoxNzUyMDU4MTY2LjQ3Nzg0LCJ1aWQiOjF9.tggpHPqkqcdD5kn8IeDUeHvPme_SHWIn0dSYKj6J6aM' \
--data-raw '{
    "name":"нурафен",
    "dose":"100mg",
    "description":"От всего"
}'
```
**Тело ответа:**
```
{
    "user_id": 1,
    "created_at": 1752054770,
    "name": "нурафен",
    "dose": "100mg",
    "description": "От всего",
    "id": 1
}
```

### 3. GET /api/medicines
**Запрос:**
```
curl --location --request GET 'localhost:8080/api/medicines' \
--header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwODAiLCJhdWQiOiJodHRwOi8vbG9jYWxob3N0OjgwODAiLCJqdGkiOiI2ODZlM2IyNjczZjUwIiwiaWF0IjoxNzUyMDU0NTY2LjQ3NzgyNCwiZXhwIjoxNzUyMDU4MTY2LjQ3Nzg0LCJ1aWQiOjF9.tggpHPqkqcdD5kn8IeDUeHvPme_SHWIn0dSYKj6J6aM'
```
**Тело ответа:**
```
[
    {
        "id": 1,
        "name": "нурафен",
        "dose": "100mg",
        "description": "От всего"
    }
]
```

### 4. POST /api/reminders
**Запрос:**
```
curl --location --request POST 'localhost:8080/api/reminders' \
--header 'Content-Type: application/json' \
--header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwODAiLCJhdWQiOiJodHRwOi8vbG9jYWxob3N0OjgwODAiLCJqdGkiOiI2ODZlM2IyNjczZjUwIiwiaWF0IjoxNzUyMDU0NTY2LjQ3NzgyNCwiZXhwIjoxNzUyMDU4MTY2LjQ3Nzg0LCJ1aWQiOjF9.tggpHPqkqcdD5kn8IeDUeHvPme_SHWIn0dSYKj6J6aM' \
--data-raw '
{
    "medicine_id": 1,
    "time": ["09:00", "18:00"],
    "begin_date": "2025-01-10",
    "finish_date": "2025-12-10",
    "comment": "После еды"
}'
```
**Тело ответа:**
```
{
    "created_at": 1752055737,
    "medicine_id": 1,
    "time": ["09:00", "18:00"],
    "begin_date": "2025-01-10",
    "finish_date": "2025-12-10",
    "comment": "После еды",
    "id": 2
}
```

### 5. GET /api/reminders
**Запрос:**
```
curl --location --request GET 'localhost:8080/api/reminders' \
--header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwODAiLCJhdWQiOiJodHRwOi8vbG9jYWxob3N0OjgwODAiLCJqdGkiOiI2ODZlM2IyNjczZjUwIiwiaWF0IjoxNzUyMDU0NTY2LjQ3NzgyNCwiZXhwIjoxNzUyMDU4MTY2LjQ3Nzg0LCJ1aWQiOjF9.tggpHPqkqcdD5kn8IeDUeHvPme_SHWIn0dSYKj6J6aM'
```
**Тело ответа:**
```
[
    {
        "medicine_id": 1,
        "time": ["09:00", "18:00"],
        "begin_date": "2025-01-10",
        "finish_date": "2025-12-10",
        "comment": "После еды"
    }
]
```

### 6. POST /api/reminders/{id}/take
**Запрос:**
```
curl --location --request POST 'localhost:8080/api/reminders/1/take' \
--header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwODAiLCJhdWQiOiJodHRwOi8vbG9jYWxob3N0OjgwODAiLCJqdGkiOiI2ODZlM2IyNjczZjUwIiwiaWF0IjoxNzUyMDU0NTY2LjQ3NzgyNCwiZXhwIjoxNzUyMDU4MTY2LjQ3Nzg0LCJ1aWQiOjF9.tggpHPqkqcdD5kn8IeDUeHvPme_SHWIn0dSYKj6J6aM'
```
**Тело ответа:**
```
{
    "status": "success"
}
```

### 7. DELETE /api/reminders/{id}
**Запрос:**
```
curl --location --request DELETE 'localhost:8080/api/reminders/1' \
--header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwODAiLCJhdWQiOiJodHRwOi8vbG9jYWxob3N0OjgwODAiLCJqdGkiOiI2ODZlM2IyNjczZjUwIiwiaWF0IjoxNzUyMDU0NTY2LjQ3NzgyNCwiZXhwIjoxNzUyMDU4MTY2LjQ3Nzg0LCJ1aWQiOjF9.tggpHPqkqcdD5kn8IeDUeHvPme_SHWIn0dSYKj6J6aM'
```
**Тело ответа:**
```
{
    "status": "success"
}
```