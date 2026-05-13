from __future__ import annotations

import pytest
from playwright.sync_api import expect

from autotests.page_objects.app_shell_page import AppShellPage
from autotests.page_objects.login_page import LoginPage


@pytest.mark.ui
class TestAuthExtendedUi:
    def test_login_form_validates_empty_fields(self, page, settings) -> None:
        login_page = LoginPage(page, settings.frontend_url)
        login_page.open()
        login_page.submit_empty()
        login_page.assert_validation_message("Пожалуйста, заполните поле логин")
        login_page.assert_validation_message("Пожалуйста, заполните поле пароль")

    def test_dashboard_redirects_to_login_without_token(self, page, settings) -> None:
        page.goto(f"{settings.frontend_url}/#/dashboard")
        expect(page).to_have_url(f"{settings.frontend_url}/#/")
        expect(page.get_by_role("heading", name="Авторизация")).to_be_visible()

    def test_root_redirects_to_dashboard_with_token_and_without_current_link(self, page, settings, auth_bundle) -> None:
        shell = AppShellPage(page, settings.frontend_url)
        shell.bootstrap_session(auth_bundle, "/dashboard", "1")
        page.add_init_script("localStorage.removeItem('currentLink')")
        page.goto(f"{settings.frontend_url}/#/")
        expect(page).to_have_url(f"{settings.frontend_url}/#/dashboard")
        shell.assert_heading("Dashboard")

    def test_root_restores_current_link_from_session(self, page, settings, auth_bundle) -> None:
        shell = AppShellPage(page, settings.frontend_url)
        shell.bootstrap_session(auth_bundle, "/reports", "10")
        page.goto(f"{settings.frontend_url}/#/")
        expect(page).to_have_url(f"{settings.frontend_url}/#/reports")
        shell.assert_heading("Отчеты")

    def test_login_with_wrong_password_stays_on_login_page(self, page, settings) -> None:
        login_page = LoginPage(page, settings.frontend_url)
        login_page.open()
        login_page.login(settings.admin_login, "wrong-password")
        login_page.assert_login_page()
        login_page.assert_notification("Неверный логин или пароль")

    def test_successful_login_stores_session_in_local_storage(self, page, settings) -> None:
        login_page = LoginPage(page, settings.frontend_url)
        login_page.open()
        login_page.login(settings.admin_login, settings.admin_password)

        shell = AppShellPage(page, settings.frontend_url)
        shell.assert_heading("Dashboard")
        assert shell.read_local_storage("crm_token")
        assert shell.read_local_storage("user_id")
        assert shell.read_local_storage("is_demo") is not None
        assert shell.read_local_storage("user_data")

    def test_logout_clears_local_storage(self, page, settings, auth_bundle) -> None:
        shell = AppShellPage(page, settings.frontend_url)
        shell.bootstrap_session(auth_bundle, "/dashboard", "1")
        shell.open_route("/dashboard")
        shell.click_logout_header_button()
        expect(page).to_have_url(f"{settings.frontend_url}/#/")
        assert shell.read_local_storage("crm_token") is None
        assert shell.read_local_storage("user_id") is None
        assert shell.read_local_storage("user_data") is None
        assert shell.read_local_storage("currentMenu") is None
        assert shell.read_local_storage("currentLink") is None
