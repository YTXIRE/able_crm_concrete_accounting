from __future__ import annotations

from collections.abc import Callable
from pathlib import Path
from typing import Any

import pytest

from autotests.helpers.api_client import ApiClient


PayloadFactory = Callable[[ApiClient, dict[str, Any], dict[str, Any]], dict[str, Any]]


def _users_create(api: ApiClient, _seeded: dict[str, Any], _aux: dict[str, Any]) -> dict[str, Any]:
    return {
        "login": api.unique_name("user_create"),
        "email": f"{api.unique_name('user_email')}@example.com",
        "password": "secret1",
        "is_demo": 1,
    }


def _users_update(api: ApiClient, _seeded: dict[str, Any], aux: dict[str, Any]) -> dict[str, Any]:
    return {
        "id": aux["user"]["id"],
        "login": api.unique_name("user_updated"),
        "email": f"{api.unique_name('user_updated_email')}@example.com",
        "is_demo": 1,
    }


def _users_change_password(_api: ApiClient, _seeded: dict[str, Any], aux: dict[str, Any]) -> dict[str, Any]:
    return {"id": aux["user"]["id"], "password": "secret2"}


def _users_delete(_api: ApiClient, _seeded: dict[str, Any], aux: dict[str, Any]) -> dict[str, Any]:
    return {"id": aux["user"]["id"]}


def _timezone_set(api: ApiClient, _seeded: dict[str, Any], _aux: dict[str, Any]) -> dict[str, Any]:
    return {"timezone_id": 1}


def _settings_set_debt(_api: ApiClient, _seeded: dict[str, Any], _aux: dict[str, Any]) -> dict[str, Any]:
    return {"active": 1}


def _vendor_create(_api: ApiClient, _seeded: dict[str, Any], _aux: dict[str, Any]) -> dict[str, Any]:
    return {"name": "bad_vendor", "icon_id": 1}


def _vendor_update(_api: ApiClient, seeded: dict[str, Any], _aux: dict[str, Any]) -> dict[str, Any]:
    return {"id": seeded["vendor"]["id"], "name": "vendor_updated", "icon_id": 1}


def _vendor_restore(_api: ApiClient, seeded: dict[str, Any], _aux: dict[str, Any]) -> dict[str, Any]:
    return {"id": seeded["vendor"]["id"]}


def _vendor_delete(_api: ApiClient, seeded: dict[str, Any], _aux: dict[str, Any]) -> dict[str, Any]:
    return {"id": seeded["vendor"]["id"]}


def _object_create(_api: ApiClient, _seeded: dict[str, Any], _aux: dict[str, Any]) -> dict[str, Any]:
    return {"name": "bad_object"}


def _object_update(_api: ApiClient, seeded: dict[str, Any], _aux: dict[str, Any]) -> dict[str, Any]:
    return {"id": seeded["object"]["id"], "name": "object_updated"}


def _object_restore(_api: ApiClient, seeded: dict[str, Any], _aux: dict[str, Any]) -> dict[str, Any]:
    return {"id": seeded["object"]["id"]}


def _object_delete(_api: ApiClient, seeded: dict[str, Any], _aux: dict[str, Any]) -> dict[str, Any]:
    return {"id": seeded["object"]["id"]}


def _material_type_create(_api: ApiClient, seeded: dict[str, Any], _aux: dict[str, Any]) -> dict[str, Any]:
    return {"name": "bad_material_type", "units_measurement_volume_id": seeded["units"][0]["id"]}


def _material_type_update(_api: ApiClient, seeded: dict[str, Any], _aux: dict[str, Any]) -> dict[str, Any]:
    return {
        "id": seeded["material_type"]["id"],
        "name": seeded["material_type"]["name"],
        "units_measurement_volume_id": seeded["units"][0]["id"],
    }


def _material_create(_api: ApiClient, seeded: dict[str, Any], _aux: dict[str, Any]) -> dict[str, Any]:
    return {"name": "bad_material", "type_id": seeded["material_type"]["id"]}


def _material_update(_api: ApiClient, seeded: dict[str, Any], _aux: dict[str, Any]) -> dict[str, Any]:
    return {"id": seeded["material"]["id"], "name": "material_updated", "type_id": seeded["material_type"]["id"]}


def _legal_entity_create(_api: ApiClient, seeded: dict[str, Any], _aux: dict[str, Any]) -> dict[str, Any]:
    return {"name": "bad_legal_entity", "legal_entities_type_id": seeded["legal_entity_types"][0]["id"]}


def _legal_entity_update(_api: ApiClient, seeded: dict[str, Any], _aux: dict[str, Any]) -> dict[str, Any]:
    return {
        "id": seeded["legal_entity"]["id"],
        "name": "legal_updated",
        "legal_entities_type_id": seeded["legal_entity_types"][0]["id"],
    }


def _legal_entity_restore(_api: ApiClient, seeded: dict[str, Any], _aux: dict[str, Any]) -> dict[str, Any]:
    return {"id": seeded["legal_entity"]["id"]}


def _legal_entity_delete(_api: ApiClient, seeded: dict[str, Any], _aux: dict[str, Any]) -> dict[str, Any]:
    return {"id": seeded["legal_entity"]["id"]}


