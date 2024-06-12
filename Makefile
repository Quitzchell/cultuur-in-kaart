.PHONY: dev
dev:
	docker compose up development --build

.PHONY: stage
stage:
	docker compose build staging --no-cache
	docker compose up staging
