from __future__ import annotations

import pytest

from autotests.helpers.api_client import ApiClient
from autotests.helpers.settings import TestSettings


@pytest.mark.api
class TestAuthExtendedApi:
    def test_login_rejects_missing_login(self, settings: TestSettings) -> None:
        client = ApiClient(settings)
        response = client.request("POST", "/api/auth/login", data={"password": settings.admin_password})
        payload = client.assert_business_code(response, 400)
        assert payload["message"]

    def test_login_rejects_missing_both_fields(self, settings: TestSettings) -> None:
        client = ApiClient(settings)
        response = client.request("POST", "/api/auth/login", data={})
        payload = client.assert_business_code(response, 400)
        assert payload["message"]

    def test_login_rejects_too_long_login(self, settings: TestSettings) -> None:
        client = ApiClient(settings)
        response = client.request(
            "POST",
            "/api/auth/login",
            data={"login": "a" * 101, "password": settings.admin_password},
        )
        payload = client.assert_business_code(response, 400)
        assert payload["message"]

    def test_login_rejects_too_long_password(self, settings: TestSettings) -> None:
        client = ApiClient(settings)
        response = client.request(
            "POST",
            "/api/auth/login",
            data={"login": settings.admin_login, "password": "a" * 101},
        )
        payload = client.assert_business_code(response, 400)
        assert payload["message"]

    def test_login_rejects_unknown_user(self, settings: TestSettings) -> None:
        client = ApiClient(settings)
        response = client.request(
            "POST",
            "/api/auth/login",
            data={"login": "definitely_unknown_user", "password": "secret1"},
        )
        payload = client.assert_business_code(response, 404)
        assert payload["message"]

    def test_login_returns_existing_token_for_same_user(self, settings: TestSettings) -> None:
        client = ApiClient(settings)
        first = client.login()
        second = client.login()
        assert first.token == second.token
        assert first.user_id == second.user_id

    def test_logout_rejects_too_long_token(self, settings: TestSettings) -> None:
        client = ApiClient(settings)
        response = client.request(
            "POST",
            "/api/auth/logout",
            data={"token": "a" * 101, "user_id": 1},
        )
        payload = client.assert_business_code(response, 400)
        assert payload["message"]

    def test_logout_rejects_invalid_user_id_for_valid_token(self, settings: TestSettings) -> None:
        client = ApiClient(settings)
        auth = client.login()
        response = client.request(
            "POST",
            "/api/auth/logout",
            data={"token": auth.token, "user_id": auth.user_id + 999},
        )
        payload = client.assert_business_code(response, 404)
        assert payload["message"]

    def test_logout_invalidates_token_for_following_requests(self, settings: TestSettings) -> None:
        client = ApiClient(settings)
        auth = client.login()
        logout_payload = client.logout()
        assert logout_payload["message"]

        response = client.request(
            "GET",
            "/api/users/get-info",
            params={"token": auth.token, "user_id": auth.user_id},
        )
        payload = client.assert_business_code(response, 404)
        assert payload["message"]

    def test_login_after_logout_returns_new_token(self, settings: TestSettings) -> None:
        client = ApiClient(settings)
        first = client.login()
        client.logout()
        second = client.login()
        assert first.token != second.token
        assert first.user_id == second.user_id

    def test_logout_accepts_authorization_header(self, settings: TestSettings) -> None:
        client = ApiClient(settings)
        auth = client.login()
        response = client.session.post(
            f"{client.settings.api_url}/api/auth/logout",
            data={"user_id": auth.user_id},
            headers={"Authorization": f"Bearer {auth.token}"},
            timeout=client.settings.timeout_seconds,
        )
        payload = client.assert_business_code(response, 200)
        assert payload["message"]