def _history_create(_api: ApiClient, seeded: dict[str, Any], _aux: dict[str, Any]) -> dict[str, Any]:
    return {
        "vendor_id": seeded["vendor"]["id"],
        "material_id": seeded["material"]["id"],
        "legal_entity_id": seeded["legal_entity"]["id"],
        "object_id": seeded["object"]["id"],
        "volume": "1.00",
        "price": "1.00",
        "total": "1.00",
        "comment": "bad history",
        "created_at": 1,
        "is_debt": 0,
        "confirmed_data": 1,
    }


def _history_update(_api: ApiClient, seeded: dict[str, Any], aux: dict[str, Any]) -> dict[str, Any]:
    return {
        "id": aux["history_operation"]["id"],
        "vendor_id": seeded["vendor"]["id"],
        "material_id": seeded["material"]["id"],
        "legal_entity_id": seeded["legal_entity"]["id"],
        "object_id": seeded["object"]["id"],
        "volume": "1.00",
        "price": "1.00",
        "total": "1.00",
        "comment": "history update",
        "created_at": 1,
        "confirmed_data": 1,
    }


def _history_delete(_api: ApiClient, _seeded: dict[str, Any], aux: dict[str, Any]) -> dict[str, Any]:
    return {"id": aux["history_operation"]["id"]}


def _payment_create(_api: ApiClient, seeded: dict[str, Any], _aux: dict[str, Any]) -> dict[str, Any]:
    return {
        "vendor_id": seeded["vendor"]["id"],
        "legal_entity_id": seeded["legal_entity"]["id"],
        "material_type_id": seeded["material_type"]["id"],
        "amount": "1.00",
        "operation_type": "buy",
        "created_at": 1,
    }


def _payment_update(_api: ApiClient, seeded: dict[str, Any], aux: dict[str, Any]) -> dict[str, Any]:
    return {
        "id": aux["payment"]["id"],
        "vendor_id": seeded["vendor"]["id"],
        "legal_entity_id": seeded["legal_entity"]["id"],
        "material_type_id": seeded["material_type"]["id"],
        "amount": "2.00",
        "operation_type": "buy",
        "created_at": 1,
    }


def _payment_delete(_api: ApiClient, _seeded: dict[str, Any], aux: dict[str, Any]) -> dict[str, Any]:
    return {"id": aux["payment"]["id"]}


def _report_advanced(_api: ApiClient, seeded: dict[str, Any], _aux: dict[str, Any]) -> dict[str, Any]:
    return {
        "filters": '[{"field":"vendor","operation":"equal","value":%d,"unity":"and"}]' % seeded["vendor"]["id"]
    }


def _report_create_filter(api: ApiClient, seeded: dict[str, Any], _aux: dict[str, Any]) -> dict[str, Any]:
    return {
        "name": api.unique_name("filter_create"),
        "filters": '[{"field":"vendor","operation":"equal","value":%d}]' % seeded["vendor"]["id"],
    }


def _report_update_filter(api: ApiClient, seeded: dict[str, Any], aux: dict[str, Any]) -> dict[str, Any]:
    return {
        "id": aux["filter"]["id"],
        "name": api.unique_name("filter_update"),
        "filters": '[{"field":"vendor","operation":"equal","value":%d}]' % seeded["vendor"]["id"],
    }


def _report_delete_filter(_api: ApiClient, _seeded: dict[str, Any], aux: dict[str, Any]) -> dict[str, Any]:
    return {"id": aux["filter"]["id"]}


def _avatar_upload(_api: ApiClient, _seeded: dict[str, Any], _aux: dict[str, Any]) -> dict[str, Any]:
    return {}


