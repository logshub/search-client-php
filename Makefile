test:
	./vendor/bin/phpunit --bootstrap vendor/autoload.php --testdox tests
cs-fixer:
	php-cs-fixer fix .
