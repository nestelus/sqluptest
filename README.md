# sqluptest
Минисайт можно установить локально на виртуальную машину с помощью VirtualBox и Vagrant

После после запуска vagrant up нужно:
vagrant ssh
cd /app
composer install
php init  (выбрать 0)
php yii migrate (выбрать y)

готов к просмотру по адресам:
http://sqlup.dev - frontend
http://admin-sqlup.dev/ - админка

Вход в админку Admin:admin

БД доступна по адресу http://192.168.55.55:8003/
сервер - localhost,
user - root,
пароль - пустой,
база - yii2advanced

если не установлен плагин hostmanager, то в хост-файле необходимо вручную прописать:
192.168.55.55	sqlup.dev
192.168.55.55	admin-sqlup.dev

На виртуальной машине в dev окружении, письма при регистрации не отправляются, а складываются в виде файлов

Отправка писем на боевой среде работает.


