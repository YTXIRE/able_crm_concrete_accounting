from __future__ import annotations

import pytest

from autotests.helpers.api_client import ApiClient


@pytest.mark.api
def test_material_type_can_change_unit_without_duplicate_name_error(api_client: ApiClient) -> None:
    units = api_client.get_units()
    assert len(units) >= 2, "Regression test requires at least two measurement units"

    material_type = api_client.create_material_type(
        api_client.unique_name("regression_material_type"),
        units[0]["id"],
    )

    payload = api_client.update_material_type(
        material_type_id=material_type["id"],
        name=material_type["name"],
        units_measurement_volume_id=units[1]["id"],
    )

    assert payload["data"]["id"] == material_type["id"]


@pytest.mark.api
@pytest.mark.parametrize("invalid_amount", ["abc", "#$%", "12abc", " "])
def test_payment_creation_rejects_non_numeric_amount(
    api_client: ApiClient, seeded_data: dict, invalid_amount: str
) -> None:
    payload = api_client.create_payment(
        vendor_id=seeded_data["vendor"]["id"],
        legal_entity_id=seeded_data["legal_entity"]["id"],
        material_type_id=seeded_data["material_type"]["id"],
        amount=invalid_amount,
        expected_code=400,
    )

    assert payload["message"]


@pytest.mark.api
def test_legal_entity_name_accepts_thirty_five_characters(api_client: ApiClient) -> None:
    legal_entity_type_id = api_client.get_legal_entity_types()[0]["id"]
    name = "A" * 35
    unique_name = f"{name[:20]}_{api_client.unique_name('le')[-14:]}"
    # Keep deterministic 35-char boundary for the business name itself.
    unique_name = unique_name[:35]

    response = api_client.session.post(
        f"{api_client.settings.api_url}/api/legal-entities/create",
        data=api_client._auth_form(
            {
                "name": unique_name,
                "legal_entities_type_id": legal_entity_type_id,
            }
        ),
        timeout=api_client.settings.timeout_seconds,
    )

    payload = api_client._assert_code(response, 201)
    assert payload["message"]
