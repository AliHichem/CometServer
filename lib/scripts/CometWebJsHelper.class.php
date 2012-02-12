<?php

/**
 * CometWebJsHelper
 *
 * @package     CometServer
 * @version     1.0.0
 * @author      Ali hichem <ali.hichem@mail.com>
 */
class CometWebJsHelper
{

    /**
     * Static class constructor
     * 
     * @return self 
     */
    public static function create()
    {
        return new self();
    }

    /**
     * Render js tag
     *  note: it's recommended to call this helper in the Html head tag
     * 
     * @param type $url
     * @param type $key
     * @param type $function 
     * 
     * @return void
     */
    public function renderJsTag($url, $function)
    {
        $script = <<<EOF
<script type="text/javascript">
String.prototype.trim = function() {
	return this.replace(/^\s+|\s+$/g,"");
}
var time_previous = null ;
var comet = function() {
    $.ajax({
        type : 'Get',
        url  : '{$url}',
        async : true,
        cache : false,
        success : function(xml) {
            data = $(xml).find('data').text();
            time = $(xml).find('time').text();
            var clean = data.replace('value:','').trim();
            if( data.match('value:') && clean.length > 0 && (time_previous != time) )
            {
                comet_data = clean;
                time_previous = time;
                {$function}(comet_data)
            }
            setTimeout('comet()', 50);
        },
        error : function(XMLHttpRequest, textstatus, error) { 
            alert(error);
            setTimeout('comet()', 15000);
        }		
    });
}
$(document).ready(function(){
    comet();
});
</script>
EOF;
        echo $script;
    }

}
