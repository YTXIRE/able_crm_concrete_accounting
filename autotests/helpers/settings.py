from __future__ import annotations

import os
from dataclasses import dataclass


def _bool_env(name: str, default: bool) -> bool:
    value = os.getenv(name)
    if value is None:
        return default
    return value.strip().lower() in {"1", "true", "yes", "on"}


@dataclass(frozen=True)
class TestSettings:
    __test__ = False

    frontend_url: str
    api_url: str
    admin_login: str
    admin_password: str
    headless: bool
    slow_mo_ms: int
    timeout_seconds: int
    ui_timeout_ms: int

    @classmethod
    def from_env(cls) -> "TestSettings":
        return cls(
            frontend_url=os.getenv("FRONTEND_URL", "http://localhost:8050").rstrip("/"),
            api_url=os.getenv("API_URL", "http://localhost:8020").rstrip("/"),
            admin_login=os.getenv("ADMIN_LOGIN", "admin"),
            admin_password=os.getenv("ADMIN_PASSWORD", "admin"),
            headless=_bool_env("HEADLESS", True),
            slow_mo_ms=int(os.getenv("SLOW_MO_MS", "0")),
            timeout_seconds=int(os.getenv("TIMEOUT_SECONDS", "30")),
            ui_timeout_ms=int(os.getenv("UI_TIMEOUT_MS", "15000")),
        )
