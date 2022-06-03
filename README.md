# Filmfy Api

- URL: http://filmfy-api.ddns.net/

## 1. Como es Filmfy Api

Además de la aplicación tenemos la parte interna de su funcionamiento dinámico utilizadno una Base de Datos y una API Propia

- Toda la información de Filmfy proviene de nuestra base de datos y nuestra api creada por nosotros mismos. 
    - Para tema Base de datos hemos utilizado MySQL utilizando PHPMyAdmin
    - Todo tema de back está programado en Laravel
    - Plantillas hechas con Blade
    - Utilizamos Backpack para construir y personalizar paneles de administración usando Laravel.

## 2. Database

## Hosting
    - Para el Tema de hospedaje de Filmfy hemos Usado una LAMP de Bitnami
      ***Bitnami es una biblioteca de instaladores o paquetes de software para aplicaciones web y montones de software, así como aparatos virtuales.
        - IP Bitnami: 13.38.179.141.
    

## Database .env


APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=7777
DB_DATABASE=filmfy-dev
DB_USERNAME=jmateo
DB_PASSWORD=Hzd2qRJOp@.e)K1u 

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DRIVER=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1

MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"




