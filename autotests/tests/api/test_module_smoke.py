from __future__ import annotations

import pytest

from autotests.helpers.api_client import ApiClient
from autotests.helpers.settings import TestSettings

@pytest.mark.api
class TestModuleSmoke:
    def test_auth_login_and_logout_smoke(self, settings: TestSettings) -> None:
        client = ApiClient(settings)
        auth = client.login()
        assert auth.token
        assert auth.user_id > 0
        logout_payload = client.logout()
        assert logout_payload["message"]

    def test_reference_modules_smoke(self, api_client: ApiClient) -> None:
        user_info = api_client.get_user_info()
        assert user_info["login"]

        users = api_client.get_users()
        assert "users" in users
        assert users["count"] >= 1

        timezones = api_client.get_timezones()
        assert isinstance(timezones, list)
        assert len(timezones) >= 1

        units = api_client.get_units()
        assert isinstance(units, list)
        assert len(units) >= 1
        assert {"id", "short_name", "full_name"}.issubset(units[0])

        legal_entity_types = api_client.get_legal_entity_types()
        assert isinstance(legal_entity_types, list)
        assert len(legal_entity_types) >= 1

        icons = api_client.get_icons()
        assert len(icons["icons"]) >= 1
        assert int(icons["count"]) >= len(icons["icons"])

        debt_flag = api_client.get_settings_debt()
        assert isinstance(debt_flag, (bool, int))

    def test_directory_modules_smoke(self, api_client: ApiClient, seeded_data: dict) -> None:
        vendors = api_client.get_vendors()
        assert any(item["id"] == seeded_data["vendor"]["id"] for item in vendors["vendors"])

        objects = api_client.get_objects()
        assert any(item["id"] == seeded_data["object"]["id"] for item in objects["objects"])

        material_types = api_client.get_material_types()
        assert any(item["id"] == seeded_data["material_type"]["id"] for item in material_types["types"])
        material_type = next(item for item in material_types["types"] if item["id"] == seeded_data["material_type"]["id"])
        assert {"id", "short_name", "full_name"}.issubset(material_type["units_measurement_volume"])

        materials = api_client.get_materials()
        assert any(item["id"] == seeded_data["material"]["id"] for item in materials["materials"])

        legal_entities = api_client.get_legal_entities()
        assert any(item["id"] == seeded_data["legal_entity"]["id"] for item in legal_entities["legal_entities"])

    def test_operational_modules_smoke(self, api_client: ApiClient, seeded_data: dict) -> None:
        history_objects = api_client.get_history_objects_by_vendor(seeded_data["vendor"]["id"])
        assert history_objects

        history_materials = api_client.get_history_materials_by_object(
            seeded_data["vendor"]["id"], seeded_data["object"]["id"]
        )
        assert history_materials

        history_operations = api_client.get_history_operations(
            seeded_data["vendor"]["id"],
            seeded_data["object"]["id"],
            seeded_data["material"]["id"],
        )
        assert history_operations
        assert {"short_name", "full_name"}.issubset(history_operations["operations"][0]["material"]["units"])

        payments = api_client.get_payments()
        assert payments

        payments_by_vendor = api_client.get_payments_by_vendor(seeded_data["vendor"]["id"])
        assert payments_by_vendor

        dashboard = api_client.get_dashboard()
        assert "debts" in dashboard
        assert "operations_by_months" in dashboard

        base_report = api_client.get_base_report()
        assert isinstance(base_report, dict)

        filters = api_client.get_report_filters()
        assert isinstance(filters, list)

    def test_files_module_upload_avatar_smoke(self, api_client: ApiClient, tiny_png) -> None:
        payload = api_client.upload_avatar(tiny_png)
        assert payload["data"]["avatar"].startswith("/files/")
