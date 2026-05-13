from __future__ import annotations

import pytest
import requests
from playwright.sync_api import expect

from autotests.page_objects.app_shell_page import AppShellPage


@pytest.mark.ui
class TestSettingsUiExtended:
    def test_settings_timezone_selector_is_visible(self, page, settings, auth_bundle) -> None:
        shell = AppShellPage(page, settings.frontend_url)
        shell.bootstrap_session(auth_bundle, "/settings", "0")
        shell.open_route("/settings")
        shell.assert_heading("Настройки приложения")
        expect(page.get_by_placeholder("Выберите часовой пояс")).to_be_visible()

    def test_settings_user_create_dialog_validates_required_fields(self, page, settings, auth_bundle) -> None:
        shell = AppShellPage(page, settings.frontend_url)
        shell.bootstrap_session(auth_bundle, "/settings", "0")
        shell.open_route("/settings")
        page.get_by_role("tab", name="Пользователи").click()
        page.locator(".create .el-button--success").first.click()
        expect(page.get_by_text("Создание пользователя")).to_be_visible()
        page.get_by_role("button", name="Сохранить").click()
        expect(page.locator(".el-form-item__error").filter(has_text="Пожалуйста, укажите адрес электронной почты").first).to_be_visible()
        expect(page.locator(".el-form-item__error").filter(has_text="Пожалуйста, укажите пароль").first).to_be_visible()

    def test_settings_debt_checkbox_is_visible(self, page, settings, auth_bundle) -> None:
        shell = AppShellPage(page, settings.frontend_url)
        shell.bootstrap_session(auth_bundle, "/settings", "0")
        shell.open_route("/settings")
        page.get_by_role("tab", name="Итог на конец года").click()
        expect(page.get_by_text("Включить долг на конец года")).to_be_visible()

    def test_user_settings_user_data_validates_email(self, page, settings, auth_bundle) -> None:
        shell = AppShellPage(page, settings.frontend_url)
        shell.bootstrap_session(auth_bundle, "/user_settings", "999")
        shell.open_route("/user_settings")
        shell.assert_heading("Настройки пользователя")
        email_input = page.locator('.el-tab-pane[aria-hidden="false"] input').nth(1)
        email_input.fill("bad-email")
        page.get_by_role("button", name="Сохранить").first.click()
        expect(page.locator(".el-form-item__error").filter(has_text="Пожалуйста, введите корректный адрес электронной почты").first).to_be_visible()

    def test_user_settings_change_password_validates_min_length(self, page, settings, auth_bundle) -> None:
        shell = AppShellPage(page, settings.frontend_url)
        shell.bootstrap_session(auth_bundle, "/user_settings", "999")
        shell.open_route("/user_settings")
        page.get_by_role("tab", name="Пароль").click()
        password_input = page.locator('.el-tab-pane[aria-hidden="false"] input[type="password"]').first
        password_input.fill("1234")
        page.get_by_role("button", name="Сохранить").first.click()
        expect(page.locator(".el-form-item__error").filter(has_text="Минимальная длинна пароля должна быть не менее 5 символов").first).to_be_visible()

    def test_user_settings_avatar_upload_control_is_visible(self, page, settings, auth_bundle) -> None:
        shell = AppShellPage(page, settings.frontend_url)
        shell.bootstrap_session(auth_bundle, "/user_settings", "999")
        shell.open_route("/user_settings")
        page.get_by_role("tab", name="Аватарка").click()
        expect(page.locator(".avatar-uploader")).to_be_visible()

    def test_user_settings_avatar_upload_succeeds(self, page, settings, auth_bundle, tiny_png) -> None:
        shell = AppShellPage(page, settings.frontend_url)
        shell.bootstrap_session(auth_bundle, "/user_settings", "999")
        shell.open_route("/user_settings")
        page.get_by_role("tab", name="Аватарка").click()

        initial_avatar_path = page.evaluate("JSON.parse(localStorage.getItem('user_data')).avatar")
        page.locator('.avatar-uploader input[type="file"]').set_input_files(str(tiny_png))
        page.wait_for_function(
            "previousAvatar => JSON.parse(localStorage.getItem('user_data'))?.avatar && JSON.parse(localStorage.getItem('user_data')).avatar !== previousAvatar",
            arg=initial_avatar_path,
        )
        avatar_path = page.evaluate("JSON.parse(localStorage.getItem('user_data')).avatar")
        assert isinstance(avatar_path, str)
        assert avatar_path.startswith('/files/')

        avatar_response = requests.get(f"{settings.api_url}{avatar_path}", timeout=settings.timeout_seconds)
        assert avatar_response.ok
