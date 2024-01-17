install:
	composer install
lint:
	composer exec --verbose phpcs -- --standard=PSR12 src tests
	composer exec --verbose phpstan
lint-fix:
	composer exec --verbose phpcbf -- --standard=PSR12 src tests
test:
	#composer exec --verbose phpunit tests
	composer exec --verbose phpunit -- --display-warnings tests
test-coverage:
	XDEBUG_MODE=coverage composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml  --coverage-filter=src
test-coverage-text:
	XDEBUG_MODE=coverage composer exec --verbose phpunit tests -- --coverage-text  --coverage-filter=src
