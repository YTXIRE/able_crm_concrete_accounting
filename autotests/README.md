# Python Autotests

Набор smoke/regression тестов на `pytest + requests + playwright`.

Что покрыто:

- авторизация;
- основные backend-модули через API;
- основные frontend-модули через роуты и UI smoke;
- регрессии по критичным багам.

## Подготовка

Запускать из корня репозитория.

1. Поднять приложение:

```bash
docker compose up --build
```

2. Установить зависимости для тестов:

```bash
python3 -m pip install -r autotests/requirements.txt
python3 -m playwright install chromium
```

3. При необходимости скопировать переменные окружения:

```bash
cp autotests/.env.example autotests/.env
```

## Запуск

Подготовить docker test environment вручную:

```bash
./autotests/scripts/prepare_docker_test_env.sh
```

С пересборкой образов:

```bash
./autotests/scripts/prepare_docker_test_env.sh --build
```

Запуск всех тестов с автоматическим reset БД и миграциями:

```bash
./autotests/run_tests.sh
```

Запуск всех тестов с пересборкой образов:

```bash
./autotests/run_tests.sh --build
```

Все тесты:

```bash
python3 -m pytest -c autotests/pytest.ini autotests/tests
```

Только API:

```bash
python3 -m pytest -c autotests/pytest.ini autotests/tests/api -m api
```

Только UI:

```bash
python3 -m pytest -c autotests/pytest.ini autotests/tests/ui -m ui
```

## Важно

- Тесты создают сущности через API, поэтому их лучше гонять на локальном или выделенном тестовом стенде.
- Старые миграции тесты не трогают.
- Набор рассчитан на текущее правило `test-first`: сначала фиксируются сценарии, потом реализуются изменения в приложении.
- `prepare_docker_test_env.sh` перед стартом тестов удаляет текущие контейнеры и volume БД, затем поднимает сервисы заново и ждет завершения автопрогона миграций backend'ом.
