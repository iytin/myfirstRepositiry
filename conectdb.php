<? $dbserver= "cybers.mysql.ukraine.com.ua";     // Имя хоста MySQL-сервера
$db = "cybers_svadbagol";      // Имя БД
$dbuser = "cybers_svadbagol";       // Имя пользователя для доступа $db
$dbpassword = "cybers_svadbagol";   // Пароль пользователя
/* Подключаемся к MySQL-серверу */
$link = @mysql_connect ($dbserver, $dbuser, $dbpassword);
if (! $link){
    echo ( "The site temporarily doesn't works!" );
exit();
}
mysql_query("SET NAMES 'utf8'");
/* Выбор БД */
if (!mysql_select_db ($db, $link) ){
exit ();
}

?>