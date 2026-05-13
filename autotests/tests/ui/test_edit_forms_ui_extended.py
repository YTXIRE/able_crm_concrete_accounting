from __future__ import annotations

import pytest
from playwright.sync_api import expect

from autotests.page_objects.app_shell_page import AppShellPage
from autotests.page_objects.dialog_page import DialogPage


def _row_by_text(page, text: str):
    return page.locator('.el-table__body-wrapper tbody tr').filter(has_text=text).first


def _dialog_text_input(page, index: int = 0):
    return page.locator('.el-dialog:visible input:not([readonly]):not([type="hidden"])').nth(index)


def _select_dialog_option(page, index: int, option_text: str) -> None:
    page.locator('.el-dialog:visible .el-select').nth(index).click()
    page.locator('.el-select-dropdown:visible .el-select-dropdown__item').filter(has_text=option_text).first.click()


@pytest.mark.ui
class TestEditFormsUiExtended:
    def test_vendor_edit_dialog_requires_name(self, page, settings, auth_bundle, api_client) -> None:
        vendor = api_client.create_vendor(api_client.unique_name('vendor_edit'))
        shell = AppShellPage(page, settings.frontend_url)
        shell.bootstrap_session(auth_bundle, '/vendors', '5')
        shell.open_route('/vendors')

        row = _row_by_text(page, vendor['name'])
        row.locator('button').first.click()
        dialog = DialogPage(page)
        dialog.assert_dialog_title('Редактирование поставщика')
        _dialog_text_input(page).fill('')
        page.locator('.el-dialog:visible').get_by_role('button', name='Сохранить').click()
        dialog.assert_validation_message('Пожалуйста, укажите название поставщика')

    def test_object_edit_dialog_requires_name(self, page, settings, auth_bundle, api_client) -> None:
        obj = api_client.create_object(api_client.unique_name('object_edit'))
        shell = AppShellPage(page, settings.frontend_url)
        shell.bootstrap_session(auth_bundle, '/objects', '6')
        shell.open_route('/objects')

        row = _row_by_text(page, obj['name'])
        row.locator('button').first.click()
        dialog = DialogPage(page)
        dialog.assert_dialog_title('Создание объекта')
        _dialog_text_input(page).fill('')
        page.locator('.el-dialog:visible').get_by_role('button', name='Сохранить').click()
        dialog.assert_validation_message('Пожалуйста, укажите название типа материала')

    def test_material_edit_dialog_requires_name(self, page, settings, auth_bundle, seeded_data, api_client) -> None:
        material = api_client.create_material(api_client.unique_name('material_edit'), seeded_data['material_type']['id'])
        shell = AppShellPage(page, settings.frontend_url)
        shell.bootstrap_session(auth_bundle, '/materials', '7')
        shell.open_route('/materials')

        row = _row_by_text(page, material['name'])
        row.locator('button').first.click()
        dialog = DialogPage(page)
        dialog.assert_dialog_title('Редактирование материала')
        _dialog_text_input(page).fill('')
        page.locator('.el-dialog:visible').get_by_role('button', name='Сохранить').click()
        dialog.assert_validation_message('Пожалуйста, укажите название типа материала')

    def test_legal_entity_edit_dialog_requires_name(self, page, settings, auth_bundle, seeded_data, api_client) -> None:
        legal = api_client.create_legal_entity(api_client.unique_name('legal_edit')[:18], seeded_data['legal_entity_types'][0]['id'])
        shell = AppShellPage(page, settings.frontend_url)
        shell.bootstrap_session(auth_bundle, '/legal_entities', '9')
        shell.open_route('/legal_entities')

        row = _row_by_text(page, legal['name'])
        row.locator('button').first.click()
        dialog = DialogPage(page)
        dialog.assert_dialog_title('Редактирование юридического лица')
        _dialog_text_input(page).fill('')
        page.locator('.el-dialog:visible').get_by_role('button', name='Сохранить').click()
        dialog.assert_validation_message('Пожалуйста, укажите название организации')

    def test_payment_edit_dialog_rejects_non_numeric_amount(self, page, settings, auth_bundle, aux_entities, seeded_data) -> None:
        shell = AppShellPage(page, settings.frontend_url)
        shell.bootstrap_session(auth_bundle, '/payments', '4')
        shell.open_route('/payments')

        page.get_by_text(seeded_data['legal_entity']['full_name']).click()
        row = page.locator('.el-table__body-wrapper tbody tr').first
        row.locator('button').first.click()
        dialog = DialogPage(page)
        dialog.assert_dialog_title('Редактирование платежа')
        page.locator('.el-dialog:visible .el-form input:not([readonly])').first.fill('abc###')
        page.locator('.el-dialog:visible').get_by_role('button', name='Сохранить').click()
        dialog.assert_validation_message('Пожалуйста, укажите корректную сумму')

    def test_report_edit_filter_rejects_empty_name(self, page, settings, auth_bundle, aux_entities) -> None:
        shell = AppShellPage(page, settings.frontend_url)
        shell.bootstrap_session(auth_bundle, '/reports', '10')
        shell.open_route('/reports')
        page.get_by_role('tab', name='Расширенный').click()

        card = page.locator('.card').filter(has_text=aux_entities['filter']['name']).first
        card.locator('button').first.click()
        expect(page.get_by_role('dialog').get_by_text('Настройка представления').first).to_be_visible()
        page.locator('.el-dialog:visible input').first.fill('')
        page.locator('.el-dialog:visible').get_by_role('button', name='Сохранить').click()
        expect(page.locator('.el-message').filter(has_text='Пожалуйста, заполните все поля').first).to_be_visible()

    def test_settings_users_edit_dialog_rejects_invalid_email(self, page, settings, auth_bundle, aux_entities) -> None:
        shell = AppShellPage(page, settings.frontend_url)
        shell.bootstrap_session(auth_bundle, '/settings', '0')
        shell.open_route('/settings')
        page.get_by_role('tab', name='Пользователи').click()

        row = _row_by_text(page, aux_entities['user']['login'])
        row.locator('button').first.click()
        dialog = DialogPage(page)
        dialog.assert_dialog_title('Редактирование пользователя')
        page.locator('.el-dialog:visible input').nth(1).fill('bad-email')
        page.locator('.el-dialog:visible').get_by_role('button', name='Сохранить').click()
        dialog.assert_validation_message('Пожалуйста, введите корректный адрес электронной почты')

    def test_history_edit_dialog_disables_next_when_total_cleared(self, page, settings, auth_bundle, seeded_data) -> None:
        shell = AppShellPage(page, settings.frontend_url)
        shell.bootstrap_session(auth_bundle, '/history', '3')
        shell.open_route('/history')

        page.locator('.menu .el-menu-item').first.click()
        page.locator('.el-collapse-item__header').nth(0).click()
        page.locator('.el-collapse-item__header').nth(1).click()

        page.locator('.el-table__body-wrapper tbody tr').first.locator('button').first.click()
        dialog = DialogPage(page)
        dialog.assert_dialog_title('Редактирование операции')
        page.get_by_role('button', name='Далее').click()
        page.locator('.el-dialog:visible input:not([readonly])').nth(2).fill('')
        expect(page.get_by_role('button', name='Далее')).to_be_disabled()
