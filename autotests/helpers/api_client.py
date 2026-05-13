from __future__ import annotations

import time
import uuid
from dataclasses import dataclass
from pathlib import Path
from typing import Any

import requests

from autotests.helpers.settings import TestSettings


@dataclass
class AuthBundle:
    token: str
    user_id: int
    is_demo: int | None
    user_data: dict[str, Any]


class ApiClient:
    def __init__(self, settings: TestSettings):
        self.settings = settings
        self.session = requests.Session()
        self.auth: AuthBundle | None = None

    def login(self) -> AuthBundle:
        response = self.session.post(
            self._url("/api/auth/login"),
            data={
                "login": self.settings.admin_login,
                "password": self.settings.admin_password,
            },
            timeout=self.settings.timeout_seconds,
        )
        payload = self._assert_code(response, 200)
        data = payload["data"]
        user_info = self.get_user_info(data["token"], data["id"])
        self.auth = AuthBundle(
            token=data["token"],
            user_id=int(data["id"]),
            is_demo=data.get("is_demo"),
            user_data=user_info,
        )
        return self.auth

    def logout(self) -> dict[str, Any]:
        self._require_auth()
        response = self.session.post(
            self._url("/api/auth/logout"),
            data={
                "token": self.auth.token,
                "user_id": self.auth.user_id,
            },
            timeout=self.settings.timeout_seconds,
        )
        return self._assert_code(response, 200)

    def get_user_info(self, token: str | None = None, user_id: int | None = None) -> dict[str, Any]:
        token = token or self._require_auth().token
        user_id = user_id or self._require_auth().user_id
        payload = self.get(
            "/api/users/get-info",
            params={"token": token, "user_id": user_id},
        )
        return payload["data"]

    def get_users(self, limit: int = 0, offset: int = 0) -> dict[str, Any]:
        payload = self.get(
            "/api/users/get-all",
            params=self._auth_query({"limit": limit, "offset": offset}),
        )
        return payload["data"]

    def get_timezones(self) -> list[dict[str, Any]]:
        payload = self.get("/api/time/get-timezones", params=self._auth_query())
        return payload["data"]

    def get_settings_debt(self) -> Any:
        payload = self.get("/api/settings/get-debt", params=self._auth_query())
        return payload["data"]["debt"]

    def get_units(self) -> list[dict[str, Any]]:
        payload = self.get("/api/units-measurement-volume/get-all", params=self._auth_query())
        return payload["data"]

    def get_legal_entity_types(self) -> list[dict[str, Any]]:
        payload = self.get("/api/legal-entities-types/get-all", params=self._auth_query())
        return payload["data"]

    def get_icons(self, limit: int = 48, offset: int = 0) -> dict[str, Any]:
        payload = self.get(
            "/api/icons/get-all",
            params=self._auth_query({"limit": limit, "offset": offset}),
        )
        return payload["data"]

    def get_vendors(self, archive: int = 0, limit: int = 0, offset: int = 0) -> dict[str, Any]:
        payload = self.get(
            "/api/vendors/get-all",
            params=self._auth_query({"archive": archive, "limit": limit, "offset": offset}),
        )
        return payload["data"]

    def get_objects(self, archive: int = 0, limit: int = 0, offset: int = 0) -> dict[str, Any]:
        payload = self.get(
            "/api/objects/get-all",
            params=self._auth_query({"archive": archive, "limit": limit, "offset": offset}),
        )
        return payload["data"]

    def get_material_types(self, limit: int = 0, offset: int = 0) -> dict[str, Any]:
        payload = self.get(
            "/api/material-types/get-all",
            params=self._auth_query({"limit": limit, "offset": offset}),
        )
        return payload["data"]

    def get_materials(self, limit: int = 0, offset: int = 0) -> dict[str, Any]:
        payload = self.get(
            "/api/materials/get-all",
            params=self._auth_query({"limit": limit, "offset": offset}),
        )
        return payload["data"]

    def get_legal_entities(self, archive: int = 0, limit: int = 0, offset: int = 0) -> dict[str, Any]:
        payload = self.get(
            "/api/legal-entities/get-all",
            params=self._auth_query({"archive": archive, "limit": limit, "offset": offset}),
        )
        return payload["data"]

    def get_history_objects_by_vendor(self, vendor_id: int) -> Any:
        payload = self.get(
            "/api/history-operation/get-objects-by-vendor",
            params=self._auth_query({"vendor_id": vendor_id}),
        )
        return payload["data"]

    def get_history_materials_by_object(self, vendor_id: int, object_id: int) -> Any:
        payload = self.get(
            "/api/history-operation/get-material-by-object",
            params=self._auth_query({"vendor_id": vendor_id, "object_id": object_id}),
        )
        return payload["data"]

    def get_history_operations(self, vendor_id: int, object_id: int, material_id: int, limit: int = 5, offset: int = 0) -> Any:
        payload = self.get(
            "/api/history-operation/get-all-operations-by-material",
            params=self._auth_query(
                {
                    "vendor_id": vendor_id,
                    "object_id": object_id,
                    "material_id": material_id,
                    "limit": limit,
                    "offset": offset,
                }
            ),
        )
        return payload["data"]

    def get_payments(self) -> Any:
        payload = self.get(
            "/api/payments/get-all",
            params=self._auth_query({"limit": 0, "offset": "{}"}),
        )
        return payload["data"]

    def get_payments_by_vendor(self, vendor_id: int) -> Any:
        payload = self.get(
            "/api/payments/get-all-payments-by-vendor",
            params=self._auth_query({"vendor_id": vendor_id}),
        )
        return payload["data"]

    def get_dashboard(self, period: int | str = 30, date_from: int = 0, date_to: int = 0) -> Any:
        payload = self.get(
            "/api/dashboard/get-data",
            params=self._auth_query(
                {
                    "period": period,
                    "date_from": date_from,
                    "date_to": date_to,
                }
            ),
        )
        return payload["data"]

    def get_base_report(self) -> Any:
        payload = self.get("/api/report/get-base", params=self._auth_query())
        return payload["data"]

    def get_report_filters(self) -> Any:
        payload = self.get("/api/report/get-filters", params=self._auth_query())
        return payload["data"]

    def get_advanced_report(self, filters: list[dict[str, Any]], expected_code: int = 200) -> dict[str, Any]:
        response = self.session.post(
            self._url("/api/report/get-advanced"),
            data=self._auth_form({"filters": self._json_string(filters)}),
            timeout=self.settings.timeout_seconds,
        )
        return self._assert_code(response, expected_code)

    def save_report_filter(self, name: str, filters: list[dict[str, Any]]) -> dict[str, Any]:
        response = self.session.post(
            self._url("/api/report/create-filter"),
            data=self._auth_form({"name": name, "filters": self._json_string(filters)}),
            timeout=self.settings.timeout_seconds,
        )
        return self._assert_code(response, 201)

    def update_report_filter(
        self, filter_id: int, name: str, filters: list[dict[str, Any]], expected_code: int = 200
    ) -> dict[str, Any]:
        response = self.session.put(
            self._url("/api/report/update-filter"),
            json=self._auth_json({"id": filter_id, "name": name, "filters": self._json_string(filters)}),
            timeout=self.settings.timeout_seconds,
        )
        return self._assert_code(response, expected_code)

    def delete_report_filter(self, filter_id: int, expected_code: int = 200) -> dict[str, Any]:
        response = self.session.delete(
            self._url("/api/report/delete-filter"),
            json=self._auth_json({"id": filter_id}),
            timeout=self.settings.timeout_seconds,
        )
        return self._assert_code(response, expected_code)

    def upload_avatar(self, image_path: Path) -> dict[str, Any]:
        auth = self._require_auth()
        with image_path.open("rb") as image_file:
            response = self.session.post(
                self._url("/api/files/upload-avatar"),
                data={
                    "id": auth.user_id,
                    "token": auth.token,
                    "user_id": auth.user_id,
                },
                files={"avatar": (image_path.name, image_file, "image/png")},
                timeout=self.settings.timeout_seconds,
            )
            print(response.text)
            print(auth)
        return self._assert_code(response, 200)

    def create_user(
        self,
        login: str,
        email: str,
        password: str = "secret1",
        is_demo: int = 1,
        expected_code: int = 201,
    ) -> dict[str, Any]:
        response = self.session.post(
            self._url("/api/users/create"),
            data=self._auth_form(
                {
                    "login": login,
                    "email": email,
                    "password": password,
                    "is_demo": is_demo,
                }
            ),
            timeout=self.settings.timeout_seconds,
        )
        return self._assert_code(response, expected_code)

    def update_user_info(
        self,
        target_user_id: int,
        login: str,
        email: str,
        is_demo: int = 1,
        expected_code: int = 200,
    ) -> dict[str, Any]:
        response = self.session.put(
            self._url("/api/users/update-info"),
            json=self._auth_json(
                {
                    "id": target_user_id,
                    "login": login,
                    "email": email,
                    "is_demo": is_demo,
                }
            ),
            timeout=self.settings.timeout_seconds,
        )
        return self._assert_code(response, expected_code)

    def change_user_password(self, target_user_id: int, password: str, expected_code: int = 200) -> dict[str, Any]:
        response = self.session.put(
            self._url("/api/users/change-password"),
            json=self._auth_json({"id": target_user_id, "password": password}),
            timeout=self.settings.timeout_seconds,
        )
        return self._assert_code(response, expected_code)

    def delete_user(self, target_user_id: int, expected_code: int = 200) -> dict[str, Any]:
        response = self.session.delete(
            self._url("/api/users/delete"),
            json=self._auth_json({"id": target_user_id}),
            timeout=self.settings.timeout_seconds,
        )
        return self._assert_code(response, expected_code)

    def set_timezone(self, timezone_id: int, expected_code: int = 200) -> dict[str, Any]:
        response = self.session.post(
            self._url("/api/time/set-timezone"),
            data=self._auth_form({"timezone_id": timezone_id}),
            timeout=self.settings.timeout_seconds,
        )
        return self._assert_code(response, expected_code)

    def create_vendor(self, name: str, icon_id: int = 1) -> dict[str, Any]:
        response = self.session.post(
            self._url("/api/vendors/create"),
            data=self._auth_form({"name": name, "icon_id": icon_id}),
            timeout=self.settings.timeout_seconds,
        )
        self._assert_code(response, 201)
        return self.find_vendor(name)

    def update_vendor(self, vendor_id: int, name: str, icon_id: int, expected_code: int = 200) -> dict[str, Any]:
        response = self.session.put(
            self._url("/api/vendors/update"),
            json=self._auth_json({"id": vendor_id, "name": name, "icon_id": icon_id}),
            timeout=self.settings.timeout_seconds,
        )
        return self._assert_code(response, expected_code)

    def delete_vendor(self, vendor_id: int, expected_code: int = 200) -> dict[str, Any]:
        response = self.session.delete(
            self._url("/api/vendors/delete"),
            json=self._auth_json({"id": vendor_id}),
            timeout=self.settings.timeout_seconds,
        )
        return self._assert_code(response, expected_code)

    def restore_vendor(self, vendor_id: int, expected_code: int = 200) -> dict[str, Any]:
        response = self.session.put(
            self._url("/api/vendors/restore"),
            json=self._auth_json({"id": vendor_id}),
            timeout=self.settings.timeout_seconds,
        )
        return self._assert_code(response, expected_code)

    def create_object(self, name: str) -> dict[str, Any]:
        response = self.session.post(
            self._url("/api/objects/create"),
            data=self._auth_form({"name": name}),
            timeout=self.settings.timeout_seconds,
        )
        self._assert_code(response, 201)
        return self.find_object(name)

    def update_object(self, object_id: int, name: str, expected_code: int = 200) -> dict[str, Any]:
        response = self.session.put(
            self._url("/api/objects/update"),
            json=self._auth_json({"id": object_id, "name": name}),
            timeout=self.settings.timeout_seconds,
        )
        return self._assert_code(response, expected_code)

    def delete_object(self, object_id: int, expected_code: int = 200) -> dict[str, Any]:
        response = self.session.delete(
            self._url("/api/objects/delete"),
            json=self._auth_json({"id": object_id}),
            timeout=self.settings.timeout_seconds,
        )
        return self._assert_code(response, expected_code)

    def restore_object(self, object_id: int, expected_code: int = 200) -> dict[str, Any]:
        response = self.session.put(
            self._url("/api/objects/restore"),
            json=self._auth_json({"id": object_id}),
            timeout=self.settings.timeout_seconds,
        )
        return self._assert_code(response, expected_code)

    def search_objects(self, query: str, expected_code: int = 200) -> dict[str, Any]:
        response = self.session.get(
            self._url("/api/objects/search"),
            params=self._auth_query({"query": query}),
            timeout=self.settings.timeout_seconds,
        )
        return self._assert_code(response, expected_code)

    def create_material_type(self, name: str, units_measurement_volume_id: int) -> dict[str, Any]:
        response = self.session.post(
            self._url("/api/material-types/create"),
            data=self._auth_form(
                {
                    "name": name,
                    "units_measurement_volume_id": units_measurement_volume_id,
                }
            ),
            timeout=self.settings.timeout_seconds,
        )
        self._assert_code(response, 201)
        return self.find_material_type(name)

    def update_material_type(self, material_type_id: int, name: str, units_measurement_volume_id: int) -> dict[str, Any]:
        response = self.session.put(
            self._url("/api/material-types/update"),
            json=self._auth_json(
                {
                    "id": material_type_id,
                    "name": name,
                    "units_measurement_volume_id": units_measurement_volume_id,
                }
            ),
            timeout=self.settings.timeout_seconds,
        )
        return self._assert_code(response, 200)

    def create_material(self, name: str, type_id: int) -> dict[str, Any]:
        response = self.session.post(
            self._url("/api/materials/create"),
            data=self._auth_form({"name": name, "type_id": type_id}),
            timeout=self.settings.timeout_seconds,
        )
        self._assert_code(response, 201)
        return self.find_material(name)

    def update_material(self, material_id: int, name: str, type_id: int, expected_code: int = 200) -> dict[str, Any]:
        response = self.session.put(
            self._url("/api/materials/update"),
            json=self._auth_json({"id": material_id, "name": name, "type_id": type_id}),
            timeout=self.settings.timeout_seconds,
        )
        return self._assert_code(response, expected_code)

    def search_materials(self, query: str, expected_code: int = 200) -> dict[str, Any]:
        response = self.session.get(
            self._url("/api/materials/search"),
            params=self._auth_query({"query": query}),
            timeout=self.settings.timeout_seconds,
        )
        return self._assert_code(response, expected_code)

    def create_legal_entity(self, name: str, legal_entities_type_id: int) -> dict[str, Any]:
        name = name[:20]
        response = self.session.post(
            self._url("/api/legal-entities/create"),
            data=self._auth_form(
                {
                    "name": name,
                    "legal_entities_type_id": legal_entities_type_id,
                }
            ),
            timeout=self.settings.timeout_seconds,
        )
        self._assert_code(response, 201)
        return self.find_legal_entity(name)

    def update_legal_entity(
        self, legal_entity_id: int, name: str, legal_entities_type_id: int, expected_code: int = 200
    ) -> dict[str, Any]:
        response = self.session.put(
            self._url("/api/legal-entities/update"),
            json=self._auth_json(
                {
                    "id": legal_entity_id,
                    "name": name,
                    "legal_entities_type_id": legal_entities_type_id,
                }
            ),
            timeout=self.settings.timeout_seconds,
        )
        return self._assert_code(response, expected_code)

    def delete_legal_entity(self, legal_entity_id: int, expected_code: int = 200) -> dict[str, Any]:
        response = self.session.delete(
            self._url("/api/legal-entities/delete"),
            json=self._auth_json({"id": legal_entity_id}),
            timeout=self.settings.timeout_seconds,
        )
        return self._assert_code(response, expected_code)

    def restore_legal_entity(self, legal_entity_id: int, expected_code: int = 200) -> dict[str, Any]:
        response = self.session.put(
            self._url("/api/legal-entities/restore"),
            json=self._auth_json({"id": legal_entity_id}),
            timeout=self.settings.timeout_seconds,
        )
        return self._assert_code(response, expected_code)

    def create_history_operation(
        self,
        vendor_id: int,
        material_id: int,
        legal_entity_id: int,
        object_id: int,
        volume: str = "10.00",
        price: str = "100.00",
        total: str = "1000.00",
        comment: str = "autotest history operation",
    ) -> dict[str, Any]:
        response = self.session.post(
            self._url("/api/history-operation/create"),
            data=self._auth_form(
                {
                    "vendor_id": vendor_id,
                    "material_id": material_id,
                    "legal_entity_id": legal_entity_id,
                    "object_id": object_id,
                    "volume": volume,
                    "price": price,
                    "total": total,
                    "comment": comment,
                    "created_at": int(time.time()),
                    "is_debt": 0,
                    "confirmed_data": 1,
                }
            ),
            timeout=self.settings.timeout_seconds,
        )
        return self._assert_code(response, 201)

    def update_history_operation(
        self,
        operation_id: int,
        vendor_id: int,
        material_id: int,
        legal_entity_id: int,
        object_id: int,
        volume: str = "11.00",
        price: str = "101.00",
        total: str = "1111.00",
        comment: str = "updated autotest operation",
        confirmed_data: int = 1,
        expected_code: int = 200,
    ) -> dict[str, Any]:
        response = self.session.post(
            self._url("/api/history-operation/update"),
            data=self._auth_form(
                {
                    "id": operation_id,
                    "vendor_id": vendor_id,
                    "material_id": material_id,
                    "legal_entity_id": legal_entity_id,
                    "object_id": object_id,
                    "volume": volume,
                    "price": price,
                    "total": total,
                    "comment": comment,
                    "created_at": int(time.time()),
                    "confirmed_data": confirmed_data,
                }
            ),
            timeout=self.settings.timeout_seconds,
        )
        return self._assert_code(response, expected_code)

    def delete_history_operation(self, operation_id: int, expected_code: int = 200) -> dict[str, Any]:
        response = self.session.delete(
            self._url("/api/history-operation/delete"),
            json=self._auth_json({"id": operation_id}),
            timeout=self.settings.timeout_seconds,
        )
        return self._assert_code(response, expected_code)

    def create_payment(
        self,
        vendor_id: int,
        legal_entity_id: int,
        material_type_id: int,
        amount: Any,
        operation_type: str = "buy",
        expected_code: int = 201,
    ) -> dict[str, Any]:
        response = self.session.post(
            self._url("/api/payments/create"),
            data=self._auth_form(
                {
                    "vendor_id": vendor_id,
                    "legal_entity_id": legal_entity_id,
                    "material_type_id": material_type_id,
                    "amount": amount,
                    "operation_type": operation_type,
                    "created_at": int(time.time()),
                }
            ),
            timeout=self.settings.timeout_seconds,
        )
        return self._assert_code(response, expected_code)

    def update_payment(
        self,
        payment_id: int,
        vendor_id: int,
        legal_entity_id: int,
        material_type_id: int,
        amount: Any,
        operation_type: str = "buy",
        expected_code: int = 200,
    ) -> dict[str, Any]:
        response = self.session.put(
            self._url("/api/payments/update"),
            json=self._auth_json(
                {
                    "id": payment_id,
                    "vendor_id": vendor_id,
                    "legal_entity_id": legal_entity_id,
                    "material_type_id": material_type_id,
                    "amount": amount,
                    "operation_type": operation_type,
                    "created_at": int(time.time()),
                }
            ),
            timeout=self.settings.timeout_seconds,
        )
        return self._assert_code(response, expected_code)

    def delete_payment(self, payment_id: int, expected_code: int = 200) -> dict[str, Any]:
        response = self.session.delete(
            self._url("/api/payments/delete"),
            json=self._auth_json({"id": payment_id}),
            timeout=self.settings.timeout_seconds,
        )
        return self._assert_code(response, expected_code)

    def unique_name(self, prefix: str) -> str:
        return f"{prefix[:12]}_{uuid.uuid4().hex[:8]}"

    def find_vendor(self, name: str) -> dict[str, Any]:
        return self._find_by_name(self.get_vendors()["vendors"], name)

    def find_object(self, name: str) -> dict[str, Any]:
        return self._find_by_name(self.get_objects()["objects"], name)

    def find_material_type(self, name: str) -> dict[str, Any]:
        return self._find_by_name(self.get_material_types()["types"], name)

    def find_material(self, name: str) -> dict[str, Any]:
        return self._find_by_name(self.get_materials()["materials"], name)

    def find_legal_entity(self, name: str) -> dict[str, Any]:
        return self._find_by_name(self.get_legal_entities()["legal_entities"], name)

    def find_filter(self, name: str) -> dict[str, Any]:
        return self._find_by_name(self.get_report_filters(), name)

    def find_payment(
        self, vendor_id: int, legal_entity_id: int, material_type_id: int, amount: float, operation_type: str = "buy"
    ) -> dict[str, Any]:
        payments = self.get_payments_by_vendor(vendor_id)
        for group in payments.values():
            if not isinstance(group, list):
                continue
            for payment in group:
                if (
                    payment.get("vendor_id") == vendor_id
                    and payment.get("legal_entity_id") == legal_entity_id
                    and payment.get("material_type_id") == material_type_id
                    and float(payment.get("amount")) == float(amount)
                    and payment.get("operation_type") == operation_type
                ):
                    return payment
        raise AssertionError("Payment was not found in API response")

    def find_history_operation(self, vendor_id: int, object_id: int, material_id: int, comment: str) -> dict[str, Any]:
        operations = self.get_history_operations(vendor_id, object_id, material_id, limit=50, offset=0)
        items = operations.get("operations", []) if isinstance(operations, dict) else operations
        for operation in items:
            if operation.get("comment") == comment:
                return operation
        raise AssertionError("History operation was not found in API response")

    def get(self, path: str, params: dict[str, Any], expected_code: int = 200) -> dict[str, Any]:
        response = self.request("GET", path, params=params)
        return self.assert_business_code(response, expected_code)

    def request(
        self,
        method: str,
        path: str,
        *,
        params: dict[str, Any] | None = None,
        data: dict[str, Any] | None = None,
        json: dict[str, Any] | None = None,
        files: dict[str, Any] | None = None,
    ) -> requests.Response:
        return self.session.request(
            method=method.upper(),
            url=self._url(path),
            params=params,
            data=data,
            json=json,
            files=files,
            timeout=self.settings.timeout_seconds,
        )

    def assert_business_code(self, response: requests.Response, expected_code: int) -> dict[str, Any]:
        return self._assert_code(response, expected_code)

    def auth_query(
        self,
        extra: dict[str, Any] | None = None,
        *,
        token: str | None = None,
        user_id: int | None = None,
    ) -> dict[str, Any]:
        return self._auth_query(extra, token=token, user_id=user_id)

    def auth_form(
        self,
        extra: dict[str, Any] | None = None,
        *,
        token: str | None = None,
        user_id: int | None = None,
    ) -> dict[str, Any]:
        return self._auth_form(extra, token=token, user_id=user_id)

    def auth_json(
        self,
        extra: dict[str, Any] | None = None,
        *,
        token: str | None = None,
        user_id: int | None = None,
    ) -> dict[str, Any]:
        return self._auth_json(extra, token=token, user_id=user_id)

    def _require_auth(self) -> AuthBundle:
        if self.auth is None:
            raise RuntimeError("API client is not authenticated")
        return self.auth

    def _url(self, path: str) -> str:
        return f"{self.settings.api_url}{path}"

    def _auth_query(
        self,
        extra: dict[str, Any] | None = None,
        *,
        token: str | None = None,
        user_id: int | None = None,
    ) -> dict[str, Any]:
        auth = self._require_auth()
        payload: dict[str, Any] = {
            "token": token if token is not None else auth.token,
            "user_id": user_id if user_id is not None else auth.user_id,
        }
        if extra:
            payload.update(extra)
        return payload

    def _auth_form(
        self,
        extra: dict[str, Any] | None = None,
        *,
        token: str | None = None,
        user_id: int | None = None,
    ) -> dict[str, Any]:
        return self._auth_query(extra, token=token, user_id=user_id)

    def _auth_json(
        self,
        extra: dict[str, Any] | None = None,
        *,
        token: str | None = None,
        user_id: int | None = None,
    ) -> dict[str, Any]:
        return self._auth_query(extra, token=token, user_id=user_id)

    def _assert_code(self, response: requests.Response, expected_code: int) -> dict[str, Any]:
        payload = response.json()
        actual_code = payload.get("code")
        assert actual_code == expected_code, (
            f"Expected business code {expected_code}, got {actual_code}. "
            f"HTTP={response.status_code}, payload={payload}"
        )
        return payload

    def _find_by_name(self, items: list[dict[str, Any]], name: str) -> dict[str, Any]:
        for item in items:
            if item.get("name") == name:
                return item
        raise AssertionError(f"Entity with name '{name}' not found in: {items}")

    @staticmethod
    def _json_string(value: Any) -> str:
        import json

        return json.dumps(value, ensure_ascii=False)
