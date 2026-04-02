<?php
$mysqli = new mysqli('localhost', 'malik123', '123', 'wordpress_community_malik');

if ($mysqli->connect_error) {
    die('Fout bij verbinding: ' . $mysqli->connect_error);
}
echo 'Databaseconnectie gelukt!';
?>