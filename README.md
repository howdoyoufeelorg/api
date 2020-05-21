HowDoYouFeel.org - API component
=========================

API for HowDoYouFeel.org - works in conjunction with other components of the project, 
but it can be developed and used on it's own. Based on Symfony 4.4, and api-project. 

See documentation at

https://symfony.com/

https://api-platform.com/

## Configuration
If you're running this as a part of the whole project, the MySQL container will be loaded, and env variables will automatically 
point the api to it.

If you want to run the API with an arbitrary MySQL server, you need to configure the DSN for the database connection, in the .env file
``` dotenv
DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7
```

## Dependencies
As usual, you need install dependencies via composer, like so:

``` bash
$ composer install 
```
The proces should also create the SSH keys for JWT token. You should see the SSH keys in api/config/jwt. If they're not created, there's a procedure here

https://github.com/lexik/LexikJWTAuthenticationBundle/blob/master/Resources/doc/index.md#generate-the-ssh-keys

## Creating the database
The commands to create the database are:
```bash 
$ bin/console doctrine:database:create
$ bin/console doctrine:schema:create 
```
Depending on your setup, you can execute these from local shell, or from within the docker container:

```bash 
$ docker exec -it <api_container_name> bin/console doctrine:database:create
$ docker exec -it <api_container_name> bin/console doctrine:schema:create 
```

## Populating initial data
Two fixtures are provided at this point. One will create a test admin that you can use to hit api with a request for JWT token, 
and the other will create an initial set of questions for the survey.

You can run the fixtures from the console:

```bash 
$ bin/console doctrine:fixtures:load 
```

These fixtures can be run from with the container, like so
```bash 
$ docker exec -it <api_container_name> bin/console doctrine:fixtures:load 
```

## Test from a browser
A very simple test would be to access this url in your browser:

http://localhost:8080/api/docs

It should give you the swagger-api documentation.
