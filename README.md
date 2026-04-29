# Able CRM Concrete Accounting

## Что было исправлено

- Исправлен конфиг `frontend/000-default.conf`: вместо Apache-конфига теперь используется корректный `nginx`-конфиг для SPA.
- Исправлен `docker-compose.yaml`: убран устаревший `version`, улучшен healthcheck MariaDB, добавлены `YII_ENV=prod` и `YII_DEBUG=false` для backend.
- Убран bind-mount `./backend:/var/www/html` из основного Docker Compose, чтобы запуск был воспроизводимым из собранного образа.
- В backend добавлен `docker-entrypoint.sh`, который автоматически применяет `yii migrate/up --interactive=0` перед стартом Apache.
- Bootstrap-файлы Yii (`backend/yii`, `backend/web/index.php`) теперь учитывают переменные окружения `YII_ENV` и `YII_DEBUG`.

## Что проверено

- `docker compose build`
- `docker compose up --build -d`
- `http://localhost:8050` отвечает `200 OK`
- `POST http://localhost:8020/api/auth/login` отвечает успешно

Пример успешной проверки backend:

```json
{"status":"OK","code":200,"data":{"token":"...","id":1,"is_demo":0}}
```

## Как запустить

1. Собрать и поднять контейнеры:

```bash
docker compose up --build -d
```

2. Открыть frontend:

```text
http://localhost:8050
```

3. Backend доступен по адресу:

```text
http://localhost:8020
```

Примечание: backend здесь API-приложение, поэтому корень может не быть пользовательской страницей. Для проверки API используйте логин-эндпоинт.

## Проверка API

```bash
curl -X POST http://localhost:8020/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"login":"admin","password":"admin"}'
```

Тестовый пользователь, создаваемый миграциями:

- Логин: `admin`
- Пароль: `admin`

## Полезные команды

Остановить контейнеры:

```bash
docker compose down
```

Остановить контейнеры и удалить volume БД, чтобы развернуть все заново с чистой схемой:

```bash
docker compose down -v
```

Посмотреть логи:

```bash
docker compose logs -f
```

## Прогресс работ

Ход выполнения сохранен в файле `PROGRESS.md`.
