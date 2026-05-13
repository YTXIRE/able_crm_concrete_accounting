from __future__ import annotations

import json
from pathlib import Path

import pytest

from autotests.helpers.api_client import ApiClient


@pytest.mark.api
def test_auth_login_rejects_missing_password(api_client: ApiClient) -> None:
    response = api_client.request("POST", "/api/auth/login", data={"login": api_client.settings.admin_login})
    payload = api_client.assert_business_code(response, 400)
    assert payload["message"]


@pytest.mark.api
def test_auth_login_rejects_wrong_password(api_client: ApiClient) -> None:
    response = api_client.request(
        "POST",
        "/api/auth/login",
        data={"login": api_client.settings.admin_login, "password": "wrong-password"},
    )
    payload = api_client.assert_business_code(response, 400)
    assert payload["message"]


@pytest.mark.api
def test_auth_logout_rejects_invalid_token(api_client: ApiClient) -> None:
    response = api_client.request(
        "POST",
        "/api/auth/logout",
        data={"token": "definitely-invalid-token", "user_id": api_client.auth.user_id},
    )
    payload = api_client.assert_business_code(response, 404)
    assert payload["message"]


@pytest.mark.api
def test_auth_logout_rejects_missing_token(api_client: ApiClient) -> None:
    response = api_client.request("POST", "/api/auth/logout", data={"user_id": api_client.auth.user_id})
    payload = api_client.assert_business_code(response, 400)
    assert payload["message"]


@pytest.mark.api
def test_users_get_all_rejects_negative_limit(api_client: ApiClient) -> None:
    response = api_client.request(
        "GET",
        "/api/users/get-all",
        params=api_client.auth_query({"limit": -1, "offset": 0}),
    )
    payload = api_client.assert_business_code(response, 400)
    assert payload["message"]


@pytest.mark.api
def test_time_get_timezone_rejects_unknown_timezone(api_client: ApiClient) -> None:
    response = api_client.request(
        "GET",
        "/api/time/get-timezone",
        params=api_client.auth_query({"timezone_name": "Unknown/Timezone"}),
    )
    payload = api_client.assert_business_code(response, 404)
    assert payload["message"]


@pytest.mark.api
def test_time_get_timezone_success(api_client: ApiClient) -> None:
    timezone_name = api_client.get_timezones()[0]["timezone_name"]
    response = api_client.request(
        "GET",
        "/api/time/get-timezone",
        params=api_client.auth_query({"timezone_name": timezone_name}),
    )
    payload = api_client.assert_business_code(response, 200)
    assert int(payload["data"]) > 0


@pytest.mark.api
def test_settings_set_debt_rejects_invalid_active_flag(api_client: ApiClient) -> None:
    response = api_client.request(
        "POST",
        "/api/settings/set-debt",
        data=api_client.auth_form({"active": 2}),
    )
    payload = api_client.assert_business_code(response, 400)
    assert payload["message"]


@pytest.mark.api
def test_vendor_create_rejects_invalid_icon_id(api_client: ApiClient) -> None:
    response = api_client.request(
        "POST",
        "/api/vendors/create",
        data=api_client.auth_form({"name": api_client.unique_name("bad_vendor"), "icon_id": 999999999}),
    )
    payload = api_client.assert_business_code(response, 404)
    assert payload["message"]


@pytest.mark.api
def test_object_create_rejects_duplicate_name(api_client: ApiClient) -> None:
    name = api_client.unique_name("dup_object")
    api_client.create_object(name)
    response = api_client.request(
        "POST",
        "/api/objects/create",
        data=api_client.auth_form({"name": name}),
    )
    payload = api_client.assert_business_code(response, 400)
    assert payload["message"]


@pytest.mark.api
def test_material_type_create_requires_unit_id(api_client: ApiClient) -> None:
    response = api_client.request(
        "POST",
        "/api/material-types/create",
        data=api_client.auth_form({"name": api_client.unique_name("bad_material_type"), "units_measurement_volume_id": 0}),
    )
    payload = api_client.assert_business_code(response, 400)
    assert payload["message"]


