# Coverage Matrix

Актуальная матрица покрытия по модулям, сценариям и слоям тестов.

## Summary

- Main suite: `314 passed`
- Fault-injection suite: `56 passed`
- Demo read-only UI suite: `17 passed` (included in main suite)
- Total automated tests: `370`
- API endpoint coverage: `58 / 58`
- API business codes covered: `200`, `201`, `400`, `404`, `405`, `500`

## Legend

- `yes` - сценарий покрыт автотестами
- `partial` - покрыт частично или только на одном слое
- `n/a` - сценарий не относится к модулю

## Module Matrix

| Module | API | UI | View/List | Create | Edit | Delete | Search | Archive/Restore | Export | Errors/Validation | Fault 500 | Demo Read-Only | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| Auth | yes | yes | n/a | n/a | n/a | logout | n/a | n/a | n/a | yes | yes | n/a | login/logout, redirects, session restore |
| Users | yes | yes | yes | yes | yes | yes | n/a | n/a | n/a | yes | yes | yes | admin users + password/profile flows |
| Time | yes | yes | yes | n/a | yes | n/a | timezone lookup | n/a | n/a | yes | yes | yes | get/set timezone covered; settings selector and header timezone switch locked in demo |
| Settings | yes | yes | yes | n/a | yes | n/a | n/a | n/a | n/a | yes | yes | yes | debt toggle and user-management controls covered |
| Vendors | yes | yes | yes | yes | yes | yes | n/a | yes | n/a | yes | yes | yes | icons picker, pagination, and read-only row actions covered |
| Objects | yes | yes | yes | yes | yes | yes | yes | yes | n/a | yes | yes | yes | object search and read-only row actions covered on API and UI |
| Material Types | yes | partial | yes | yes | partial | n/a | n/a | n/a | n/a | yes | yes | yes | API fully covered; UI create flow and demo edit lock covered |
| Materials | yes | yes | yes | yes | yes | n/a | yes | n/a | n/a | yes | yes | yes | search/edit flows and demo edit lock covered |
| Legal Entity Types | yes | partial | yes | n/a | n/a | n/a | n/a | n/a | n/a | yes | yes | n/a | used through selects in UI |
| Legal Entities | yes | yes | yes | yes | yes | yes | n/a | yes | n/a | yes | yes | yes | create/edit/archive/restore and read-only row actions covered |
| History Operations | yes | yes | yes | yes | yes | yes | partial | n/a | n/a | yes | yes | yes | nested browse flow, form stepper, and read-only row actions covered |
| Payments | yes | yes | yes | yes | yes | yes | partial | n/a | n/a | yes | yes | yes | invalid amount/edit flow and read-only row actions covered |
| Reports | yes | yes | yes | filter create | filter edit | filter delete | filter select | n/a | yes | yes | yes | yes | base/advanced/filter builder and read-only mutation locks covered |
| Dashboard | yes | yes | yes | n/a | n/a | n/a | n/a | n/a | n/a | partial | yes | n/a | list/summary route and API covered |
| Files / Avatar | yes | yes | partial | upload | partial | n/a | n/a | n/a | n/a | yes | yes | yes | API and UI upload covered; avatar URL served correctly and control disabled in demo |
| Icons | yes | yes | yes | n/a | n/a | n/a | pagination | n/a | n/a | partial | yes | n/a | icon chooser dialog covered |

## Test File Index

### API

- `tests/api/test_auth_extended.py`
- `tests/api/test_module_smoke.py`
- `tests/api/test_negative_common.py`
- `tests/api/test_negative_mutation_common.py`
- `tests/api/test_negative_validations.py`
- `tests/api/test_regressions.py`
- `tests/api/test_settings_extended.py`
- `tests/api/test_success_remaining.py`
- `tests/api/test_fault_injection_500.py`

### UI

- `tests/ui/test_auth_and_navigation.py`
- `tests/ui/test_auth_ui_extended.py`
- `tests/ui/test_demo_read_only_ui.py`
- `tests/ui/test_dialog_validations.py`
- `tests/ui/test_directories_ui_crud.py`
- `tests/ui/test_edit_forms_ui_extended.py`
- `tests/ui/test_history_flow.py`
- `tests/ui/test_payments_ui_extended.py`
- `tests/ui/test_reports_ui_extended.py`
- `tests/ui/test_settings_ui_extended.py`
- `tests/ui/test_tabs_and_header.py`
- `tests/ui/test_vendors_icon_picker.py`

## Practical Interpretation

На текущем этапе покрыты:

- все API маршруты приложения;
- все рабочие статус-коды API;
- базовые и расширенные UI-сценарии авторизации;
- CRUD и edit-формы по ключевым справочникам;
- ключевые пользовательские сценарии `History`, `Payments`, `Reports`, `Settings`, `UserSettings`;
- UI avatar upload с проверкой доступности загруженного файла;
- `demo/read-only` блокировки для основных mutating UI-контролов в `Vendors`, `Objects`, `Materials`, `Material Types`, `Legal Entities`, `History`, `Payments`, `Reports`, `Settings`, `UserSettings`.

## Remaining Work If Needed

Если захочется углубить покрытие еще сильнее, следующий логичный слой:

- расширить `demo/read-only` проверки на оставшиеся вторичные controls и маршруты;
- больше edge cases для `History` и `Reports`;
- дополнительные UI negative cases для `edit`-форм;
- экспорт/печать через более глубокие браузерные проверки.
