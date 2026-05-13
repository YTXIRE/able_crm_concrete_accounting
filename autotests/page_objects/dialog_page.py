from __future__ import annotations

from playwright.sync_api import Page, expect


class DialogPage:
    def __init__(self, page: Page):
        self.page = page

    def open_create_dialog(self) -> None:
        self.page.locator(".create .el-button--success").first.click()

    def submit_dialog(self) -> None:
        self.page.locator(".el-dialog:visible").get_by_role("button", name="Сохранить").click()

    def assert_dialog_title(self, title: str) -> None:
        expect(self.page.locator(".el-dialog:visible")).to_contain_text(title)

    def assert_validation_message(self, message: str) -> None:
        expect(self.page.locator(".el-form-item__error").filter(has_text=message).first).to_be_visible()

    def open_dialog_and_submit(self, title: str) -> None:
        self.open_create_dialog()
        self.assert_dialog_title(title)
        self.submit_dialog()
