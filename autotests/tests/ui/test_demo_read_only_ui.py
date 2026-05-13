from __future__ import annotations

import re

import pytest
from playwright.sync_api import expect

from autotests.page_objects.app_shell_page import AppShellPage


def _row_by_text(page, text: str):
    return page.locator('.el-table__body-wrapper tbody tr').filter(has_text=text).first


@pytest.mark.ui
class TestDemoReadOnlyUi:
    @pytest.mark.parametrize(
        "route,heading",
        [
            ("/vendors", "Поставщики"),
            ("/objects", "Список объектов"),
            ("/materials", "Список материалов"),
            ("/material_types", "Типы материалов"),
            ("/legal_entities", "Юридические лица"),
            ("/history", "История операций"),
            ("/payments", "Оплата"),
        ],
    )
    def test_create_buttons_are_disabled_in_read_only_mode(self, route, heading, page, settings, readonly_auth_bundle) -> None:
        shell = AppShellPage(page, settings.frontend_url)
        shell.bootstrap_session(readonly_auth_bundle, route, "0")
        shell.open_route(route)
        shell.assert_heading(heading)
        expect(page.locator('.create .el-button--success').first).to_be_disabled()

    def test_vendors_row_actions_are_disabled_in_read_only_mode(self, page, settings, readonly_auth_bundle, api_client) -> None:
        active_vendor = api_client.create_vendor(api_client.unique_name('vendor_ro_active'))
        archived_vendor = api_client.create_vendor(api_client.unique_name('vendor_ro_archived'))
        api_client.delete_vendor(archived_vendor['id'])

        shell = AppShellPage(page, settings.frontend_url)
        shell.bootstrap_session(readonly_auth_bundle, '/vendors', '5')
        shell.open_route('/vendors')

        active_row = _row_by_text(page, active_vendor['name'])
        expect(active_row.locator('button').first).to_be_disabled()
        expect(active_row.locator('.remove')).to_be_disabled()

        page.get_by_role('tab', name='Архивные').click()
        archived_row = _row_by_text(page, archived_vendor['name'])
        expect(archived_row.locator('.el-button--info')).to_be_disabled()

    def test_objects_row_actions_are_disabled_in_read_only_mode(self, page, settings, readonly_auth_bundle, api_client) -> None:
        active_object = api_client.create_object(api_client.unique_name('object_ro_active'))
        archived_object = api_client.create_object(api_client.unique_name('object_ro_archived'))
        api_client.delete_object(archived_object['id'])

        shell = AppShellPage(page, settings.frontend_url)
        shell.bootstrap_session(readonly_auth_bundle, '/objects', '6')
        shell.open_route('/objects')

        active_row = _row_by_text(page, active_object['name'])
        expect(active_row.locator('button').first).to_be_disabled()
        expect(active_row.locator('.remove')).to_be_disabled()

        page.get_by_role('tab', name='Архивные').click()
        archived_row = _row_by_text(page, archived_object['name'])
        expect(archived_row.locator('.el-button--info')).to_be_disabled()

    def test_legal_entities_row_actions_are_disabled_in_read_only_mode(
        self, page, settings, readonly_auth_bundle, api_client, seeded_data
    ) -> None:
        active_entity = api_client.create_legal_entity(api_client.unique_name('legal_ro_active')[:18], seeded_data['legal_entity_types'][0]['id'])
        archived_entity = api_client.create_legal_entity(api_client.unique_name('legal_ro_archived')[:18], seeded_data['legal_entity_types'][0]['id'])
        api_client.delete_legal_entity(archived_entity['id'])

        shell = AppShellPage(page, settings.frontend_url)
        shell.bootstrap_session(readonly_auth_bundle, '/legal_entities', '9')
        shell.open_route('/legal_entities')

        active_row = _row_by_text(page, active_entity['name'])
        expect(active_row.locator('button').first).to_be_disabled()
        expect(active_row.locator('.remove')).to_be_disabled()

        page.get_by_role('tab', name='Архивные').click()
        archived_row = _row_by_text(page, archived_entity['name'])
        expect(archived_row.locator('.el-button--info')).to_be_disabled()

    def test_materials_edit_actions_are_disabled_in_read_only_mode(self, page, settings, readonly_auth_bundle, seeded_data) -> None:
        shell = AppShellPage(page, settings.frontend_url)
        shell.bootstrap_session(readonly_auth_bundle, '/materials', '7')
        shell.open_route('/materials')

        row = _row_by_text(page, seeded_data['material']['name'])
        expect(row.locator('button').first).to_be_disabled()

    def test_material_types_edit_actions_are_disabled_in_read_only_mode(
        self, page, settings, readonly_auth_bundle, seeded_data
    ) -> None:
        shell = AppShellPage(page, settings.frontend_url)
        shell.bootstrap_session(readonly_auth_bundle, '/material_types', '8')
        shell.open_route('/material_types')

        row = _row_by_text(page, seeded_data['material_type']['name'])
        expect(row.locator('button').first).to_be_disabled()

    def test_payments_row_actions_are_disabled_in_read_only_mode(self, page, settings, readonly_auth_bundle, seeded_data) -> None:
        shell = AppShellPage(page, settings.frontend_url)
        shell.bootstrap_session(readonly_auth_bundle, '/payments', '4')
        shell.open_route('/payments')

        page.get_by_text(seeded_data['legal_entity']['full_name']).click()
        row = page.locator('.el-table__body-wrapper tbody tr').first
        expect(row).to_be_visible()
        expect(row.locator('button').first).to_be_disabled()
        expect(row.locator('.remove')).to_be_disabled()

    def test_history_row_actions_are_disabled_in_read_only_mode(
        self, page, settings, readonly_auth_bundle, seeded_data, api_client
    ) -> None:
        shell = AppShellPage(page, settings.frontend_url)
        shell.bootstrap_session(readonly_auth_bundle, '/history', '3')
        shell.open_route('/history')

        vendors = api_client.get_vendors(limit=0, offset=0, archive=0)['vendors']
        vendor_index = next(i for i, vendor in enumerate(vendors) if vendor['id'] == seeded_data['vendor']['id'])
        page.locator('.menu .el-menu-item').nth(vendor_index).click()
        expect(page.locator('.el-collapse-item__header').first).to_be_visible()
        page.locator('.el-collapse-item__header').nth(0).click()
        page.locator('.el-collapse-item__header').nth(1).click()

        row = page.locator('.el-table__body-wrapper tbody tr').first
        expect(row).to_be_visible()
        expect(row.locator('button').first).to_be_disabled()
        expect(row.locator('.remove')).to_be_disabled()

    def test_reports_mutation_controls_are_disabled_in_read_only_mode(self, page, settings, readonly_auth_bundle, aux_entities) -> None:
        shell = AppShellPage(page, settings.frontend_url)
        shell.bootstrap_session(readonly_auth_bundle, '/reports', '10')
        shell.open_route('/reports')

        expect(page.get_by_role('button', name='Сохранить в PDF')).to_be_disabled()
        expect(page.get_by_role('button', name='Печать')).to_be_disabled()

        page.get_by_role('tab', name='Расширенный').click()
        expect(page.get_by_role('button', name='Добавить новый фильтр')).to_be_disabled()

        card = page.locator('.card').filter(has_text=aux_entities['filter']['name']).first
        expect(card.locator('.el-button--primary').first).to_be_disabled()
        expect(card.locator('.el-button--danger').first).to_be_disabled()

    def test_settings_controls_are_disabled_in_read_only_mode(self, page, settings, readonly_auth_bundle, aux_entities) -> None:
        shell = AppShellPage(page, settings.frontend_url)
        shell.bootstrap_session(readonly_auth_bundle, '/settings', '0')
        shell.open_route('/settings')

        expect(page.locator('.time .el-button').first).to_be_disabled()
        expect(page.locator('.el-select input').first).to_be_disabled()

        page.get_by_role('tab', name='Пользователи').click()
        expect(page.locator('.create .el-button--success').first).to_be_disabled()
        row = _row_by_text(page, aux_entities['user']['login'])
        expect(row.locator('button').first).to_be_disabled()
        expect(row.locator('.el-button--danger').first).to_be_disabled()

        page.get_by_role('tab', name='Итог на конец года').click()
        expect(page.locator('.el-checkbox input').first).to_be_disabled()

    def test_user_settings_controls_are_disabled_in_read_only_mode(self, page, settings, readonly_auth_bundle) -> None:
        shell = AppShellPage(page, settings.frontend_url)
        shell.bootstrap_session(readonly_auth_bundle, '/user_settings', '999')
        shell.open_route('/user_settings')

        visible_inputs = page.locator('.el-tab-pane[aria-hidden="false"] input')
        expect(visible_inputs.nth(0)).to_be_disabled()
        expect(visible_inputs.nth(1)).to_be_disabled()
        expect(page.get_by_role('button', name='Сохранить').first).to_be_disabled()

        page.get_by_role('tab', name='Пароль').click()
        expect(page.locator('.el-tab-pane[aria-hidden="false"] input[type="password"]').first).to_be_disabled()
        expect(page.get_by_role('button', name='Сохранить').first).to_be_disabled()

        page.get_by_role('tab', name='Аватарка').click()
        expect(page.locator('.avatar-uploader .el-upload-list').first).to_have_class(re.compile('is-disabled'))
