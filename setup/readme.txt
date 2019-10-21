Описанное ниже годится для среды Windows. *nix пользователи проблем не должны испытать.

Установите и настройте:
postgres
php
nginx

Примеры конфигов для php и nginx под windows: nginx.conf.sample php.ini.sample 
Для запуска nginx и php-cgi под Windows удобно использовать шедулер и файлы RunHiddenConsole.exe.windows start_server.cmd.windows shutdown_server.cmd.windows
Для создания схемы данных в Postgres запустите:
psql -U postgres -f somedb.sql postgres
psql -U postgres -f somedb_data.sql somedb

Для первоначальной инициализации yii2 приложения запустите init.bat
Где-то возможно понадобится инициализировать и/или обновить Composer. Как это сделать без phpstorm я не знаю и пока знать не хочу.
После запуска "yii.bat test/init-admin" в приложении будет создан первый пользователь admin с паролем 123456

Примеры конфигурации для разных частей приложения: main-local.php.*
Разложите их по соответствующим каталогам и модифицируйте по желанию ( например: cp main-local.php.backend somedb\backend\config\main-local.php )
Не забудьте поменять cookieValidationKey!

Не забудьте сменить пароли:
В базе для: somedb_app_user, somedb_app_admin
В приложении для: admin
