from __future__ import annotations

import pytest
from playwright.sync_api import expect

from autotests.page_objects.app_shell_page import AppShellPage
from autotests.page_objects.vendors_page import VendorsPage


@pytest.mark.ui
def test_vendor_icon_picker_pagination_loads_next_page(page, settings, auth_bundle) -> None:
    shell = AppShellPage(page, settings.frontend_url)
    shell.bootstrap_session(auth_bundle, "/vendors", "5")
    shell.open_route("/vendors")
    shell.assert_heading("Поставщики")

    vendors_page = VendorsPage(page)
    vendors_page.open_create_dialog()
    vendors_page.open_icon_picker()

    pagination = page.get_by_role("dialog", name="Иконки").locator('[role="pagination"]').first
    expect(pagination).to_be_visible()

    with page.expect_response(lambda response: "/api/icons/get-all" in response.url and "offset=48" in response.url) as next_page:
        vendors_page.go_to_next_icons_page()

    assert next_page.value.ok
