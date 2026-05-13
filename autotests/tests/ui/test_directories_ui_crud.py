from __future__ import annotations

import pytest
from playwright.sync_api import expect

from autotests.page_objects.app_shell_page import AppShellPage
from autotests.page_objects.dialog_page import DialogPage


def _row_by_text(page, text: str):
    return page.locator(".el-table__body-wrapper tbody tr").filter(has_text=text).first


def _active_tab_row_by_text(page, text: str):
    return page.locator('.el-tab-pane[aria-hidden="false"] .el-table__body-wrapper tbody tr').filter(has_text=text).first


def _confirm_popconfirm(page):
    page.get_by_role("button", name="Естественно").click()


def _dialog_text_input(page, index: int = 0):
    return page.locator('.el-dialog:visible input:not([readonly]):not([type="hidden"])').nth(index)


def _wait_dialog_closed(page) -> None:
    expect(page.locator('.el-dialog:visible')).to_have_count(0)


def _select_dialog_option(page, index: int, option_text: str) -> None:
    page.locator(".el-dialog:visible .el-select").nth(index).click()
    page.locator(".el-select-dropdown:visible .el-select-dropdown__item").filter(has_text=option_text).first.click()


@pytest.mark.ui
class TestDirectoriesUiCrud:
    def test_vendors_create_edit_archive_restore(self, page, settings, auth_bundle, api_client) -> None:
        shell = AppShellPage(page, settings.frontend_url)
        shell.bootstrap_session(auth_bundle, "/vendors", "5")
        shell.open_route("/vendors")
        shell.assert_heading("Поставщики")

        created_name = api_client.unique_name("vendor_ui")
        updated_name = api_client.unique_name("vendor_ui_upd")
        dialog = DialogPage(page)

        dialog.open_create_dialog()
        dialog.assert_dialog_title("Создание нового поставщика")
        _dialog_text_input(page).fill(created_name)
        dialog.submit_dialog()
        _wait_dialog_closed(page)
        expect(_active_tab_row_by_text(page, created_name)).to_be_visible()

        created_row = _active_tab_row_by_text(page, created_name)
        created_row.locator("button").first.click()
        dialog.assert_dialog_title("Редактирование поставщика")
        _dialog_text_input(page).fill(updated_name)
        dialog.submit_dialog()
        _wait_dialog_closed(page)
        expect(_active_tab_row_by_text(page, updated_name)).to_be_visible()

        updated_row = _active_tab_row_by_text(page, updated_name)
        updated_row.locator(".remove").click()
        _confirm_popconfirm(page)
        expect(page.locator('.el-tab-pane[aria-hidden="false"] .el-table__body-wrapper tbody tr').filter(has_text=updated_name)).to_have_count(0)

        page.get_by_role("tab", name="Архивные").click()
        expect(_active_tab_row_by_text(page, updated_name)).to_be_visible()
        _active_tab_row_by_text(page, updated_name).locator('.el-button--info').click()
        page.get_by_role("tab", name="Активные").click()
        expect(_active_tab_row_by_text(page, updated_name)).to_be_visible()

    def test_objects_create_search_edit_archive_restore(self, page, settings, auth_bundle, api_client) -> None:
        shell = AppShellPage(page, settings.frontend_url)
        shell.bootstrap_session(auth_bundle, "/objects", "6")
        shell.open_route("/objects")
        shell.assert_heading("Список объектов")

        created_name = api_client.unique_name("object_ui")
        updated_name = api_client.unique_name("object_ui_upd")
        dialog = DialogPage(page)

        dialog.open_create_dialog()
        dialog.assert_dialog_title("Создание объекта")
        _dialog_text_input(page).fill(created_name)
        dialog.submit_dialog()
        _wait_dialog_closed(page)
        expect(_active_tab_row_by_text(page, created_name)).to_be_visible()

        search_input = page.get_by_placeholder("Введите название объекта")
        search_input.fill(created_name[:6])
        page.get_by_role("button").filter(has=page.locator("svg[data-icon='search']")).first.click()
        expect(_active_tab_row_by_text(page, created_name)).to_be_visible()

        row = _row_by_text(page, created_name)
        row.locator("button").first.click()
        dialog.assert_dialog_title("Создание объекта")
        _dialog_text_input(page).fill(updated_name)
        dialog.submit_dialog()
        _wait_dialog_closed(page)
        search_input.fill("")
        expect(_active_tab_row_by_text(page, updated_name)).to_be_visible()

        row = _active_tab_row_by_text(page, updated_name)
        row.locator(".remove").click()
        _confirm_popconfirm(page)
        expect(page.locator('.el-tab-pane[aria-hidden="false"] .el-table__body-wrapper tbody tr').filter(has_text=updated_name)).to_have_count(0)

        page.get_by_role("tab", name="Архивные").click()
        expect(_active_tab_row_by_text(page, updated_name)).to_be_visible()
        _active_tab_row_by_text(page, updated_name).locator('.el-button--info').click()
        page.get_by_role("tab", name="Активные").click()
        expect(_active_tab_row_by_text(page, updated_name)).to_be_visible()

    def test_materials_create_search_edit(self, page, settings, auth_bundle, seeded_data, api_client) -> None:
        shell = AppShellPage(page, settings.frontend_url)
        shell.bootstrap_session(auth_bundle, "/materials", "7")
        shell.open_route("/materials")
        shell.assert_heading("Список материалов")

        created_name = api_client.unique_name("material_ui")
        updated_name = api_client.unique_name("material_ui_upd")
        dialog = DialogPage(page)

        dialog.open_create_dialog()
        dialog.assert_dialog_title("Создание материала")
        _dialog_text_input(page).fill(created_name)
        _select_dialog_option(page, 0, seeded_data["material_type"]["name"])
        dialog.submit_dialog()
        _wait_dialog_closed(page)
        expect(_row_by_text(page, created_name)).to_be_visible()

        search_input = page.get_by_placeholder("Введите название материала")
        search_input.fill(created_name[:6])
        page.get_by_role("button").filter(has=page.locator("svg[data-icon='search']")).first.click()
        expect(_row_by_text(page, created_name)).to_be_visible()

        row = _row_by_text(page, created_name)
        row.locator("button").first.click()
        dialog.assert_dialog_title("Редактирование материала")
        _dialog_text_input(page).fill(updated_name)
        dialog.submit_dialog()
        _wait_dialog_closed(page)
        search_input.fill("")
        expect(_row_by_text(page, updated_name)).to_be_visible()

    def test_legal_entities_create_edit_archive_restore(self, page, settings, auth_bundle, seeded_data, api_client) -> None:
        shell = AppShellPage(page, settings.frontend_url)
        shell.bootstrap_session(auth_bundle, "/legal_entities", "9")
        shell.open_route("/legal_entities")
        shell.assert_heading("Юридические лица")

        created_name = api_client.unique_name("legal_ui")[:18]
        updated_name = api_client.unique_name("legal_ui_upd")[:18]
        dialog = DialogPage(page)

        dialog.open_create_dialog()
        dialog.assert_dialog_title("Создание нового юридического лица")
        _select_dialog_option(page, 0, seeded_data["legal_entity_types"][0]["name"])
        _dialog_text_input(page).fill(created_name)
        dialog.submit_dialog()
        _wait_dialog_closed(page)
        expect(_row_by_text(page, created_name)).to_be_visible()

        row = _row_by_text(page, created_name)
        row.locator("button").first.click()
        dialog.assert_dialog_title("Редактирование юридического лица")
        _dialog_text_input(page).fill(updated_name)
        dialog.submit_dialog()
        _wait_dialog_closed(page)
        expect(_active_tab_row_by_text(page, updated_name)).to_be_visible()

        row = _active_tab_row_by_text(page, updated_name)
        row.locator(".remove").click()
        _confirm_popconfirm(page)
        expect(page.locator('.el-tab-pane[aria-hidden="false"] .el-table__body-wrapper tbody tr').filter(has_text=updated_name)).to_have_count(0)

        page.get_by_role("tab", name="Архивные").click()
        expect(_active_tab_row_by_text(page, updated_name)).to_be_visible()
        _active_tab_row_by_text(page, updated_name).locator('.el-button--info').click()
        page.get_by_role("tab", name="Активные").click()
        expect(_active_tab_row_by_text(page, updated_name)).to_be_visible()
