Symfony Standard Edition
========================

Instalation:

install dependencies:
```
composer install
```

example database parameters:

```
database_host: 127.0.0.1
database_port: null
database_name: blog
database_user: root
database_password: null
```

prepare database:
```
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
```

administration:

path: `/web/app_dev.php/admin/`
username: admin
password: pass

