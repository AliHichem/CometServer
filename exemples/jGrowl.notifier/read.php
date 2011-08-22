<?php

require_once "../../lib/CometAutoloader.class.php";
$cometWeb = new CometWebInterface();
$cometWeb->setKey(array('ali.hichem@mail.com'));
echo $cometWeb->readAsXml();