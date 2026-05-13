from __future__ import annotations

import pytest

from autotests.page_objects.app_shell_page import AppShellPage
from autotests.page_objects.login_page import LoginPage


ROUTES = [
    ("/dashboard", "Dashboard", "1"),
    ("/history", "История операций", "3"),
    ("/payments", "Оплата", "4"),
    ("/vendors", "Поставщики", "5"),
    ("/objects", "Список объектов", "6"),
    ("/materials", "Список материалов", "7"),
    ("/material_types", "Типы материалов", "8"),
    ("/legal_entities", "Юридические лица", "9"),
    ("/reports", "Отчеты", "10"),
    ("/settings", "Настройки приложения", "0"),
    ("/user_settings", "Настройки пользователя", "999"),
]


@pytest.mark.ui
def test_login_page_and_successful_sign_in(page, settings) -> None:
    login_page = LoginPage(page, settings.frontend_url)
    login_page.open()
    login_page.login(settings.admin_login, settings.admin_password)

    shell = AppShellPage(page, settings.frontend_url)
    shell.assert_heading("Dashboard")
    shell.assert_header_visible()


@pytest.mark.ui
@pytest.mark.parametrize("route,heading,current_menu", ROUTES)
def test_each_frontend_module_route_renders(route, heading, current_menu, page, settings, auth_bundle) -> None:
    shell = AppShellPage(page, settings.frontend_url)
    shell.bootstrap_session(auth_bundle, route, current_menu)
    shell.open_route(route)
    shell.assert_header_visible()
    shell.assert_heading(heading)
