from __future__ import annotations

import pytest

from autotests.helpers.api_client import ApiClient


def _vendor_filter(vendor_id: int) -> list[dict[str, object]]:
    return [{"field": "vendor", "operation": "equal", "value": vendor_id, "unity": "and"}]


@pytest.mark.api
def test_users_mutation_end_to_end(api_client: ApiClient) -> None:
    login = api_client.unique_name("user_flow")
    email = f"{login}@example.com"
    created = api_client.create_user(login, email)
    user_id = created["data"]["id"]

    updated_login = api_client.unique_name("user_flow_updated")
    updated_email = f"{updated_login}@example.com"
    updated = api_client.update_user_info(user_id, updated_login, updated_email)
    assert updated["data"]["id"] == user_id

    changed = api_client.change_user_password(user_id, "secret2")
    assert changed["message"]

    deleted = api_client.delete_user(user_id)
    assert deleted["message"]


@pytest.mark.api
def test_time_and_settings_success(api_client: ApiClient) -> None:
    timezones = api_client.get_timezones()
    set_timezone_payload = api_client.set_timezone(timezones[0]["id"])
    assert set_timezone_payload["message"]

    current_debt = bool(api_client.get_settings_debt())
    target = 0 if current_debt else 1
    changed = api_client.request(
        "POST",
        "/api/settings/set-debt",
        data=api_client.auth_form({"active": target}),
    )
    changed_payload = api_client.assert_business_code(changed, 200)
    assert changed_payload["message"]

    restore = api_client.request(
        "POST",
        "/api/settings/set-debt",
        data=api_client.auth_form({"active": 1 if current_debt else 0}),
    )
    restore_payload = api_client.assert_business_code(restore, 200)
    assert restore_payload["message"]


@pytest.mark.api
def test_vendor_mutations_success(api_client: ApiClient) -> None:
    vendor = api_client.create_vendor(api_client.unique_name("vendor_success"))
    updated = api_client.update_vendor(vendor["id"], api_client.unique_name("vendor_success_updated"), 1)
    assert updated["data"]["id"] == vendor["id"]

    deleted = api_client.delete_vendor(vendor["id"])
    assert deleted["message"]

    restored = api_client.restore_vendor(vendor["id"])
    assert restored["message"]


@pytest.mark.api
def test_object_mutations_and_search_success(api_client: ApiClient) -> None:
    obj = api_client.create_object(api_client.unique_name("object_success"))
    search = api_client.search_objects(obj["name"][:8])
    assert search["data"]["count"] >= 1

    updated = api_client.update_object(obj["id"], api_client.unique_name("object_success_updated"))
    assert updated["data"]["id"] == obj["id"]

    deleted = api_client.delete_object(obj["id"])
    assert deleted["message"]

    restored = api_client.restore_object(obj["id"])
    assert restored["message"]


@pytest.mark.api
def test_material_type_and_material_mutations_and_search_success(api_client: ApiClient, seeded_data: dict) -> None:
    units = seeded_data["units"]
    material_type = api_client.create_material_type(api_client.unique_name("material_type_success"), units[0]["id"])
    updated_type = api_client.update_material_type(material_type["id"], material_type["name"], units[-1]["id"])
    assert updated_type["data"]["id"] == material_type["id"]

    material = api_client.create_material(api_client.unique_name("material_success"), material_type["id"])
    search = api_client.search_materials(material["name"][:8])
    assert search["data"]["count"] >= 1

    updated_material = api_client.update_material(
        material["id"], api_client.unique_name("material_success_updated"), material_type["id"]
    )
    assert updated_material["data"]["id"] == material["id"]


@pytest.mark.api
def test_legal_entity_mutations_success(api_client: ApiClient, seeded_data: dict) -> None:
    legal_entity = api_client.create_legal_entity(
        api_client.unique_name("legal_success")[:18], seeded_data["legal_entity_types"][0]["id"]
    )
    updated = api_client.update_legal_entity(
        legal_entity["id"], api_client.unique_name("legal_success_updated")[:18], seeded_data["legal_entity_types"][0]["id"]
    )
    assert updated["data"]["id"] == legal_entity["id"]

    deleted = api_client.delete_legal_entity(legal_entity["id"])
    assert deleted["message"]

    restored = api_client.restore_legal_entity(legal_entity["id"])
    assert restored["message"]


@pytest.mark.api
def test_history_operation_mutations_success(api_client: ApiClient, seeded_data: dict) -> None:
    comment = api_client.unique_name("history_success")
    api_client.create_history_operation(
        vendor_id=seeded_data["vendor"]["id"],
        material_id=seeded_data["material"]["id"],
        legal_entity_id=seeded_data["legal_entity"]["id"],
        object_id=seeded_data["object"]["id"],
        comment=comment,
    )
    operation = api_client.find_history_operation(
        seeded_data["vendor"]["id"],
        seeded_data["object"]["id"],
        seeded_data["material"]["id"],
        comment,
    )

    updated = api_client.update_history_operation(
        operation_id=operation["id"],
        vendor_id=seeded_data["vendor"]["id"],
        material_id=seeded_data["material"]["id"],
        legal_entity_id=seeded_data["legal_entity"]["id"],
        object_id=seeded_data["object"]["id"],
    )
    assert updated["message"]

    deleted = api_client.delete_history_operation(operation["id"])
    assert deleted["message"]


@pytest.mark.api
def test_payment_mutations_success(api_client: ApiClient, seeded_data: dict) -> None:
    amount = 3456.78
    api_client.create_payment(
        vendor_id=seeded_data["vendor"]["id"],
        legal_entity_id=seeded_data["legal_entity"]["id"],
        material_type_id=seeded_data["material_type"]["id"],
        amount=f"{amount:.2f}",
    )
    payment = api_client.find_payment(
        seeded_data["vendor"]["id"],
        seeded_data["legal_entity"]["id"],
        seeded_data["material_type"]["id"],
        amount,
    )

    updated = api_client.update_payment(
        payment_id=payment["id"],
        vendor_id=seeded_data["vendor"]["id"],
        legal_entity_id=seeded_data["legal_entity"]["id"],
        material_type_id=seeded_data["material_type"]["id"],
        amount="4567.89",
    )
    assert updated["data"]["id"] == payment["id"]

    deleted = api_client.delete_payment(payment["id"])
    assert deleted["message"]


@pytest.mark.api
def test_report_advanced_and_filter_mutations_success(api_client: ApiClient, seeded_data: dict) -> None:
    filters = _vendor_filter(seeded_data["vendor"]["id"])
    advanced = api_client.get_advanced_report(filters)
    assert isinstance(advanced["data"], list)

    filter_name = api_client.unique_name("report_filter_success")
    created = api_client.save_report_filter(filter_name, filters)
    assert created["message"]
    saved_filter = api_client.find_filter(filter_name)

    updated = api_client.update_report_filter(saved_filter["id"], api_client.unique_name("report_filter_updated"), filters)
    assert updated["message"]

    deleted = api_client.delete_report_filter(saved_filter["id"])
    assert deleted["message"]
