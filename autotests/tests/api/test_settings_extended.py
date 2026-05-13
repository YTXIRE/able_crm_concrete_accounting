from __future__ import annotations

import pytest

from autotests.helpers.api_client import ApiClient


@pytest.mark.api
class TestSettingsExtendedApi:
    def test_time_set_timezone_rejects_unknown_timezone_id(self, api_client: ApiClient) -> None:
        response = api_client.request(
            "POST",
            "/api/time/set-timezone",
            data=api_client.auth_form({"timezone_id": 999999999}),
        )
        payload = api_client.assert_business_code(response, 404)
        assert payload["message"]

    def test_settings_get_debt_matches_toggle_result(self, api_client: ApiClient) -> None:
        initial = bool(api_client.get_settings_debt())
        target = 0 if initial else 1

        changed = api_client.request(
            "POST",
            "/api/settings/set-debt",
            data=api_client.auth_form({"active": target}),
        )
        changed_payload = api_client.assert_business_code(changed, 200)
        assert changed_payload["message"]

        current = bool(api_client.get_settings_debt())
        assert current is bool(target)

        restore = api_client.request(
            "POST",
            "/api/settings/set-debt",
            data=api_client.auth_form({"active": 1 if initial else 0}),
        )
        restore_payload = api_client.assert_business_code(restore, 200)
        assert restore_payload["message"]

    def test_settings_set_debt_rejects_same_state(self, api_client: ApiClient) -> None:
        current = bool(api_client.get_settings_debt())
        response = api_client.request(
            "POST",
            "/api/settings/set-debt",
            data=api_client.auth_form({"active": 1 if current else 0}),
        )
        payload = api_client.assert_business_code(response, 400)
        assert payload["message"]

    def test_users_create_rejects_invalid_email(self, api_client: ApiClient) -> None:
        response = api_client.request(
            "POST",
            "/api/users/create",
            data=api_client.auth_form(
                {
                    "login": api_client.unique_name("bad_user"),
                    "email": "not-an-email",
                    "password": "secret1",
                    "is_demo": 1,
                }
            ),
        )
        payload = api_client.assert_business_code(response, 400)
        assert payload["message"]

    def test_users_create_rejects_short_password(self, api_client: ApiClient) -> None:
        response = api_client.request(
            "POST",
            "/api/users/create",
            data=api_client.auth_form(
                {
                    "login": api_client.unique_name("bad_user"),
                    "email": f"{api_client.unique_name('mail')}@example.com",
                    "password": "1234",
                    "is_demo": 1,
                }
            ),
        )
        payload = api_client.assert_business_code(response, 400)
        assert payload["message"]

    def test_users_create_rejects_duplicate_email_or_login(self, api_client: ApiClient) -> None:
        login = api_client.unique_name("dup_user")
        email = f"{login}@example.com"
        created = api_client.create_user(login, email)
        assert created["data"]["id"] > 0

        response = api_client.request(
            "POST",
            "/api/users/create",
            data=api_client.auth_form(
                {
                    "login": login,
                    "email": email,
                    "password": "secret1",
                    "is_demo": 1,
                }
            ),
        )
        payload = api_client.assert_business_code(response, 400)
        assert payload["message"]

    def test_users_update_info_rejects_invalid_email(self, api_client: ApiClient, aux_entities: dict) -> None:
        response = api_client.request(
            "PUT",
            "/api/users/update-info",
            json=api_client.auth_json(
                {
                    "id": aux_entities["user"]["id"],
                    "login": api_client.unique_name("upd_user"),
                    "email": "bad-email",
                    "is_demo": 1,
                }
            ),
        )
        payload = api_client.assert_business_code(response, 400)
        assert payload["message"]

    def test_users_change_password_rejects_short_password(self, api_client: ApiClient, aux_entities: dict) -> None:
        response = api_client.request(
            "PUT",
            "/api/users/change-password",
            json=api_client.auth_json({"id": aux_entities["user"]["id"], "password": "1234"}),
        )
        payload = api_client.assert_business_code(response, 400)
        assert payload["message"]

    def test_users_delete_rejects_admin_removal(self, api_client: ApiClient) -> None:
        response = api_client.request(
            "DELETE",
            "/api/users/delete",
            json=api_client.auth_json({"id": api_client.auth.user_id}),
        )
        payload = api_client.assert_business_code(response, 400)
        assert payload["message"]
