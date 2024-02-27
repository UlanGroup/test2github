# Сервер

хост 94.228.121.80
имя пользователя ulanenko
пароль qi?54n_H^PvCGU



Как найти SSH ключ
Перейти по этому пути, файл будет называться id_rsa.pub
`/Users/mirage/.ssh/`


# Пароль от MySQL

пользователь `root` 
пароль `R*Y7a0#A`

пользователь `ulanenko` 
пароль `jND!!qp7`

пользователь `ulanenko2` 
пароль `jND!!qp7`


**Команда создания пользователя в базе данных на localhost**
CREATE USER 'ulanenko'@'localhost' IDENTIFIED BY 'jND!!qp7'; - для локального доступа
GRANT ALL PRIVILEGES ON *.* TO 'ulanenko'@'localhost';
FLUSH PRIVILEGES;



**Команда создания пользователя в базе данных с доступом с любых хостов**
CREATE USER 'ulanenko2'@'%' IDENTIFIED BY 'jND!!qp7'; - для локального доступа
GRANT ALL PRIVILEGES ON *.* TO 'ulanenko2'@'%';
FLUSH PRIVILEGES;

Для того, чтобы пользоваетль подключился к базе извне, нужно отредактировать конфигурационный файл
Путь: sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf
Прописать в строчке bind-address = 0.0.0.0 вместо bind-address = 127.0.0.1

После перезагрузить sudo systemctl restart mysql