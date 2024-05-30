.PHONY: dev
dev:
	docker compose up development --build

.PHONY: stage
stage:
	docker compose up staging --build
