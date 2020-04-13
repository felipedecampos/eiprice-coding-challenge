# eiprice-coding-challenge
Coding challenge - Web scrapping

## ** Test specification ** 
[Link to the test specification](https://github.com/felipedecampos/eiprice-coding-challenge/tree/master/docs/EIPRICE_AVALIACAO_TECNICA.pdf)

## ** What has been done **

First, you will need to install the application environment with docker-compose

[Go to "How to install the project environment"](https://github.com/felipedecampos/eiprice-coding-challenge#how-to-install-the-project-environment)

Then you will need to go to the welcome application page.

Please open your browser and enter with the application url:

http://eiprice-coding-challenge.local/

After that you will see the **DOWNLOAD REPORT** button on the page

You just need to press the button and the report will be downloaded

## ** PHP Standards Recommendations **

To validate the code for consistency with a coding standard, go to the **project folder** and run the commands:

**PSR-1**
```shell
$ vendor/bin/phpcs --standard=PSR1 --extensions=php --ignore=*/database/*,*/resources/*,*/storage/*,*/vendor/*,*/public/index.php,*/tests/bootstrap.php,*/bootstrap/cache/* .
```

**PSR-2**

```shell
$ vendor/bin/phpcs --standard=PSR2 --extensions=php --ignore=*/database/*,*/resources/*,*/storage/*,*/vendor/*,*/public/index.php,*/tests/bootstrap.php,*/bootstrap/cache/* .
```

**PSR-12**

```shell
$ vendor/bin/phpcs --standard=PSR12 --extensions=php --ignore=*/database/*,*/resources/*,*/storage/*,*/vendor/*,*/public/index.php,*/tests/bootstrap.php,*/bootstrap/cache/* .
```

#### How to install the project environment

#### requirements:

- **docker** version >**17.05.0-ce**
- **docker-compose** version >**1.19.0**
- **git** version >**2.7.4**

To know your docker version run:

```shell
$ docker -v
```

To know your docker-composer version run:

```shell
$ docker-compose -v
```

To know your git version run:

```shell
$ git --version
```

**To install the environment, follow the steps below:**

Open your **Linux** console and enter into the workspace you want to clone the project.

Now you need to clone the **project**:

```shell
$ git clone https://github.com/felipedecampos/eiprice-coding-challenge.git
```

Then you need to enter into the project folder **eiprice-coding-challenge**:

```shell
$ cd eiprice-coding-challenge
```

Now you need to create the **.env** file:

```shell
$ cp .env.example .env
```

**Note: Please, make sure you are not using the same IP and PORT (PHP and NGINX) mentioned into the .env file # Docker block**

If you are already using the IP or PORT, please, replace for another one.

The next thing you should do is set your application key to a random string. Typically, this string should be 32 characters long. The key can be set in the **.env** file.

After that, you will install the environment with **docker-compose**:

**Note: Please, make sure you have docker and docker-compose already installed in your computer**

```shell
$ docker-compose up -d
```

When the containers are already running, you will need to install composer:

```shell
$ docker exec eiprice-php /bin/bash -c "composer install"
```

After that you will need to migrate the database:
```shell
$ docker exec eiprice-php /bin/bash -c "php artisan migrate"
$ docker exec eiprice-php /bin/bash -c "php artisan db:seed"
```

Finally you can just generate the composer optimized file:

```shell
$ docker exec eiprice-php /bin/bash -c "composer dump-autoload -o"
```