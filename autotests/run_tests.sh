#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(cd "$ROOT_DIR/.." && pwd)"
VENV_PYTHON="$ROOT_DIR/.venv/bin/python"

BUILD_IMAGES=0
if [[ "${1:-}" == "--build" ]]; then
    BUILD_IMAGES=1
    shift
fi

if [[ ! -x "$VENV_PYTHON" ]]; then
    echo "[autotests] Python venv was not found at $VENV_PYTHON"
    echo "[autotests] Install dependencies first: python3 -m venv autotests/.venv && autotests/.venv/bin/pip install -r autotests/requirements.txt"
    exit 1
fi

if [[ "$BUILD_IMAGES" -eq 1 ]]; then
    "$ROOT_DIR/scripts/prepare_docker_test_env.sh" --build
else
    "$ROOT_DIR/scripts/prepare_docker_test_env.sh"
fi

cd "$PROJECT_DIR"

exec "$VENV_PYTHON" -m pytest -c "$ROOT_DIR/pytest.ini" "$ROOT_DIR/tests" "$@"
