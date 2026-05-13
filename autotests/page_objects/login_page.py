from __future__ import annotations

from playwright.sync_api import Page, expect


class LoginPage:
    def __init__(self, page: Page, frontend_url: str):
        self.page = page
        self.frontend_url = frontend_url

    def open(self) -> None:
        self.page.goto(f"{self.frontend_url}/#/")
        expect(self.page.get_by_role("heading", name="Авторизация")).to_be_visible()

    def login(self, login: str, password: str) -> None:
        self.page.get_by_placeholder("Логин").fill(login)
        self.page.get_by_placeholder("Пароль").fill(password)
        self.page.get_by_role("button", name="Войти").click()

    def submit_empty(self) -> None:
        self.page.get_by_role("button", name="Войти").click()

    def assert_validation_message(self, message: str) -> None:
        expect(self.page.locator(".el-form-item__error").filter(has_text=message).first).to_be_visible()

    def assert_login_page(self) -> None:
        expect(self.page.get_by_role("heading", name="Авторизация")).to_be_visible()

    def assert_notification(self, message: str) -> None:
        expect(self.page.locator(".el-notification").filter(has_text=message).first).to_be_visible()
