from __future__ import annotations

import pytest
from playwright.sync_api import expect

from autotests.page_objects.app_shell_page import AppShellPage
from autotests.page_objects.dialog_page import DialogPage


def _select_dialog_option(page, index: int, option_text: str) -> None:
    page.locator(".el-dialog:visible .el-select").nth(index).click()
    page.locator(".el-select-dropdown:visible .el-select-dropdown__item").filter(has_text=option_text).first.click()


@pytest.mark.ui
class TestPaymentsUiExtended:
    def test_payments_shows_placeholder_until_legal_entity_selected(self, page, settings, auth_bundle) -> None:
        shell = AppShellPage(page, settings.frontend_url)
        shell.bootstrap_session(auth_bundle, "/payments", "4")
        shell.open_route("/payments")
        shell.assert_heading("Оплата")
        expect(page.locator(".el-menu-item").first).to_be_visible()
        expect(page.get_by_role("columnheader", name="Тип операции")).to_have_count(0)

    def test_payments_selecting_legal_entity_shows_payments_table(self, page, settings, auth_bundle, seeded_data) -> None:
        shell = AppShellPage(page, settings.frontend_url)
        shell.bootstrap_session(auth_bundle, "/payments", "4")
        shell.open_route("/payments")
        page.get_by_text(seeded_data["legal_entity"]["full_name"]).click()
        expect(page.get_by_role("columnheader", name="Тип операции")).to_be_visible()

    def test_payment_create_dialog_rejects_non_numeric_amount(self, page, settings, auth_bundle, seeded_data) -> None:
        shell = AppShellPage(page, settings.frontend_url)
        shell.bootstrap_session(auth_bundle, "/payments", "4")
        shell.open_route("/payments")

        dialog = DialogPage(page)
        dialog.open_create_dialog()
        dialog.assert_dialog_title("Создание платежа")

        _select_dialog_option(page, 0, seeded_data["vendor"]["name"])
        _select_dialog_option(page, 1, seeded_data["legal_entity"]["full_name"])
        _select_dialog_option(page, 2, seeded_data["material_type"]["name"])
        page.locator('.el-dialog:visible .el-form input:not([readonly])').first.fill("abc###")
        dialog.submit_dialog()
        dialog.assert_validation_message("Пожалуйста, укажите корректную сумму")
