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

You also need to generate the SSH keys for the JWT token generator, like so:

``` bash
$ mkdir -p config/jwt
$ openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
$ openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout  
```

## Creating the database
You can use doctrine commands via docker to create the database in MySQL server. 
```bash 
$ docker exec -it <api_container_name> bin/console doctrine:database:create
$ docker exec -it <api_container_name> bin/console doctrine:schema:create 
```

## Populating initial data
Two fixtures are provided at this point. One will create a test admin that you can use to hit api with a request for JWT token, 
and the other will create an initial set of questions for the survey.

These fixtures can be run from with the container, like so
```bash 
$ docker exec -it <api_container_name> bin/console doctrine:fixtures:load 
```