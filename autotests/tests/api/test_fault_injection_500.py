from __future__ import annotations

from pathlib import Path
from typing import Any

import pytest

from autotests.helpers.api_client import ApiClient
from autotests.tests.api.test_negative_common import GET_ENDPOINT_SPECS
from autotests.tests.api.test_negative_mutation_common import MUTATION_SPECS, _request_with_mode


@pytest.mark.api
@pytest.mark.fault500
class TestFaultInjection500:
    @pytest.mark.parametrize("_label,path,extra_factory", GET_ENDPOINT_SPECS)
    def test_get_endpoints_return_500_when_db_is_unavailable(
            self,
            _label: str,
            path: str,
            extra_factory,
            api_client: ApiClient,
            seeded_data: dict[str, Any],
            db_outage,
    ) -> None:
        response = api_client.request("GET", path, params=api_client.auth_query(extra_factory(seeded_data)))
        payload = api_client.assert_business_code(response, 500)
        assert payload["message"]

    @pytest.mark.parametrize("_label,method,_wrong_method,path,payload_mode,payload_factory", MUTATION_SPECS)
    def test_mutation_endpoints_return_500_when_db_is_unavailable(
            self,
            _label: str,
            method: str,
            _wrong_method: str,
            path: str,
            payload_mode: str,
            payload_factory,
            api_client: ApiClient,
            seeded_data: dict[str, Any],
            aux_entities: dict[str, Any],
            tiny_png: Path,
            db_outage,
    ) -> None:
        payload = payload_factory(api_client, seeded_data, aux_entities)
        response = _request_with_mode(api_client, method, path, payload_mode, payload, tiny_png, token=None)
        result = api_client.assert_business_code(response, 500)
        assert result["message"]
