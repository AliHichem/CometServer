<?php 

require_once "../../config/bootstrap.php"; 

use Comet\Web\JsHelper   as CometWebJsHelper,
    Comet\Web\CInterface as CometWebInterface
;

$cometWeb = new CometWebInterface();
$cometWeb->setKey(array('ali.hichem@mail.com'));

if (isset($_POST['comet-text']))
{
    $cometWeb->write($_POST['comet-text']);
}
elseif (isset($_POST['frm-comet-refresh']) && $_POST['frm-comet-refresh'] == "true")
{
    $cometWeb->refresh();
}
?>
<html>
    <head>
        <title>CometServer - example:  Comet notifier</title>
        <link href="css/main.css" type="text/css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <h1>Comet notification updater</h1>
            <fieldset>
                <legend>Write/Update message</legend>
                <form action="" method="POST">
                    <textarea name="comet-text" id="comet-text" rows="5" cols="50">
                        <?php if (isset($_POST['comet-text'])) : ?>
                            <?php echo $_POST['comet-text']; ?>
                        <?php else: ?>
                            <table>
                                <tr>
                                    <td>
                                        <?php 
                                        $email = "ali.hichem@mail.com";
                                        $default = "https://github.com/AliHichem";
                                        $size = 40;
                                        $grav_url = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . 
                                                    "?d=" . urlencode( $default ) . "&s=" . $size;
                                        ?>
                                        <a href="<?php echo $default ?>">
                                            <img src="<?php echo $grav_url; ?>" alt="" />
                                        </a>
                                    </td>
                                    <td valign="top">
                                        <div class="tooltip">
                                            <b><u>Comet:</u></b><br/>
                                            the jGrowl message notifier is working! 
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        <?php endif; ?>
                    </textarea>
                    <input type="submit"/>
                </form>
            </fieldset>
            <fieldset>
                <legend>Force refresh</legend>
                <form action="" method="POST">
                    <input name="frm-comet-refresh" type="hidden" value="true" />
                    <input type="submit" value="refresh" />
                </form>
            </fieldset>    
        </div>
    </body>
</html>
