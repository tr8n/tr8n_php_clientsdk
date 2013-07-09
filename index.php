<?php require('tr8n/Tr8n.php'); ?>

<? echo tr("Hello World", "Greeting"); ?>

<br><br>

<? echo tr("You have {count||message} in your mailbox.", "", array("count" => 5)); ?>
