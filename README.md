# Football League API Symfony Project

This is a small API project written using Symfony 4.1 and Doctrine. The task was to build an API for a football league system. Acceptance criteria were roughly:
- 1 league contains many teams
- a team can belong to only 1 league
- at minimum a team should have a name and a strip 
- API should provide a list of teams belonging to a league
- API should allow a league to be deleted
- API should allow a team to be created
- API should allow a team to be edited

## Installation

- clone the project ```git clone git@github.com:jpagel/football.git```
- change directory ```cd football```
- install the dependencies ```composer install```
- create a mysql user and database, and edit the DATABASE_URL string in .env accordingly
- build the database schema ```./bin/console doctrine:schema:create```
- run the fixtures ```./bin/console doctrine:fixtures:load```
- configure the Lexik JWT keys as explained in https://github.com/lexik/LexikJWTAuthenticationBundle/blob/master/Resources/doc/index.md#installation
- run the dev server ```./bin/console server:run```
- you should get a happy message by visiting `http://localhost:8000\server-health`
- make a new phpunit.xml by copying the phpunit.xml.dist
- edit phpunit.xml to make a database connection by adding eg ```<env name="DATABASE_URL" value="mysql://symfony_user:symfony_db_password@127.0.0.1/db_name" />``` in the `<php>` section
- with the local server running, run the tests ```./vendor/bin/simple-phpunit```

## TODO

A few obvious things that still need tidying up:

- There is no clear distinction between the test and dev environments. The functional tests (in `tests/Controller`) rely on a running instance of the dev server. It would be better if the server were running in test env and the tests wrote their fixtures to the test db.
- ~~There is some copied-and-pasted logic in the controllers (to do with generating 404s when entities are not found) which should be removed to a utility method.~~
- ~~There is logic in the League controller for detaching teams. This makes the controller a little too fat for comfort. It should be removed to a utility or repository method.~~
- The apib and the functional tests cover only happy paths. There should be tests and documentation also for cases where slugs are not found or db updates fail.
- There is no api resource for changing the league to which a team belongs.
- There is no api resource for creating a league.
- There is no user management system.