@pytest.mark.api
def test_material_create_rejects_unknown_type(api_client: ApiClient) -> None:
    response = api_client.request(
        "POST",
        "/api/materials/create",
        data=api_client.auth_form({"name": api_client.unique_name("bad_material"), "type_id": 999999999}),
    )
    payload = api_client.assert_business_code(response, 404)
    assert payload["message"]


@pytest.mark.api
def test_legal_entity_create_rejects_unknown_type(api_client: ApiClient) -> None:
    response = api_client.request(
        "POST",
        "/api/legal-entities/create",
        data=api_client.auth_form({"name": "bad_legal_entity", "legal_entities_type_id": 999999999}),
    )
    payload = api_client.assert_business_code(response, 404)
    assert payload["message"]


@pytest.mark.api
def test_history_operation_rejects_invalid_confirmation_flag(api_client: ApiClient, seeded_data: dict) -> None:
    response = api_client.request(
        "POST",
        "/api/history-operation/create",
        data=api_client.auth_form(
            {
                "vendor_id": seeded_data["vendor"]["id"],
                "material_id": seeded_data["material"]["id"],
                "legal_entity_id": seeded_data["legal_entity"]["id"],
                "object_id": seeded_data["object"]["id"],
                "volume": "10.00",
                "price": "100.00",
                "total": "1000.00",
                "comment": "bad confirmed_data",
                "created_at": 1,
                "is_debt": 0,
                "confirmed_data": 2,
            }
        ),
    )
    payload = api_client.assert_business_code(response, 400)
    assert payload["message"]


@pytest.mark.api
def test_history_operation_rejects_invalid_debt_flag(api_client: ApiClient, seeded_data: dict) -> None:
    response = api_client.request(
        "POST",
        "/api/history-operation/create",
        data=api_client.auth_form(
            {
                "vendor_id": seeded_data["vendor"]["id"],
                "material_id": seeded_data["material"]["id"],
                "legal_entity_id": seeded_data["legal_entity"]["id"],
                "object_id": seeded_data["object"]["id"],
                "volume": "10.00",
                "price": "100.00",
                "total": "1000.00",
                "comment": "bad is_debt",
                "created_at": 1,
                "is_debt": 2,
                "confirmed_data": 1,
            }
        ),
    )
    payload = api_client.assert_business_code(response, 400)
    assert payload["message"]


@pytest.mark.api
def test_report_create_filter_rejects_empty_name(api_client: ApiClient) -> None:
    filters = [{"field": "vendor", "operation": "equal", "value": 1}]
    response = api_client.request(
        "POST",
        "/api/report/create-filter",
        data=api_client.auth_form({"name": "", "filters": json.dumps(filters, ensure_ascii=False)}),
    )
    payload = api_client.assert_business_code(response, 400)
    assert payload["message"]


@pytest.mark.api
def test_report_create_filter_rejects_invalid_filter_type(api_client: ApiClient) -> None:
    filters = [{"field": "invalid_field", "operation": "equal", "value": 1}]
    response = api_client.request(
        "POST",
        "/api/report/create-filter",
        data=api_client.auth_form(
            {"name": api_client.unique_name("bad_filter"), "filters": json.dumps(filters, ensure_ascii=False)}
        ),
    )
    payload = api_client.assert_business_code(response, 400)
    assert payload["message"]


@pytest.mark.api
def test_files_upload_rejects_missing_file(api_client: ApiClient) -> None:
    response = api_client.request(
        "POST",
        "/api/files/upload-avatar",
        data=api_client.auth_form({"id": api_client.auth.user_id}),
    )
    payload = api_client.assert_business_code(response, 400)
    assert payload["message"]


@pytest.mark.api
def test_files_upload_rejects_invalid_extension(api_client: ApiClient, tmp_path: Path) -> None:
    invalid_file = tmp_path / "avatar.txt"
    invalid_file.write_text("not an image", encoding="utf-8")

    with invalid_file.open("rb") as file_handle:
        response = api_client.request(
            "POST",
            "/api/files/upload-avatar",
            data=api_client.auth_form({"id": api_client.auth.user_id}),
            files={"avatar": (invalid_file.name, file_handle, "text/plain")},
        )

    payload = api_client.assert_business_code(response, 400)
    assert payload["message"]
