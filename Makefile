DC=docker-compose -f docker-compose.yml
DC_TEST=docker-compose -f docker-compose-test.yml

start:
	$(DC) up -d
	$(DC) run --rm php dockerize -wait tcp://postgres:5432
	until $(DC) exec postgres sh /home/ping.sh; \
	do \
  		echo 'Database not created yet, sleeping 5 seconds.'; \
  		sleep 5; \
	done;
	echo 'Database created!'
	$(DC) run --rm php bin/console d:m:m -n

start-test: stop-test
	$(DC_TEST) up -d
	$(DC) run --rm php dockerize -wait tcp://postgres_test:5432; \
	until $(DC_TEST) exec postgres_test sh /home/ping.sh; \
	do \
		echo 'Database not created yet, sleeping 5 seconds.'; \
		sleep 5; \
	done;
	$(DC) run --rm php bin/console -e test d:m:m -n

build:
	$(DC) build php

ps:
	$(DC) ps

ps-test:
	$(DC_TEST) ps

cli:
	$(DC) exec php sh

integration-test:
	$(DC) run --rm php vendor/bin/phpunit tests/Integration

acceptance-test:
	$(DC) run --rm php vendor/bin/phpunit tests/Acceptance

stop:
	$(DC) rm -sfv

stop-test:
	$(DC_TEST) rm -sfv
