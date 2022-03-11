<?php
include ("lib/db.php");

$database = new DB();
$availableChannels = $database->getAvailableChannels();

echo $availableChannels;
