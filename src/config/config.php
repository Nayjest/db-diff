<?php
$default_connection = Config::get('database.default');
$db = Config::get("database.connections.$default_connection.database");
return [
    'db' => $db
];