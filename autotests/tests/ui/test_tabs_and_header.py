from __future__ import annotations

import pytest
from playwright.sync_api import expect

from autotests.page_objects.app_shell_page import AppShellPage


@pytest.mark.ui
def test_reports_tabs_are_visible(page, settings, auth_bundle) -> None:
    shell = AppShellPage(page, settings.frontend_url)
    shell.bootstrap_session(auth_bundle, "/reports", "10")
    shell.open_route("/reports")
    shell.assert_heading("Отчеты")
    expect(page.get_by_text("Базовый")).to_be_visible()
    expect(page.get_by_text("Расширенный")).to_be_visible()


@pytest.mark.ui
def test_settings_tabs_are_visible_for_admin(page, settings, auth_bundle) -> None:
    shell = AppShellPage(page, settings.frontend_url)
    shell.bootstrap_session(auth_bundle, "/settings", "0")
    shell.open_route("/settings")
    shell.assert_heading("Настройки приложения")
    expect(page.get_by_text("Настройка часового пояса")).to_be_visible()
    expect(page.get_by_text("Пользователи")).to_be_visible()
    expect(page.get_by_text("Итог на конец года")).to_be_visible()


@pytest.mark.ui
def test_user_settings_tabs_are_visible(page, settings, auth_bundle) -> None:
    shell = AppShellPage(page, settings.frontend_url)
    shell.bootstrap_session(auth_bundle, "/user_settings", "999")
    shell.open_route("/user_settings")
    shell.assert_heading("Настройки пользователя")
    expect(page.get_by_role("tab", name="Личные данные")).to_be_visible()
    expect(page.get_by_role("tab", name="Пароль")).to_be_visible()
    expect(page.get_by_role("tab", name="Аватарка")).to_be_visible()


@pytest.mark.ui
def test_header_navigation_to_settings_and_user_settings(page, settings, auth_bundle) -> None:
    shell = AppShellPage(page, settings.frontend_url)
    shell.bootstrap_session(auth_bundle, "/dashboard", "1")
    shell.open_route("/dashboard")
    shell.assert_heading("Dashboard")

    shell.click_settings_header_button()
    shell.assert_heading("Настройки приложения")

    shell.click_user_settings_header_button()
    shell.assert_heading("Настройки пользователя")


@pytest.mark.ui
def test_logout_from_header_returns_to_login(page, settings, auth_bundle) -> None:
    shell = AppShellPage(page, settings.frontend_url)
    shell.bootstrap_session(auth_bundle, "/dashboard", "1")
    shell.open_route("/dashboard")
    shell.assert_heading("Dashboard")

    shell.click_logout_header_button()
    expect(page).to_have_url(f"{settings.frontend_url}/#/")
    expect(page.get_by_role("heading", name="Авторизация")).to_be_visible()
