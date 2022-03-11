<?php
include ("lib/db.php");

$database = new DB();
$contact = $database->getContact($_REQUEST['channel'], $_REQUEST['location']);

echo $contact;
