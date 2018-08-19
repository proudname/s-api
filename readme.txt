1. В конфиге nginx или apache установить webroot папку в /public относительно папки проекта.
2. Установить PHP 7.1.3 и выше и базу postgresql.
3. composer install (в процессе установки могут появится сообщения о недостающих модулях для php, их надо установить)
4. В файле .env поменять строку DATABASE_URL на актуальную (при необходимости создать нового user'a в postgresql)
5. выполнить из папки проекта ./bin/console doctrine:database:create && ./bin/console doctrine:schema:update --force

/upload