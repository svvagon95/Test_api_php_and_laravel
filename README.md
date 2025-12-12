# To-Do Tasks REST API (Plain PHP + Laravel)

Тестовое задание: разработка простого REST API для управления списком задач (To-Do List).

Проект реализован в **двух вариантах**:
- **plain_php_api/** — реализация на чистом PHP (без фреймворков)
- **laravel_api/** — реализация на Laravel

Обе версии работают с одной базой данных MySQL и предоставляют одинаковый REST API.

---

## Стек технологий

- PHP 8.x
- MySQL
- Laravel 12.x
- Composer
- HTML + JavaScript (простой клиент для демонстрации API)
- Git

---

## Структура проекта

```text
.
├── plain_php_api/
│   ├── index.php            # Входная точка и простой роутер
│   ├── db.php               # Подключение к MySQL (PDO) и создание таблицы
│   ├── TaskController.php   # CRUD-логика для задач
│   ├── frontend.html        # Минимальный HTML+JS клиент
│   └── styles.css           # Стили для фронтенда
│
└── laravel_api/
    ├── app/Models/Task.php
    ├── app/Http/Controllers/TaskController.php
    ├── routes/api.php
    ├── database/migrations/*create_tasks_table.php
    └── ...

