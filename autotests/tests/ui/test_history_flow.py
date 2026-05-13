from __future__ import annotations

import re

import pytest
from playwright.sync_api import expect

from autotests.page_objects.app_shell_page import AppShellPage
from autotests.page_objects.dialog_page import DialogPage


def _select_dialog_option(page, index: int, option_text: str) -> None:
    page.locator(".el-dialog:visible .el-select").nth(index).click()
    page.locator(".el-select-dropdown:visible .el-select-dropdown__item").filter(has_text=option_text).first.click()


@pytest.mark.ui
def test_history_create_dialog_renders_stepper(page, settings, auth_bundle) -> None:
    shell = AppShellPage(page, settings.frontend_url)
    shell.bootstrap_session(auth_bundle, "/history", "3")
    shell.open_route("/history")
    dialog = DialogPage(page)
    dialog.open_create_dialog()
    dialog.assert_dialog_title("Создание новой операции")

    expect(page.get_by_text("Основа")).to_be_visible()
    expect(page.get_by_text("Детали")).to_be_visible()
    expect(page.get_by_text("Итог")).to_be_visible()
    expect(page.get_by_role("button", name="Далее")).to_be_disabled()


@pytest.mark.ui
def test_history_first_step_requires_vendor_before_material_type(page, settings, auth_bundle) -> None:
    shell = AppShellPage(page, settings.frontend_url)
    shell.bootstrap_session(auth_bundle, "/history", "3")
    shell.open_route("/history")
    dialog = DialogPage(page)
    dialog.open_create_dialog()
    dialog.assert_dialog_title("Создание новой операции")

    material_type_input = page.locator(".el-dialog:visible .el-select input").nth(1)
    expect(material_type_input).to_be_disabled()


@pytest.mark.ui
def test_history_first_step_can_be_completed_with_valid_data(page, settings, auth_bundle, seeded_data) -> None:
    shell = AppShellPage(page, settings.frontend_url)
    shell.bootstrap_session(auth_bundle, "/history", "3")
    shell.open_route("/history")
    dialog = DialogPage(page)
    dialog.open_create_dialog()
    dialog.assert_dialog_title("Создание новой операции")

    _select_dialog_option(page, 0, seeded_data["vendor"]["name"])
    _select_dialog_option(page, 1, seeded_data["material_type"]["name"])
    _select_dialog_option(page, 2, seeded_data["material"]["name"])
    _select_dialog_option(page, 3, seeded_data["legal_entity"]["full_name"])

    expect(page.get_by_role("button", name="Далее")).to_be_enabled()
