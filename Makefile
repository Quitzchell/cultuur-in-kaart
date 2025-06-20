.DEFAULT_GOAL := dev

.PHONY: dev
dev:
	docker compose up development --build