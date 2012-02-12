<?php 

require_once "../../config/bootstrap.php"; 

use Comet\Web\JsHelper   as CometWebJsHelper,
    Comet\Web\CInterface as CometWebInterface
;

$cometWeb = new CometWebInterface();
$cometWeb->setKey(array('ali.hichem@mail.com'));
echo $cometWeb->readAsXml();