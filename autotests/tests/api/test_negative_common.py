from __future__ import annotations

from collections.abc import Callable
from typing import Any

import pytest

from autotests.helpers.api_client import ApiClient


PayloadFactory = Callable[[dict[str, Any]], dict[str, Any]]


def _no_extra(_: dict[str, Any]) -> dict[str, Any]:
    return {}


def _history_vendor(data: dict[str, Any]) -> dict[str, Any]:
    return {"vendor_id": data["vendor"]["id"]}


def _history_object(data: dict[str, Any]) -> dict[str, Any]:
    return {
        "vendor_id": data["vendor"]["id"],
        "object_id": data["object"]["id"],
    }


def _history_material(data: dict[str, Any]) -> dict[str, Any]:
    return {
        "vendor_id": data["vendor"]["id"],
        "object_id": data["object"]["id"],
        "material_id": data["material"]["id"],
        "limit": 5,
        "offset": 0,
    }


def _payments_get_all(_: dict[str, Any]) -> dict[str, Any]:
    return {"limit": 0, "offset": "{}"}


def _payments_by_vendor(data: dict[str, Any]) -> dict[str, Any]:
    return {"vendor_id": data["vendor"]["id"]}


def _object_search(data: dict[str, Any]) -> dict[str, Any]:
    return {"query": data["object"]["name"][:6]}


def _material_search(data: dict[str, Any]) -> dict[str, Any]:
    return {"query": data["material"]["name"][:6]}


GET_ENDPOINT_SPECS: list[tuple[str, str, PayloadFactory]] = [
    ("users.get-all", "/api/users/get-all", lambda _: {"limit": 0, "offset": 0}),
    ("users.get-info", "/api/users/get-info", _no_extra),
    ("time.get-timezones", "/api/time/get-timezones", _no_extra),
    ("time.get-timezone", "/api/time/get-timezone", lambda _: {"timezone_name": "Europe/Moscow"}),
    ("settings.get-debt", "/api/settings/get-debt", _no_extra),
    ("units.get-all", "/api/units-measurement-volume/get-all", _no_extra),
    ("legal-entity-types.get-all", "/api/legal-entities-types/get-all", _no_extra),
    ("icons.get-all", "/api/icons/get-all", lambda _: {"limit": 48, "offset": 0}),
    ("vendors.get-all", "/api/vendors/get-all", lambda _: {"limit": 0, "offset": 0, "archive": 0}),
    ("objects.get-all", "/api/objects/get-all", lambda _: {"limit": 0, "offset": 0, "archive": 0}),
    ("objects.search", "/api/objects/search", _object_search),
    ("material-types.get-all", "/api/material-types/get-all", lambda _: {"limit": 0, "offset": 0}),
    ("materials.get-all", "/api/materials/get-all", lambda _: {"limit": 0, "offset": 0}),
    ("materials.search", "/api/materials/search", _material_search),
    ("legal-entities.get-all", "/api/legal-entities/get-all", lambda _: {"limit": 0, "offset": 0, "archive": 0}),
    (
        "dashboard.get-data",
        "/api/dashboard/get-data",
        lambda _: {"period": 30, "date_from": 0, "date_to": 0},
    ),
    ("report.get-base", "/api/report/get-base", _no_extra),
    ("report.get-filters", "/api/report/get-filters", _no_extra),
    ("history.get-objects-by-vendor", "/api/history-operation/get-objects-by-vendor", _history_vendor),
    ("history.get-material-by-object", "/api/history-operation/get-material-by-object", _history_object),
    (
        "history.get-all-operations-by-material",
        "/api/history-operation/get-all-operations-by-material",
        _history_material,
    ),
    ("payments.get-all", "/api/payments/get-all", _payments_get_all),
    ("payments.get-all-payments-by-vendor", "/api/payments/get-all-payments-by-vendor", _payments_by_vendor),
]


