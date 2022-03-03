<?php
require_once(ROOT_PATH .'./Controllers/contactController.php');
$contacts = new ContactController();
$contacts->delete();
