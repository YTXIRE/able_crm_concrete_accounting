from __future__ import annotations

import pytest
from playwright.sync_api import expect

from autotests.page_objects.app_shell_page import AppShellPage


@pytest.mark.ui
class TestReportsUiExtended:
    def test_reports_advanced_empty_filter_submission_shows_error(self, page, settings, auth_bundle) -> None:
        shell = AppShellPage(page, settings.frontend_url)
        shell.bootstrap_session(auth_bundle, "/reports", "10")
        shell.open_route("/reports")
        page.get_by_role("tab", name="Расширенный").click()
        expect(page.get_by_role("button", name="Добавить новый фильтр")).to_be_visible()
        page.get_by_role("button", name="Добавить новый фильтр").click()
        expect(page.get_by_role("dialog").get_by_text("Настройка представления").first).to_be_visible()
        page.locator(".el-dialog:visible").get_by_role("button", name="Сохранить").click()
        expect(page.locator(".el-message").filter(has_text="Пожалуйста, заполните все поля").first).to_be_visible()

    def test_reports_advanced_existing_filter_loads_data(self, page, settings, auth_bundle, aux_entities) -> None:
        shell = AppShellPage(page, settings.frontend_url)
        shell.bootstrap_session(auth_bundle, "/reports", "10")
        shell.open_route("/reports")
        page.get_by_role("tab", name="Расширенный").click()
        page.get_by_text(aux_entities["filter"]["name"]).click()
        expect(page.get_by_role("columnheader", name="Тип материала")).to_be_visible()

    def test_reports_base_has_export_buttons(self, page, settings, auth_bundle) -> None:
        shell = AppShellPage(page, settings.frontend_url)
        shell.bootstrap_session(auth_bundle, "/reports", "10")
        shell.open_route("/reports")
        expect(page.get_by_role("button", name="Сохранить в PDF")).to_be_visible()
        expect(page.get_by_role("button", name="Печать")).to_be_visible()
