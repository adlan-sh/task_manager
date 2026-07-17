# Тестовое задание "Task manager"

### Для запуска проекта необходимо:
1. Запустить контейнеры командой ```docker compose up -d```
2. Зайти в контейнер приложения: ```docker exec -it ${APP_NAME}_app sh```
3. Запустить миграции ```php artisan migrate```
4. Запустить сидер ```php artisan db:seed```

Swagger открывается по URL: ```/swagger```

### Endpoints:

1. Создание задачи:
```POST /api/tasks``` \
Пример тела запроса:
```json
{
    "name": "Заполнить трудозатраты",
    "description": "Описание задачи",
    "due_date": "2025-01-20T15:00:00",
    "priority": "HIGH",
    "category_id": 1
}
```

2. Получить задачу: ```GET /api/tasks/{id}```


3. Получить список задач: ```GET /api/tasks?page=1&sort=due_date&search=Задача``` \
Query-параметры: \
```page``` - страница; \
```sort``` - столбец для сортировки; \
```seach``` - поиск задачи по названию


4. Обновить задачу: ```PATCH /api/tasks/{id}``` \
Пример тела запроса:
```json
{
    "name": "Задача 2",
    "due_date": "2025-01-20T15:00:00",
    "status": "DONE",
    "priority": "HIGH",
    "category_id": 3
}
```

5. Удалить задачу: ```DELETE /api/tasks/{id}```
