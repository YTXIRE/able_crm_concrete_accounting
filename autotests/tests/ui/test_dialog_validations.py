from __future__ import annotations

import pytest
from playwright.sync_api import expect

from autotests.page_objects.app_shell_page import AppShellPage
from autotests.page_objects.dialog_page import DialogPage


@pytest.mark.ui
def test_vendor_create_dialog_requires_name(page, settings, auth_bundle) -> None:
    shell = AppShellPage(page, settings.frontend_url)
    shell.bootstrap_session(auth_bundle, "/vendors", "5")
    shell.open_route("/vendors")
    dialog = DialogPage(page)
    dialog.open_dialog_and_submit("Создание нового поставщика")
    dialog.assert_validation_message("Пожалуйста, укажите название поставщика")


@pytest.mark.ui
def test_object_create_dialog_requires_name(page, settings, auth_bundle) -> None:
    shell = AppShellPage(page, settings.frontend_url)
    shell.bootstrap_session(auth_bundle, "/objects", "6")
    shell.open_route("/objects")
    dialog = DialogPage(page)
    dialog.open_dialog_and_submit("Создание объекта")
    dialog.assert_validation_message("Пожалуйста, укажите название типа материала")


@pytest.mark.ui
def test_material_type_create_dialog_requires_name_and_unit(page, settings, auth_bundle) -> None:
    shell = AppShellPage(page, settings.frontend_url)
    shell.bootstrap_session(auth_bundle, "/material_types", "8")
    shell.open_route("/material_types")
    dialog = DialogPage(page)
    dialog.open_dialog_and_submit("Создание типа материала")
    dialog.assert_validation_message("Пожалуйста, укажите название типа материала")
    dialog.assert_validation_message("Пожалуйста, выберите величину объема")


@pytest.mark.ui
def test_material_type_create_dialog_shows_unit_short_and_full_names(page, settings, auth_bundle) -> None:
    shell = AppShellPage(page, settings.frontend_url)
    shell.bootstrap_session(auth_bundle, "/material_types", "8")
    shell.open_route("/material_types")
    dialog = DialogPage(page)
    dialog.open_create_dialog()
    dialog.assert_dialog_title("Создание типа материала")

    page.locator(".el-dialog:visible .el-select").first.click()
    option = page.locator(".el-select-dropdown:visible .el-select-dropdown__item").filter(has_text="Штука").first
    expect(option.locator(".label")).to_have_text("шт")
    expect(option.locator(".name")).to_have_text("Штука")


@pytest.mark.ui
def test_material_create_dialog_requires_name_and_type(page, settings, auth_bundle) -> None:
    shell = AppShellPage(page, settings.frontend_url)
    shell.bootstrap_session(auth_bundle, "/materials", "7")
    shell.open_route("/materials")
    dialog = DialogPage(page)
    dialog.open_dialog_and_submit("Создание материала")
    dialog.assert_validation_message("Пожалуйста, укажите название типа материала")
    dialog.assert_validation_message("Пожалуйста, выберите типа материала")


@pytest.mark.ui
def test_legal_entity_create_dialog_requires_type_and_name(page, settings, auth_bundle) -> None:
    shell = AppShellPage(page, settings.frontend_url)
    shell.bootstrap_session(auth_bundle, "/legal_entities", "9")
    shell.open_route("/legal_entities")
    dialog = DialogPage(page)
    dialog.open_dialog_and_submit("Создание нового юридического лица")
    dialog.assert_validation_message("Пожалуйста, выберите тип управления")
    dialog.assert_validation_message("Пожалуйста, укажите название организации")


@pytest.mark.ui
def test_payment_create_dialog_requires_required_fields(page, settings, auth_bundle) -> None:
    shell = AppShellPage(page, settings.frontend_url)
    shell.bootstrap_session(auth_bundle, "/payments", "4")
    shell.open_route("/payments")
    dialog = DialogPage(page)
    dialog.open_dialog_and_submit("Создание платежа")
    dialog.assert_validation_message("Пожалуйста, выберите поставщика")
    dialog.assert_validation_message("Пожалуйста, выберите юридическое лицо")
    dialog.assert_validation_message("Пожалуйста, выберите тип материала")
    dialog.assert_validation_message("Пожалуйста, укажите сумму")
