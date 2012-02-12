<?php 

require_once "../../config/bootstrap.php"; 

use Comet\Web\JsHelper as CometWebJsHelper;

?>
<html>
    <head>
        <title>CometServer - example:  jGrowl notifier</title>
        <script src="js/jquery.js"></script>
        <script src="js/jquery.jgrowl.js"></script>
        <link href="css/jquery.jgrowl.css" type="text/css" rel="stylesheet">
        <link href="css/main.css" type="text/css" rel="stylesheet">
        <script type="text/javascript">
            function notify(data)
            {
                var jGrowl_defaults = { life: 5000, animationOpen : { opacity:'show' } , animationClose : {opacity:'hide'}} ;
                $.jGrowl(data,jGrowl_defaults);
            }
        </script>        
        <?php CometWebJsHelper::create()->renderJsTag("read.php", "notify"); ?>
    </head>
    <body>
        <div class="container">
            
            <h1><?php 
                $email = "ali.hichem@mail.com";
                $default = "https://github.com/AliHichem";
                $size = 40;
                $grav_url = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?d=" . urlencode( $default ) . "&s=" . $size;
                ?>
                <a href="<?php echo $default ?>">
                    <img src="<?php echo $grav_url; ?>" alt="" />
                </a>
                /
                CometServer - example : jGrowl notifier
            </h1>
            <p>
                
            </p>
        </div>
    </body>
</html>
