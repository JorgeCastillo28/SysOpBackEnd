# Iniciando

Iniciando el proyecto

# Instalación

## Laravel

Instalación básica de laravel con composer

```jsx
composer install
```

## Base de datos

Solo es necesaria una base de datos con collation: **utf8mb4_unicode_ci**

## ENV

Recuerda cambiar el app key (`php artisan key:generate`) y el dominio en el cual se encuentra tu aplicación., en este ejemplo es **prueba-sysop.test**, es necesario en: APP_URL, PASSPORT_ENDPOINT.

    ```jsx
	APP_NAME=Laravel
	APP_ENV=local
	APP_KEY=
	APP_DEBUG=true
	APP_URL=http://localhost

	DEBUGBAR_ENABLED=false

	LOG_CHANNEL=stack

	DB_CONNECTION=mysql
	DB_HOST=127.0.0.1
	DB_PORT=3306
	DB_DATABASE=laravel
	DB_USERNAME=root
	DB_PASSWORD=

	BROADCAST_DRIVER=log
	CACHE_DRIVER=file
	QUEUE_CONNECTION=sync
	SESSION_DRIVER=file
	SESSION_LIFETIME=525600

	REDIS_HOST=127.0.0.1
	REDIS_PASSWORD=null
	REDIS_PORT=6379

	MAIL_MAILER=smtp
    MAIL_HOST=mailpit
    MAIL_PORT=1025
    MAIL_USERNAME=null
    MAIL_PASSWORD=null
    MAIL_ENCRYPTION=null
    MAIL_FROM_ADDRESS="hello@example.com"
    MAIL_FROM_NAME="${APP_NAME}"

	AWS_ACCESS_KEY_ID=
	AWS_SECRET_ACCESS_KEY=
	AWS_DEFAULT_REGION=us-east-1
	AWS_BUCKET=
	AWS_USE_PATH_STYLE_ENDPOINT=false

	PUSHER_APP_ID=
	PUSHER_APP_KEY=
	PUSHER_APP_SECRET=
	PUSHER_HOST=
	PUSHER_PORT=443
	PUSHER_SCHEME=https
	PUSHER_APP_CLUSTER=mt1

	MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
	MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

	SESSION_SECURE_COOKIE=true

	PASSPORT_ENDPOINT=https://localhost.test/oauth/token
	PASSPORT_CLIENT_ID=
	PASSPORT_CLIENT_SECRET=
    ```

## Migraciones

Se deben de correr las migraciones con el comando `php artisan migrate`

## Seeders

Se deben de correr los seeders con el comando `php artisan db:seed` y `php artisan db:seed --class=LaratrustSeeder` para la configuración de usuarios de laratrust

## API / Auth

El sistema utiliza tokens con laravel passport, para la instalación de esto se necesita ya contar con la base de datos y el .env

- Corremos `php artisan passport:install` el resultado esto te dara el token secreto del cliente, normalmente se usa el del cliente 2.

```php
Personal access client created successfully.
Client ID: 1
Client secret: ****************************************
Password grant client created successfully.
Client ID: 2
Client secret: ****************************************
```

- Copia el token del cliente 2 o Password Grant Client y ponlo en tu .env

## IMPORTANTE
- Para el caso de ya tener la base de datos con la información no será necesario crear los tokens de passport
- Para enviar los correos al agregar un nuevo usuario se utilizó mailtrap para pruebas, añadir la configuración necesaria en las variables de MAIL_ para poder enviar los correos
