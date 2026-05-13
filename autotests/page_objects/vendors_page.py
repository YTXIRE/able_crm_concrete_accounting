from __future__ import annotations

from playwright.sync_api import Locator, Page, expect


class VendorsPage:
    def __init__(self, page: Page):
        self.page = page

    def open_create_dialog(self) -> None:
        self.page.locator(".create .el-button--success").first.click()
        expect(self.page.get_by_text("Создание нового поставщика")).to_be_visible()

    def open_icon_picker(self) -> None:
        self.page.get_by_role("button", name="Выбрать иконку").click()
        expect(self.page.get_by_text("Иконки")).to_be_visible()

    def first_icon_svg(self) -> Locator:
        return self.page.locator(".el-dialog.is-fullscreen .el-card svg").first

    def go_to_next_icons_page(self) -> None:
        self.page.locator(".el-dialog.is-fullscreen .el-pagination .btn-next").click()
