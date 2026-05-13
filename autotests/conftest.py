from __future__ import annotations

import base64
import os
from os import remove
from pathlib import Path
import subprocess
import time
from PIL import Image
from typing import Any, Generator

import pytest
import requests
from dotenv import load_dotenv
from playwright.sync_api import Browser, Page, sync_playwright

from autotests.helpers.api_client import ApiClient
from autotests.helpers.settings import TestSettings

load_dotenv()


PROJECT_ROOT = Path(__file__).resolve().parents[1]


def _docker_compose(*args: str) -> subprocess.CompletedProcess[str]:
    return subprocess.run(
        ["docker", "compose", *args],
        cwd=PROJECT_ROOT,
        text=True,
        capture_output=True,
        check=False,
    )


def _ensure_fault_mode_enabled() -> None:
    print("RUN_FAULT_500", os.getenv("RUN_FAULT_500"))
    if os.getenv("RUN_FAULT_500", "0") != "1":
        pytest.skip("Fault-injection 500 tests are disabled. Set RUN_FAULT_500=1 to enable them.")


@pytest.fixture(scope="session")
def settings() -> TestSettings:
    return TestSettings.from_env()


@pytest.fixture(scope="session")
def api_client(settings: TestSettings) -> ApiClient:
    client = ApiClient(settings)
    try:
        client.login()
    except requests.RequestException as exc:
        pytest.skip(f"Autotest environment is unavailable: {exc}")
    return client


@pytest.fixture()
def auth_bundle(api_client: ApiClient) -> dict[str, Any]:
    api_client.login()
    auth = api_client.auth
    assert auth is not None
    return {
        "token": auth.token,
        "user_id": auth.user_id,
        "is_demo": auth.is_demo,
        "user_data": auth.user_data,
    }


@pytest.fixture()
def readonly_auth_bundle(auth_bundle: dict[str, Any]) -> dict[str, Any]:
    data = dict(auth_bundle)
    data["is_demo"] = 1
    return data


@pytest.fixture(scope="session")
def seeded_data(api_client: ApiClient) -> dict[str, Any]:
    units = api_client.get_units()
    legal_entity_types = api_client.get_legal_entity_types()
    assert len(units) >= 1
    assert len(legal_entity_types) >= 1

    vendor = api_client.create_vendor(api_client.unique_name("autotest_vendor"), icon_id=1)
    material_type = api_client.create_material_type(api_client.unique_name("autotest_material_type"), units[0]["id"])
    material = api_client.create_material(api_client.unique_name("autotest_material"), material_type["id"])
    obj = api_client.create_object(api_client.unique_name("autotest_object"))
    legal_entity = api_client.create_legal_entity(api_client.unique_name("autotest_legal_entity"), legal_entity_types[0]["id"])
    api_client.create_history_operation(
        vendor_id=vendor["id"],
        material_id=material["id"],
        legal_entity_id=legal_entity["id"],
        object_id=obj["id"],
    )
    api_client.create_payment(
        vendor_id=vendor["id"],
        legal_entity_id=legal_entity["id"],
        material_type_id=material_type["id"],
        amount="1500.00",
    )

    return {
        "units": units,
        "legal_entity_types": legal_entity_types,
        "vendor": vendor,
        "material_type": material_type,
        "material": material,
        "object": obj,
        "legal_entity": legal_entity,
    }


@pytest.fixture(scope="session")
def aux_entities(api_client: ApiClient, seeded_data: dict[str, Any]) -> dict[str, Any]:
    user_login = api_client.unique_name("autotest_user")
    user_email = f"{user_login}@example.com"
    created_user = api_client.create_user(user_login, user_email)

    filter_name = api_client.unique_name("autotest_filter")
    filter_payload = [
        {
            "field": "vendor",
            "operation": "equal",
            "value": seeded_data["vendor"]["id"],
            "unity": "and",
        }
    ]
    api_client.save_report_filter(filter_name, filter_payload)
    saved_filter = api_client.find_filter(filter_name)

    history_comment = api_client.unique_name("history_comment")
    api_client.create_history_operation(
        vendor_id=seeded_data["vendor"]["id"],
        material_id=seeded_data["material"]["id"],
        legal_entity_id=seeded_data["legal_entity"]["id"],
        object_id=seeded_data["object"]["id"],
        comment=history_comment,
    )
    history_operation = api_client.find_history_operation(
        seeded_data["vendor"]["id"],
        seeded_data["object"]["id"],
        seeded_data["material"]["id"],
        history_comment,
    )

    payment_amount = 2456.78
    api_client.create_payment(
        vendor_id=seeded_data["vendor"]["id"],
        legal_entity_id=seeded_data["legal_entity"]["id"],
        material_type_id=seeded_data["material_type"]["id"],
        amount=f"{payment_amount:.2f}",
    )
    payment = api_client.find_payment(
        vendor_id=seeded_data["vendor"]["id"],
        legal_entity_id=seeded_data["legal_entity"]["id"],
        material_type_id=seeded_data["material_type"]["id"],
        amount=payment_amount,
    )

    return {
        "user": created_user["data"],
        "filter": saved_filter,
        "history_operation": history_operation,
        "payment": payment,
    }


@pytest.fixture(scope="session")
def browser(settings: TestSettings) -> Browser:
    with sync_playwright() as playwright:
        browser = playwright.chromium.launch(
            headless=settings.headless,
            slow_mo=settings.slow_mo_ms,
        )
        yield browser
        browser.close()


@pytest.fixture()
def page(browser: Browser, settings: TestSettings) -> Generator[Page, Any, None]:
    context = browser.new_context(ignore_https_errors=True)
    page = context.new_page()
    page.set_default_timeout(settings.ui_timeout_ms)
    yield page
    context.close()


@pytest.fixture()
def tiny_png(tmp_path: Path) -> Generator[Path, Any, None]:
    image_path = tmp_path / "avatar.png"

    img = Image.new("RGB", (128, 128), color="white")
    img.save(image_path, format="PNG")

    yield image_path

    if image_path.exists():
        remove(image_path)


@pytest.fixture(scope="module")
def db_outage(api_client: ApiClient, seeded_data, aux_entities) -> Generator[None, Any, None]:
    _ensure_fault_mode_enabled()

    running = _docker_compose("ps", "--services", "--status", "running")
    if running.returncode != 0 or "db" not in running.stdout.split():
        pytest.skip("Compose db service is not running; cannot execute fault-injection 500 tests.")

    stopped = _docker_compose("stop", "db")
    if stopped.returncode != 0:
        pytest.skip(f"Unable to stop db service for fault-injection: {stopped.stderr.strip()}")

    time.sleep(3)
    try:
        yield
    finally:
        _docker_compose("up", "-d", "db")
        time.sleep(8)
