
Необходимо будет создать бд с названием - task (или своим, 
только изменить в файле .env) : <br> DB_DATABASE=свое <br> DB_USERNAME=свое <br> DB_PASSWORD=свое

Также npm : npm install -g npm

И запустить сиды с миграцией: php artisan migrate --seed

Логины и пароли можно посмотреть тут : database/seeders/DatabaseSeeder.php
<br>
Пароль от руководителей : admin <br>
Пароль от пользователей : test