WRONG_METHOD_SPECS: list[tuple[str, str, str, str, PayloadFactory]] = [
    ("auth.login", "GET", "/api/auth/login", "data", lambda _: {"login": "admin", "password": "admin"}),
    ("auth.logout", "GET", "/api/auth/logout", "data", _no_extra),
    ("users.get-info", "POST", "/api/users/get-info", "data", _no_extra),
    ("users.get-all", "POST", "/api/users/get-all", "data", lambda _: {"limit": 0, "offset": 0}),
    ("time.get-timezones", "POST", "/api/time/get-timezones", "data", _no_extra),
    ("time.get-timezone", "POST", "/api/time/get-timezone", "data", lambda _: {"timezone_name": "Europe/Moscow"}),
    ("settings.get-debt", "POST", "/api/settings/get-debt", "data", _no_extra),
    ("units.get-all", "POST", "/api/units-measurement-volume/get-all", "data", _no_extra),
    ("legal-entity-types.get-all", "POST", "/api/legal-entities-types/get-all", "data", _no_extra),
    ("icons.get-all", "POST", "/api/icons/get-all", "data", lambda _: {"limit": 48, "offset": 0}),
    ("vendors.get-all", "POST", "/api/vendors/get-all", "data", lambda _: {"limit": 0, "offset": 0, "archive": 0}),
    ("vendors.create", "GET", "/api/vendors/create", "params", lambda _: {"name": "bad", "icon_id": 1}),
    ("objects.get-all", "POST", "/api/objects/get-all", "data", lambda _: {"limit": 0, "offset": 0, "archive": 0}),
    ("objects.create", "GET", "/api/objects/create", "params", lambda _: {"name": "bad"}),
    ("objects.search", "POST", "/api/objects/search", "data", lambda _: {"query": "obj"}),
    ("material-types.get-all", "POST", "/api/material-types/get-all", "data", lambda _: {"limit": 0, "offset": 0}),
    (
        "material-types.create",
        "GET",
        "/api/material-types/create",
        "params",
        lambda data: {"name": "bad", "units_measurement_volume_id": data["units"][0]["id"]},
    ),
    ("materials.get-all", "POST", "/api/materials/get-all", "data", lambda _: {"limit": 0, "offset": 0}),
    (
        "materials.create",
        "GET",
        "/api/materials/create",
        "params",
        lambda data: {"name": "bad", "type_id": data["material_type"]["id"]},
    ),
    ("materials.search", "POST", "/api/materials/search", "data", lambda _: {"query": "mat"}),
    ("legal-entities.get-all", "POST", "/api/legal-entities/get-all", "data", lambda _: {"limit": 0, "offset": 0, "archive": 0}),
    (
        "legal-entities.create",
        "GET",
        "/api/legal-entities/create",
        "params",
        lambda data: {"name": "bad", "legal_entities_type_id": data["legal_entity_types"][0]["id"]},
    ),
    (
        "history.get-objects-by-vendor",
        "POST",
        "/api/history-operation/get-objects-by-vendor",
        "data",
        lambda data: {"vendor_id": data["vendor"]["id"]},
    ),
    (
        "history.get-material-by-object",
        "POST",
        "/api/history-operation/get-material-by-object",
        "data",
        lambda data: {"vendor_id": data["vendor"]["id"], "object_id": data["object"]["id"]},
    ),
    (
        "history.get-all-operations-by-material",
        "POST",
        "/api/history-operation/get-all-operations-by-material",
        "data",
        lambda data: {
            "vendor_id": data["vendor"]["id"],
            "object_id": data["object"]["id"],
            "material_id": data["material"]["id"],
            "limit": 5,
            "offset": 0,
        },
    ),
    (
        "history.create",
        "GET",
        "/api/history-operation/create",
        "params",
        lambda data: {
            "vendor_id": data["vendor"]["id"],
            "material_id": data["material"]["id"],
            "legal_entity_id": data["legal_entity"]["id"],
            "object_id": data["object"]["id"],
            "volume": "1.00",
            "price": "1.00",
            "total": "1.00",
            "comment": "wrong method",
            "created_at": 1,
            "is_debt": 0,
            "confirmed_data": 1,
        },
    ),
    ("payments.get-all", "POST", "/api/payments/get-all", "data", lambda _: {"limit": 0, "offset": "{}"}),
    (
        "payments.get-all-payments-by-vendor",
        "POST",
        "/api/payments/get-all-payments-by-vendor",
        "data",
        lambda data: {"vendor_id": data["vendor"]["id"]},
    ),
    (
        "payments.create",
        "GET",
        "/api/payments/create",
        "params",
        lambda data: {
            "vendor_id": data["vendor"]["id"],
            "legal_entity_id": data["legal_entity"]["id"],
            "material_type_id": data["material_type"]["id"],
            "amount": "1.00",
            "operation_type": "buy",
        },
    ),
    ("dashboard.get-data", "POST", "/api/dashboard/get-data", "data", lambda _: {"period": 30}),
    ("report.get-base", "POST", "/api/report/get-base", "data", _no_extra),
    ("report.get-filters", "POST", "/api/report/get-filters", "data", _no_extra),
    (
        "report.create-filter",
        "GET",
        "/api/report/create-filter",
        "params",
        lambda _: {"name": "bad", "filters": "[]"},
    ),
    ("files.upload-avatar", "GET", "/api/files/upload-avatar", "params", _no_extra),
]


@pytest.mark.api
class TestNegativeCommon:
    @pytest.mark.parametrize("_label,path,extra_factory", GET_ENDPOINT_SPECS)
    def test_get_endpoints_require_token(
            self,
            _label: str,
            path: str,
            extra_factory: PayloadFactory,
            api_client: ApiClient,
            seeded_data: dict[str, Any],
    ) -> None:
        params = {"user_id": api_client.auth.user_id, **extra_factory(seeded_data)}
        response = api_client.request("GET", path, params=params)
        if _label in {"objects.search", "materials.search"}:
            assert response.status_code == 400
            return
        payload = api_client.assert_business_code(response, 400)
        assert payload["message"]

    @pytest.mark.parametrize("_label,path,extra_factory", GET_ENDPOINT_SPECS)
    def test_get_endpoints_reject_invalid_token(
            self,
            _label: str,
            path: str,
            extra_factory: PayloadFactory,
            api_client: ApiClient,
            seeded_data: dict[str, Any],
    ) -> None:
        params = api_client.auth_query(extra_factory(seeded_data), token="definitely-invalid-token")
        response = api_client.request("GET", path, params=params)
        payload = api_client.assert_business_code(response, 404)
        assert payload["message"]

    @pytest.mark.parametrize("_label,method,path,payload_mode,extra_factory", WRONG_METHOD_SPECS)
    def test_endpoints_reject_wrong_http_method(
            self,
            _label: str,
            method: str,
            path: str,
            payload_mode: str,
            extra_factory: PayloadFactory,
            api_client: ApiClient,
            seeded_data: dict[str, Any],
    ) -> None:
        extra = extra_factory(seeded_data)
        kwargs: dict[str, Any] = {}
        if payload_mode == "params":
            kwargs["params"] = api_client.auth_query(extra)
        else:
            kwargs["data"] = api_client.auth_form(extra)
        response = api_client.request(method, path, **kwargs)
        if _label in {"objects.search", "materials.search"}:
            assert response.status_code == 400
            return
        payload = api_client.assert_business_code(response, 405)
        assert payload["message"]
