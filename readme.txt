Программа для процессинга файлов на сервере, с возможностью получения статуса обработки. Используется postgresql, symfony4, doctrine orm.

1. В конфиге nginx или apache установить webroot папку в /public относительно папки проекта.
2. Установить PHP 7.1.3 и выше и базу postgresql.
3. composer install (в процессе установки могут появится сообщения о недостающих модулях для php, их надо установить)
4. В файле .env поменять строку DATABASE_URL на актуальную (при необходимости создать нового user'a в postgresql)
5. выполнить из папки проекта ./bin/console doctrine:database:create && ./bin/console doctrine:schema:update --force

/upload

Принимает
upload[path] - строка путь к файлу
upload[param1] - строка с параметром 1
upload[param2] - строка с параметром 2
upload[param3] - строка с параметром 3

Возвращает json
response - строка success или failed (если failed, возвращает error_message вместо остальных полей)
uploadId - айди файла

/status

Принимает
id - айди файла

Возвращает json
response - строка success или failed (если failed, возвращает error_message вместо остальных полей)
status - статус загрузки
is_end - если загрузка завершена, возвращает 1


/download

Принимает
id - айди файла

Возвращает json
response - строка success или failed (если failed, возвращает error_message вместо остальных полей)
path - путь к файлу


/last

Возвращает json
response - строка success или failed (если failed, возвращает error_message вместо остальных полей)
items - последние 20 загрузок



Рабочий скрипт sh для обработки файлов лежит в корне handler.sh, в него первым аргументом передается путь к файлу,
все echo строки выводятся в базу данных и записываются как status.


