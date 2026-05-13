# API Coverage Map

Текущее состояние карты покрытия API-тестов для набора `autotests/tests/api`.

## Summary

- Всего endpoint'ов в `backend/controllers/api`: `58`
- Endpoint'ов, которые покрыты API-тестами: `58 / 58`
- Покрытые бизнес-коды по основному набору: `200`, `201`, `400`, `404`, `405`
- Покрытие `500`: есть отдельный optional fault-injection слой

## Что считается полным покрытием сейчас

Набор тестов покрывает для всех endpoint'ов все объявленные пользовательские статусы:

- успешные ответы `200/201`;
- валидационные ошибки `400`;
- ошибки отсутствующих сущностей/токена `404`;
- ошибки неверного HTTP-метода `405`.
- ошибки инфраструктуры `500` через отдельный fault-injection слой.

## Как покрыт `500`

`500` покрывается отдельным optional-набором `autotests/tests/api/test_fault_injection_500.py`.

Он запускается только при `RUN_FAULT_500=1` и использует controlled fault injection:

- временно останавливает сервис `db` через `docker compose`;
- выполняет API-запросы с валидным payload;
- проверяет возврат бизнес-кода `500`.

Это нужно потому, что стабильное покрытие `500` без fault injection невозможно. Для него требуется искусственно создать инфраструктурный сбой, например:

- временное отключение БД;
- подмена конфигурации окружения;
- принудительный сбой файловой системы;
- тестовый backend-хук для искусственного exception-path.

## Покрытые группы endpoint'ов

Полностью покрыты по non-500 статусам:

- `auth`
- `users`
- `time`
- `settings`
- `units-measurement-volume`
- `legal-entities-types`
- `icons`
- `vendors`
- `objects`
- `material-types`
- `materials`
- `legal-entities`
- `history-operation`
- `payments`
- `dashboard`
- `report`
- `files`

## Где это покрыто в тестах

- smoke и happy-path: `autotests/tests/api/test_module_smoke.py`
- common GET negative coverage: `autotests/tests/api/test_negative_common.py`
- common mutation negative coverage: `autotests/tests/api/test_negative_mutation_common.py`
- validation-specific negative coverage: `autotests/tests/api/test_negative_validations.py`
- targeted regressions: `autotests/tests/api/test_regressions.py`
- remaining success flows: `autotests/tests/api/test_success_remaining.py`
- fault-injection `500`: `autotests/tests/api/test_fault_injection_500.py`

## Итог

На текущем этапе API-набор можно считать полным:

- все маршруты затронуты;
- все declared `200/201/400/404/405` статусы покрыты;
- для `500` подготовлен отдельный управляемый fault-injection сценарий;
- регрессии по найденным багам зафиксированы тестами.
