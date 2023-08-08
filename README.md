1. To set up the project locally:
````
docker build -t test-project .
docker-compose up -d
docker-compose exec php-fpm sh
/usr/local/bin/docker-php-ext-install pdo pdo_mysql
docker-compose restart phpfpm
````

2. Apply init.sql migration from ./migration folder
3. Reach in browser under http://phpfpm.local
