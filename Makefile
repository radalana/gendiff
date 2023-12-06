install:
	composer install
lint:
	composer exec --verbose phpcs -- --standard=PSR12 src tests
test:
	composer exec --verbose phpunit tests
test-coverage:
	XDEBUG_MODE=coverage composer exec --verbose phpunit tests -- --coverage-text