MUTATION_SPECS: list[tuple[str, str, str, str, str, PayloadFactory]] = [
    ("users.create", "POST", "GET", "/api/users/create", "data", _users_create),
    ("users.update-info", "PUT", "POST", "/api/users/update-info", "json", _users_update),
    ("users.change-password", "PUT", "POST", "/api/users/change-password", "json", _users_change_password),
    ("users.delete", "DELETE", "POST", "/api/users/delete", "json", _users_delete),
    ("time.set-timezone", "POST", "GET", "/api/time/set-timezone", "data", _timezone_set),
    ("settings.set-debt", "POST", "GET", "/api/settings/set-debt", "data", _settings_set_debt),
    ("vendors.create", "POST", "GET", "/api/vendors/create", "data", _vendor_create),
    ("vendors.update", "PUT", "POST", "/api/vendors/update", "json", _vendor_update),
    ("vendors.restore", "PUT", "POST", "/api/vendors/restore", "json", _vendor_restore),
    ("vendors.delete", "DELETE", "POST", "/api/vendors/delete", "json", _vendor_delete),
    ("objects.create", "POST", "GET", "/api/objects/create", "data", _object_create),
    ("objects.update", "PUT", "POST", "/api/objects/update", "json", _object_update),
    ("objects.restore", "PUT", "POST", "/api/objects/restore", "json", _object_restore),
    ("objects.delete", "DELETE", "POST", "/api/objects/delete", "json", _object_delete),
    ("material-types.create", "POST", "GET", "/api/material-types/create", "data", _material_type_create),
    ("material-types.update", "PUT", "POST", "/api/material-types/update", "json", _material_type_update),
    ("materials.create", "POST", "GET", "/api/materials/create", "data", _material_create),
    ("materials.update", "PUT", "POST", "/api/materials/update", "json", _material_update),
    ("legal-entities.create", "POST", "GET", "/api/legal-entities/create", "data", _legal_entity_create),
    ("legal-entities.update", "PUT", "POST", "/api/legal-entities/update", "json", _legal_entity_update),
    ("legal-entities.restore", "PUT", "POST", "/api/legal-entities/restore", "json", _legal_entity_restore),
    ("legal-entities.delete", "DELETE", "POST", "/api/legal-entities/delete", "json", _legal_entity_delete),
    ("history.create", "POST", "GET", "/api/history-operation/create", "data", _history_create),
    ("history.update", "POST", "GET", "/api/history-operation/update", "data", _history_update),
    ("history.delete", "DELETE", "POST", "/api/history-operation/delete", "json", _history_delete),
    ("payments.create", "POST", "GET", "/api/payments/create", "data", _payment_create),
    ("payments.update", "PUT", "POST", "/api/payments/update", "json", _payment_update),
    ("payments.delete", "DELETE", "POST", "/api/payments/delete", "json", _payment_delete),
    ("report.get-advanced", "POST", "GET", "/api/report/get-advanced", "data", _report_advanced),
    ("report.create-filter", "POST", "GET", "/api/report/create-filter", "data", _report_create_filter),
    ("report.update-filter", "PUT", "POST", "/api/report/update-filter", "json", _report_update_filter),
    ("report.delete-filter", "DELETE", "POST", "/api/report/delete-filter", "json", _report_delete_filter),
    ("files.upload-avatar", "POST", "GET", "/api/files/upload-avatar", "multipart", _avatar_upload),
]


def _request_with_mode(
    api_client: ApiClient,
    http_method: str,
    path: str,
    payload_mode: str,
    payload: dict[str, Any],
    tiny_png: Path,
    *,
    token: str | None,
) -> Any:
    if payload_mode == "data":
        return api_client.request(http_method, path, data=api_client.auth_form(payload, token=token))
    if payload_mode == "json":
        return api_client.request(http_method, path, json=api_client.auth_json(payload, token=token))
    if payload_mode == "multipart":
        with tiny_png.open("rb") as file_handle:
            return api_client.request(
                http_method,
                path,
                data=api_client.auth_form({"id": api_client.auth.user_id}, token=token),
                files={"avatar": (tiny_png.name, file_handle, "image/png")},
            )
    raise AssertionError(f"Unsupported payload mode: {payload_mode}")


@pytest.mark.api
class TestNegativeMutationCommon:
    @pytest.mark.parametrize("_label,_method,_wrong_method,path,payload_mode,payload_factory", MUTATION_SPECS)
    def test_mutation_endpoints_require_token(
            self,
            _label: str,
            _method: str,
            _wrong_method: str,
            path: str,
            payload_mode: str,
            payload_factory: PayloadFactory,
            api_client: ApiClient,
            seeded_data: dict[str, Any],
            aux_entities: dict[str, Any],
            tiny_png: Path,
    ) -> None:
        payload = payload_factory(api_client, seeded_data, aux_entities)
        response = _request_with_mode(api_client, _method, path, payload_mode, payload, tiny_png, token="")
        result = api_client.assert_business_code(response, 400)
        assert result["message"]

    @pytest.mark.parametrize("_label,_method,_wrong_method,path,payload_mode,payload_factory", MUTATION_SPECS)
    def test_mutation_endpoints_reject_invalid_token(
            self,
            _label: str,
            _method: str,
            _wrong_method: str,
            path: str,
            payload_mode: str,
            payload_factory: PayloadFactory,
            api_client: ApiClient,
            seeded_data: dict[str, Any],
            aux_entities: dict[str, Any],
            tiny_png: Path,
    ) -> None:
        payload = payload_factory(api_client, seeded_data, aux_entities)
        response = _request_with_mode(
            api_client,
            _method,
            path,
            payload_mode,
            payload,
            tiny_png,
            token="definitely-invalid-token",
        )
        result = api_client.assert_business_code(response, 404)
        assert result["message"]

    @pytest.mark.parametrize("_label,_method,wrong_method,path,payload_mode,payload_factory", MUTATION_SPECS)
    def test_mutation_endpoints_reject_wrong_http_method(
            self,
            _label: str,
            _method: str,
            wrong_method: str,
            path: str,
            payload_mode: str,
            payload_factory: PayloadFactory,
            api_client: ApiClient,
            seeded_data: dict[str, Any],
            aux_entities: dict[str, Any],
            tiny_png: Path,
    ) -> None:
        payload = payload_factory(api_client, seeded_data, aux_entities)
        response = _request_with_mode(api_client, wrong_method, path, payload_mode, payload, tiny_png, token=None)
        result = api_client.assert_business_code(response, 405)
        assert result["message"]
