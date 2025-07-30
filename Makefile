start:
	symfony server:start -d

stop:
	symfony server:stop

install:
	composer install

cc:
	php bin/console cache:clear
