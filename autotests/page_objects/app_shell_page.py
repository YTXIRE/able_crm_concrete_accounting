from __future__ import annotations

import json
from typing import Any

from playwright.sync_api import Page, expect


class AppShellPage:
    def __init__(self, page: Page, frontend_url: str):
        self.page = page
        self.frontend_url = frontend_url

    def bootstrap_session(self, auth_bundle: dict[str, Any], current_link: str, current_menu: str) -> None:
        payload = {
            "token": auth_bundle["token"],
            "user_id": str(auth_bundle["user_id"]),
            "is_demo": str(auth_bundle["is_demo"] if auth_bundle["is_demo"] is not None else 0),
            "user_data": json.dumps(auth_bundle["user_data"], ensure_ascii=False),
            "current_link": current_link,
            "current_menu": current_menu,
        }
        self.page.add_init_script(
            script=f"""
            (() => {{
                const payload = {json.dumps(payload, ensure_ascii=False)};
                localStorage.setItem('crm_token', payload.token);
                localStorage.setItem('user_id', payload.user_id);
                localStorage.setItem('is_demo', payload.is_demo);
                localStorage.setItem('user_data', payload.user_data);
                localStorage.setItem('currentLink', payload.current_link);
                localStorage.setItem('currentMenu', payload.current_menu);
            }})();
            """
        )

    def open_route(self, route: str) -> None:
        self.page.goto(f"{self.frontend_url}/#{route}")

    def assert_heading(self, heading: str) -> None:
        expect(self.page.get_by_role("heading", name=heading)).to_be_visible()

    def assert_header_visible(self) -> None:
        expect(self.page.locator(".logo")).to_have_text("Able CRM")

    def click_settings_header_button(self) -> None:
        self.page.locator(".exit .el-button").nth(1).click()

    def click_user_settings_header_button(self) -> None:
        self.page.locator(".login_text").click()

    def click_logout_header_button(self) -> None:
        self.page.locator(".exit .el-button").nth(2).click()

    def read_local_storage(self, key: str):
        return self.page.evaluate("key => localStorage.getItem(key)", key)
