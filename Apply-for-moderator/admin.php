<?php
require_once( dirname(__FILE__).'/form.lib.php' );

define( 'PHPFMG_USER', "chinemeremalozie17@gmail.com" ); // must be a email address. for sending password to you.
define( 'PHPFMG_PW', "eab152" );

?>
<?php
/**
 * GNU Library or Lesser General Public License version 2.0 (LGPLv2)
*/

# main
# ------------------------------------------------------
error_reporting( E_ERROR ) ;
phpfmg_admin_main();
# ------------------------------------------------------




function phpfmg_admin_main(){
    $mod  = isset($_REQUEST['mod'])  ? $_REQUEST['mod']  : '';
    $func = isset($_REQUEST['func']) ? $_REQUEST['func'] : '';
    $function = "phpfmg_{$mod}_{$func}";
    if( !function_exists($function) ){
        phpfmg_admin_default();
        exit;
    };

    // no login required modules
    $public_modules   = false !== strpos('|captcha||ajax|', "|{$mod}|");
    $public_functions = false !== strpos('|phpfmg_ajax_submit||phpfmg_mail_request_password||phpfmg_filman_download||phpfmg_image_processing||phpfmg_dd_lookup|', "|{$function}|") ;   
    if( $public_modules || $public_functions ) { 
        $function();
        exit;
    };
    
    return phpfmg_user_isLogin() ? $function() : phpfmg_admin_default();
}

function phpfmg_ajax_submit(){
    $phpfmg_send = phpfmg_sendmail( $GLOBALS['form_mail'] );
    $isHideForm  = isset($phpfmg_send['isHideForm']) ? $phpfmg_send['isHideForm'] : false;

    $response = array(
        'ok' => $isHideForm,
        'error_fields' => isset($phpfmg_send['error']) ? $phpfmg_send['error']['fields'] : '',
        'OneEntry' => isset($GLOBALS['OneEntry']) ? $GLOBALS['OneEntry'] : '',
    );
    
    @header("Content-Type:text/html; charset=$charset");
    echo "<html><body><script>
    var response = " . json_encode( $response ) . ";
    try{
        parent.fmgHandler.onResponse( response );
    }catch(E){};
    \n\n";
    echo "\n\n</script></body></html>";

}


function phpfmg_admin_default(){
    if( phpfmg_user_login() ){
        phpfmg_admin_panel();
    };
}



function phpfmg_admin_panel()
{    
    if( !phpfmg_user_isLogin() ){
        exit;
    };

    phpfmg_admin_header();
    phpfmg_writable_check();
?>    
<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td valign=top style="padding-left:280px;">

<style type="text/css">
    .fmg_title{
        font-size: 16px;
        font-weight: bold;
        padding: 10px;
    }
    
    .fmg_sep{
        width:32px;
    }
    
    .fmg_text{
        line-height: 150%;
        vertical-align: top;
        padding-left:28px;
    }

</style>

<script type="text/javascript">
    function deleteAll(n){
        if( confirm("Are you sure you want to delete?" ) ){
            location.href = "admin.php?mod=log&func=delete&file=" + n ;
        };
        return false ;
    }
</script>


<div class="fmg_title">
    1. Email Traffics
</div>
<div class="fmg_text">
    <a href="admin.php?mod=log&func=view&file=1">view</a> &nbsp;&nbsp;
    <a href="admin.php?mod=log&func=download&file=1">download</a> &nbsp;&nbsp;
    <?php 
        if( file_exists(PHPFMG_EMAILS_LOGFILE) ){
            echo '<a href="#" onclick="return deleteAll(1);">delete all</a>';
        };
    ?>
</div>


<div class="fmg_title">
    2. Form Data
</div>
<div class="fmg_text">
    <a href="admin.php?mod=log&func=view&file=2">view</a> &nbsp;&nbsp;
    <a href="admin.php?mod=log&func=download&file=2">download</a> &nbsp;&nbsp;
    <?php 
        if( file_exists(PHPFMG_SAVE_FILE) ){
            echo '<a href="#" onclick="return deleteAll(2);">delete all</a>';
        };
    ?>
</div>

<div class="fmg_title">
    3. Form Generator
</div>
<div class="fmg_text">
    <a href="http://www.formmail-maker.com/generator.php" onclick="document.frmFormMail.submit(); return false;" title="<?php echo htmlspecialchars(PHPFMG_SUBJECT);?>">Edit Form</a> &nbsp;&nbsp;
    <a href="http://www.formmail-maker.com/generator.php" >New Form</a>
</div>
    <form name="frmFormMail" action='http://www.formmail-maker.com/generator.php' method='post' enctype='multipart/form-data'>
    <input type="hidden" name="uuid" value="<?php echo PHPFMG_ID; ?>">
    <input type="hidden" name="external_ini" value="<?php echo function_exists('phpfmg_formini') ?  phpfmg_formini() : ""; ?>">
    </form>

		</td>
	</tr>
</table>

<?php
    phpfmg_admin_footer();
}



function phpfmg_admin_header( $title = '' ){
    header( "Content-Type: text/html; charset=" . PHPFMG_CHARSET );
?>
<html>
<head>
    <title><?php echo '' == $title ? '' : $title . ' | ' ; ?>PHP FormMail Admin Panel </title>
    <meta name="keywords" content="PHP FormMail Generator, PHP HTML form, send html email with attachment, PHP web form,  Free Form, Form Builder, Form Creator, phpFormMailGen, Customized Web Forms, phpFormMailGenerator,formmail.php, formmail.pl, formMail Generator, ASP Formmail, ASP form, PHP Form, Generator, phpFormGen, phpFormGenerator, anti-spam, web hosting">
    <meta name="description" content="PHP formMail Generator - A tool to ceate ready-to-use web forms in a flash. Validating form with CAPTCHA security image, send html email with attachments, send auto response email copy, log email traffics, save and download form data in Excel. ">
    <meta name="generator" content="PHP Mail Form Generator, phpfmg.sourceforge.net">

    <style type='text/css'>
    body, td, label, div, span{
        font-family : Verdana, Arial, Helvetica, sans-serif;
        font-size : 12px;
    }
    </style>
</head>
<body  marginheight="0" marginwidth="0" leftmargin="0" topmargin="0">

<table cellspacing=0 cellpadding=0 border=0 width="100%">
    <td nowrap align=center style="background-color:#024e7b;padding:10px;font-size:18px;color:#ffffff;font-weight:bold;width:250px;" >
        Form Admin Panel
    </td>
    <td style="padding-left:30px;background-color:#86BC1B;width:100%;font-weight:bold;" >
        &nbsp;
<?php
    if( phpfmg_user_isLogin() ){
        echo '<a href="admin.php" style="color:#ffffff;">Main Menu</a> &nbsp;&nbsp;' ;
        echo '<a href="admin.php?mod=user&func=logout" style="color:#ffffff;">Logout</a>' ;
    }; 
?>
    </td>
</table>

<div style="padding-top:28px;">

<?php
    
}


function phpfmg_admin_footer(){
?>

</div>

<div style="color:#cccccc;text-decoration:none;padding:18px;font-weight:bold;">
	:: <a href="http://phpfmg.sourceforge.net" target="_blank" title="Free Mailform Maker: Create read-to-use Web Forms in a flash. Including validating form with CAPTCHA security image, send html email with attachments, send auto response email copy, log email traffics, save and download form data in Excel. " style="color:#cccccc;font-weight:bold;text-decoration:none;">PHP FormMail Generator</a> ::
</div>

</body>
</html>
<?php
}


function phpfmg_image_processing(){
    $img = new phpfmgImage();
    $img->out_processing_gif();
}


# phpfmg module : captcha
# ------------------------------------------------------
function phpfmg_captcha_get(){
    $img = new phpfmgImage();
    $img->out();
    //$_SESSION[PHPFMG_ID.'fmgCaptchCode'] = $img->text ;
    $_SESSION[ phpfmg_captcha_name() ] = $img->text ;
}



function phpfmg_captcha_generate_images(){
    for( $i = 0; $i < 50; $i ++ ){
        $file = "$i.png";
        $img = new phpfmgImage();
        $img->out($file);
        $data = base64_encode( file_get_contents($file) );
        echo "'{$img->text}' => '{$data}',\n" ;
        unlink( $file );
    };
}


function phpfmg_dd_lookup(){
    $paraOk = ( isset($_REQUEST['n']) && isset($_REQUEST['lookup']) && isset($_REQUEST['field_name']) );
    if( !$paraOk )
        return;
        
    $base64 = phpfmg_dependent_dropdown_data();
    $data = @unserialize( base64_decode($base64) );
    if( !is_array($data) ){
        return ;
    };
    
    
    foreach( $data as $field ){
        if( $field['name'] == $_REQUEST['field_name'] ){
            $nColumn = intval($_REQUEST['n']);
            $lookup  = $_REQUEST['lookup']; // $lookup is an array
            $dd      = new DependantDropdown(); 
            echo $dd->lookupFieldColumn( $field, $nColumn, $lookup );
            return;
        };
    };
    
    return;
}


function phpfmg_filman_download(){
    if( !isset($_REQUEST['filelink']) )
        return ;
        
    $filelink =  base64_decode($_REQUEST['filelink']);
    $file = PHPFMG_SAVE_ATTACHMENTS_DIR . basename($filelink);

    // 2016-12-05:  to prevent *LFD/LFI* attack. patch provided by Pouya Darabi, a security researcher in cert.org
    $real_basePath = realpath(PHPFMG_SAVE_ATTACHMENTS_DIR); 
    $real_requestPath = realpath($file);
    if ($real_requestPath === false || strpos($real_requestPath, $real_basePath) !== 0) { 
        return; 
    }; 

    if( !file_exists($file) ){
        return ;
    };
    
    phpfmg_util_download( $file, $filelink );
}


class phpfmgDataManager
{
    var $dataFile = '';
    var $columns = '';
    var $records = '';
    
    function __construct(){
        $this->dataFile = PHPFMG_SAVE_FILE; 
    }

    function phpfmgDataManager(){
        $this->dataFile = PHPFMG_SAVE_FILE; 
    }
    
    function parseFile(){
        $fp = @fopen($this->dataFile, 'rb');
        if( !$fp ) return false;
        
        $i = 0 ;
        $phpExitLine = 1; // first line is php code
        $colsLine = 2 ; // second line is column headers
        $this->columns = array();
        $this->records = array();
        $sep = chr(0x09);
        while( !feof($fp) ) { 
            $line = fgets($fp);
            $line = trim($line);
            if( empty($line) ) continue;
            $line = $this->line2display($line);
            $i ++ ;
            switch( $i ){
                case $phpExitLine:
                    continue;
                    break;
                case $colsLine :
                    $this->columns = explode($sep,$line);
                    break;
                default:
                    $this->records[] = explode( $sep, phpfmg_data2record( $line, false ) );
            };
        }; 
        fclose ($fp);
    }
    
    function displayRecords(){
        $this->parseFile();
        echo "<table border=1 style='width=95%;border-collapse: collapse;border-color:#cccccc;' >";
        echo "<tr><td>&nbsp;</td><td><b>" . join( "</b></td><td>&nbsp;<b>", $this->columns ) . "</b></td></tr>\n";
        $i = 1;
        foreach( $this->records as $r ){
            echo "<tr><td align=right>{$i}&nbsp;</td><td>" . join( "</td><td>&nbsp;", $r ) . "</td></tr>\n";
            $i++;
        };
        echo "</table>\n";
    }
    
    function line2display( $line ){
        $line = str_replace( array('"' . chr(0x09) . '"', '""'),  array(chr(0x09),'"'),  $line );
        $line = substr( $line, 1, -1 ); // chop first " and last "
        return $line;
    }
    
}
# end of class



# ------------------------------------------------------
class phpfmgImage
{
    var $im = null;
    var $width = 73 ;
    var $height = 33 ;
    var $text = '' ; 
    var $line_distance = 8;
    var $text_len = 4 ;

    function __construct( $text = '', $len = 4 ){
        $this->phpfmgImage( $text, $len );
    }

    function phpfmgImage( $text = '', $len = 4 ){
        $this->text_len = $len ;
        $this->text = '' == $text ? $this->uniqid( $this->text_len ) : $text ;
        $this->text = strtoupper( substr( $this->text, 0, $this->text_len ) );
    }
    
    function create(){
        $this->im = imagecreate( $this->width, $this->height );
        $bgcolor   = imagecolorallocate($this->im, 255, 255, 255);
        $textcolor = imagecolorallocate($this->im, 0, 0, 0);
        $this->drawLines();
        imagestring($this->im, 5, 20, 9, $this->text, $textcolor);
    }
    
    function drawLines(){
        $linecolor = imagecolorallocate($this->im, 210, 210, 210);
    
        //vertical lines
        for($x = 0; $x < $this->width; $x += $this->line_distance) {
          imageline($this->im, $x, 0, $x, $this->height, $linecolor);
        };
    
        //horizontal lines
        for($y = 0; $y < $this->height; $y += $this->line_distance) {
          imageline($this->im, 0, $y, $this->width, $y, $linecolor);
        };
    }
    
    function out( $filename = '' ){
        if( function_exists('imageline') ){
            $this->create();
            if( '' == $filename ) header("Content-type: image/png");
            ( '' == $filename ) ? imagepng( $this->im ) : imagepng( $this->im, $filename );
            imagedestroy( $this->im ); 
        }else{
            $this->out_predefined_image(); 
        };
    }

    function uniqid( $len = 0 ){
        $md5 = md5( uniqid(rand()) );
        return $len > 0 ? substr($md5,0,$len) : $md5 ;
    }
    
    function out_predefined_image(){
        header("Content-type: image/png");
        $data = $this->getImage(); 
        echo base64_decode($data);
    }
    
    // Use predefined captcha random images if web server doens't have GD graphics library installed  
    function getImage(){
        $images = array(
			'25E0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7WANEQ1lDHVqRxUSmiDSwNjBMdUASC2gFiwUEIOtuFQlhbWB0EEF237SpS5eGrsyahuy+AIZGV4Q6MGR0wBRjbRABiqHaAbS1Fd0toaGMIehuHqjwoyLE4j4AuJnLDQw82DkAAAAASUVORK5CYII=',
			'CA4E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7WEMYAhgaHUMDkMREWhlDGFodHZDVBTSytjJMRRNrEGl0CISLgZ0UtWrayszMzNAsJPeB1Lk2ousVDXUNDUSzA2gemjqRVkwx1hCwGIqbByr8qAixuA8A0xvMMijN0dIAAAAASUVORK5CYII=',
			'593A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7QkMYQxhDGVqRxQIaWFtZGx2mOqCIiTQ6NAQEBCCJBQYAxRodHUSQ3Bc2benSrKkrs6Yhu6+VMRBJHVSMAWheYGgIsh2tLCAxFHUiU0BuQdXLGgByMyOqeQMUflSEWNwHAHlXzOZri7IaAAAAAElFTkSuQmCC',
			'BC12' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7QgMYQxmmMEx1QBILmMLa6BDCEBCALNYq0uAYwugggqIOyJvC0CCC5L7QqGmrVgFRFJL7oOoaHdDMA4q1MqCJOUwBmYjmlikMAehuZgx1DA0ZBOFHRYjFfQB3984GORchFAAAAABJRU5ErkJggg==',
			'88BE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAUElEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDGUMDkMREprC2sjY6OiCrC2gVaXRtCEQRQ1MHdtLSqJVhS0NXhmYhuY9Y84iwA6ebByr8qAixuA8Azv/LI1bfrJIAAAAASUVORK5CYII=',
			'6CEA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7WAMYQ1lDHVqRxUSmsDa6NjBMdUASC2gRaQCKBQQgizWINLA2MDqIILkvMmraqqWhK7OmIbkvZAqKOojeVrBYaAiamCuaOohbUMUgbnZEERuo8KMixOI+AO+Wy9Gv7fHRAAAAAElFTkSuQmCC',
			'3EF8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAVklEQVR4nGNYhQEaGAYTpIn7RANEQ1lDA6Y6IIkFTBFpYG1gCAhAVtkKEmN0EEEWQ1UHdtLKqKlhS0NXTc1Cdh+x5mERw+YWsJsbGFDcPFDhR0WIxX0AxQ/LADQhMiAAAAAASUVORK5CYII=',
			'1D34' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7GB1EQxhDGRoCkMRYHURaWRsdGpHFRB1EGh0aAloDUPQCxRodpgQguW9l1rSVWVNXRUUhuQ+iztEBQ29DYGgIhlhAA5o6kFtQxERDMN08UOFHRYjFfQDwn8zfXOsqKgAAAABJRU5ErkJggg==',
			'8560' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7WANEQxlCGVqRxUSmiDQwOjpMdUASC2gVaWBtcAgIQFUXwtrA6CCC5L6lUVOXLp26MmsakvtEpjA0ujo6wtRBzQOKNQSiiYkAxQLQ7GBtRXcLawBjCLqbByr8qAixuA8AbwDMe93B/bYAAAAASUVORK5CYII=',
			'5088' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7QkMYAhhCGaY6IIkFNDCGMDo6BASgiLG2sjYEOoggiQUGiDQ6ItSBnRQ2bdrKrNBVU7OQ3deKog4u5opmXkArph0iUzDdwhqA6eaBCj8qQizuAwDmM8vbKxYzcwAAAABJRU5ErkJggg==',
			'0ABE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7GB0YAlhDGUMDkMRYAxhDWBsdHZDViUxhbWVtCEQRC2gVaXRFqAM7KWrptJWpoStDs5Dch6YOKiYa6opmnsgUoDo0MdYATL2MDkAxNDcPVPhREWJxHwBWMcsGerfw/AAAAABJRU5ErkJggg==',
			'429E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpI37pjCGMIQyhgYgi4WwtjI6Ojogq2MMEWl0bQhEEWOdwoAsBnbStGmrlq7MjAzNQnJfwBSGKQwhqHpDQxkCGNDMA7rFgRFDjLUB3S0MU0RDHdDdPFDhRz2IxX0AzTXJidESZ2sAAAAASUVORK5CYII=',
			'319A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7RAMYAhhCGVqRxQKmMAYwOjpMdUBW2coawNoQEBCALDaFASgW6CCC5L6VUauiVmZGZk1Ddh9QHUMIXB3UPKBYQ2BoCJoYYwOqugCgXkZHRxQx0QDWUIZQRlTzBij8qAixuA8ALHvI0LRGTzkAAAAASUVORK5CYII=',
			'F3D0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWklEQVR4nGNYhQEaGAYTpIn7QkNZQ1hDGVqRxQIaRFpZGx2mOqCIMTS6NgQEBKCKtbI2BDqIILkvNGpV2NJVkVnTkNyHpg7JPGxi6HZgcwummwcq/KgIsbgPAMf/zlnzRgg6AAAAAElFTkSuQmCC',
			'A615' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nM2QsRGAIAxFk4INcJ809inAgmmgYANkAwuZ0tgFtNQ78rt3+bl3gfaYCDPlFz8kdFDQs2KGTQaHpPdssQkHxtlG6a6k/MJRt1bPEJQf5yVDkRuq671NNDC5JwypZ+buMndMTDztNMH/PsyL3wVx+MtmtixxewAAAABJRU5ErkJggg==',
			'B60F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7QgMYQximMIaGIIkFTGFtZQhldEBWF9Aq0sjo6IgqNkWkgbUhECYGdlJo1LSwpasiQ7OQ3BcwRbQVSR3cPFcsYo4YdmC6BepmFLGBCj8qQizuAwCUjMrYuuifngAAAABJRU5ErkJggg==',
			'F694' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7QkMZQxhCGRoCkMQCGlhbGR0dGlHFRBpZGwJa0cQagGJTApDcFxo1LWxlZlRUFJL7AhpEWxlCAh3QzXNoCAwNQRNzBJJY3IImhunmgQo/KkIs7gMAhLfO/UNR1AAAAAAASUVORK5CYII=',
			'54E8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7QkMYWllDHaY6IIkFNDBMZW1gCAhAFQtlbWB0EEESCwxgdEVSB3ZS2LSlS5eGrpqahey+VpFWdPMYWkVDXdHMC2gFugVNTGQKA4Ze1gBMNw9U+FERYnEfAGG1y3FU0zgkAAAAAElFTkSuQmCC',
			'DC4A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7QgMYQxkaHVqRxQKmsAJFHKY6IIu1ijQARQIC0MQYAh0dRJDcF7V02qqVmZlZ05DcB1LH2ghXhxALDQwNQbcDXR3ILWhiEDejig1U+FERYnEfAKjTzsgJn9OZAAAAAElFTkSuQmCC',
			'2B16' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7WANEQximMEx1QBITmSLSyhDCEBCAJBbQKtLoGMLoIICsuxWobgqjA4r7pk0NWzVtZWoWsvsCwOpQzGN0EGl0AOoVQXZLA6aYSANIL6pbQkNFQxhDHVDcPFDhR0WIxX0AmN3LDId4ZYQAAAAASUVORK5CYII=',
			'633A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7WANYQxhDGVqRxUSmiLSyNjpMdUASC2hhaHRoCAgIQBZrAOprdHQQQXJfZNSqsFVTV2ZNQ3JfyBQUdRC9rSDzAkNDMMVQ1EHcgqoX4mZGFLGBCj8qQizuAwAJ4cy+p8Ve1QAAAABJRU5ErkJggg==',
			'3BC7' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7RANEQxhCHUNDkMQCpoi0MjoENIggq2wVaXRtEEAVA6pjBalHct/KqKlhS4FUFrL7IOpaGTDMY5iCKSYQwIDhlkAHLG5GERuo8KMixOI+AAjOy9V8Je3UAAAAAElFTkSuQmCC',
			'E46A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7QkMYWhlCgRhJLKCBYSqjo8NUB1SxUNYGh4AAFDFGV9YGRgcRJPeFRi1dunTqyqxpSO4LaBBpZXV0hKmDiomGujYEhoag2tHK2hCIpo6hlRFNL8TNjChiAxV+VIRY3AcAQNrMJce+oQYAAAAASUVORK5CYII=',
			'CC09' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7WEMYQxmmMEx1QBITaWVtdAhlCAhAEgtoFGlwdHR0EEEWaxBpYG0IhImBnRS1atqqpauiosKQ3AdRFzAVUy+IRLfDAcUObG7B5uaBCj8qQizuAwAUs8z049qDTgAAAABJRU5ErkJggg==',
			'26E2' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDHaY6IImJTGFtZW1gCAhAEgtoFWlkbWB0EEHW3SrSwApSj+y+adPCloauWhWF7L4AUZB5jch2AE1qdAWagOKWBrDYFGQxoA1gtyCLhYaC3OwYGjIIwo+KEIv7ALMXyv5bpf8AAAAAAElFTkSuQmCC',
			'169C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7GB0YQxhCGaYGIImxOrC2Mjo6BIggiYk6iDSyNgQ6sKDoFWkAiSG7b2XWtLCVmZFZyO5jdBBtZQiBq4PpbXRowBRzxLADi1tCMN08UOFHRYjFfQDGzMf5lq+iIAAAAABJRU5ErkJggg==',
			'951D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7WANEQxmmMIY6IImJTBFpYAhhdAhAEgtoFWlgBIqJoIqFAPXCxMBOmjZ16tJV01ZmTUNyH6srQ6PDFFS9DK2YYgKtIhhiIlNYW0F2ILuFNQDoklBHFDcPVPhREWJxHwAEcsp/+w7LpAAAAABJRU5ErkJggg==',
			'3A23' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdklEQVR4nGNYhQEaGAYTpIn7RAMYAhhCGUIdkMQCpjCGMDo6OgQgq2xlbWVtCGgQQRabItLoABQLQHLfyqhpK7NWZi3NQnYfSF0rQwOqeaKhDlMYUM1rBaoLQBULAOp1dGBEcYtogEija2gAipsHKvyoCLG4DwC+KMzccsknTAAAAABJRU5ErkJggg==',
			'4F90' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpI37poiGOoQytKKIhYg0MDo6THVAEmMEirE2BAQEIImxTgGJBTqIILlv2rSpYSszI7OmIbkvAKiOIQSuDgxDQ0E8VDEGoDpGNDvAYmhuAYkxoLt5oMKPehCL+wCMjcvH7iE0lQAAAABJRU5ErkJggg==',
			'7278' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7QkMZQ1hDA6Y6IIu2srYyNAQEBKCIiTQ6NAQ6iCCLTWFodGh0gKmDuClq1VIgnJqF5D5GB6DKKQwo5rE2MAQwBDCimCcCVAmCyGIBQJUgtQEoYqKhrg0MqG4eoPCjIsTiPgA4PcwEJfQLigAAAABJRU5ErkJggg==',
			'8278' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDA6Y6IImJTGFtZWgICAhAEgtoFWl0aAh0EEFRx9Do0OgAUwd20tKoVUuBcGoWkvuA6kAQzTyGAIYARhTzAloZHUAQ1Q7WBtYGVL2sAaKhrg0MKG4eqPCjIsTiPgD2BcyGMHgfkQAAAABJRU5ErkJggg==',
			'6E44' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7WANEQxkaHRoCkMREpog0MLQ6NCKLBbQAxaY6tKKINQDFAh2mBCC5LzJqatjKzKyoKCT3hQDNY210dEDR2woUCw0MDUETw+oWNDFsbh6o8KMixOI+AGP5zs3fuVX+AAAAAElFTkSuQmCC',
			'073E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7GB1EQx1DGUMDkMRYAxgaXRsdHZDViUxhaHRoCEQRC2hlaGVAqAM7KWrpqmmrpq4MzUJyH1BdAAOaeQGtjEB+IJodrA3oYqwBIg2saHoZHUQaGNHcPFDhR0WIxX0ACa/KWEaMTxEAAAAASUVORK5CYII=',
			'3B60' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7RANEQxhCGVqRxQKmiLQyOjpMdUBW2SrS6NrgEBCALAZUx9rA6CCC5L6VUVPDlk5dmTUN2X0gdY6OMHVI5gViEQtAsQObW7C5eaDCj4oQi/sAIn3MNcu4YK8AAAAASUVORK5CYII=',
			'34F6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7RAMYWllDA6Y6IIkFTGGYytrAEBCArLKVIZS1gdFBAFlsCqMrSAzZfSujli5dGroyNQvZfVNEWoHq0MwTDXUF6hVBtQOkDkUM6JZWdLeA3dzAgOLmgQo/KkIs7gMA+wbKc8T+BzAAAAAASUVORK5CYII=',
			'ACA2' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nM2QMQ6AMAhF6cANOFA7uGMiS09DB29QvUGXntIOJlJ11KT87eUHXoD6GIWR8ouf804gw+YNQ8bkBZgNo0waQvBkGK+kqKxk/GLZa6mx5fI7e8neEGlMeIXbvkk59wxTY9wzJ6izLAP878O8+B1Oj85U03ARHAAAAABJRU5ErkJggg==',
			'DA14' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7QgMYAhimMDQEIIkFTGEMYQhhaEQRa2VtBYq2ooqJNDpMYZgSgOS+qKXTVmZNWxUVheQ+iDpGB1S9oqFAsdAQTPPQ3IIpFhog0ugY6oAiNlDhR0WIxX0AUO/P4BQy7TgAAAAASUVORK5CYII=',
			'7816' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7QkMZQximMEx1QBZtZW1lCGEICEARE2l0DGF0EEAWmwJUN4XRAcV9USvDVk1bmZqF5D5GB7A6FPNYG0QaHYB6RZDERLCIBTSA9KK6JaCBMYQx1AHVzQMUflSEWNwHAILnyxfFJCT8AAAAAElFTkSuQmCC',
			'6CAD' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7WAMYQxmmMIY6IImJTGFtdAhldAhAEgtoEWlwdHR0EEEWaxBpYG0IhImBnRQZNW3V0lWRWdOQ3BcyBUUdRG8rUCwUU8wVTR3ILSAxZLeA3Aw0D8XNAxV+VIRY3AcAyKDM53EmW1EAAAAASUVORK5CYII=',
			'B78B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7QgNEQx1CGUMdkMQCpjA0Ojo6OgQgi7UyNLo2BDqIoKprZUSoAzspNGrVtFWhK0OzkNwHVBfAiGEeowMrunmtrA0YYlNEGtD1hgYAVaC5eaDCj4oQi/sAxKrMiYgQFUIAAAAASUVORK5CYII=',
			'6AB7' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7WAMYAlhDGUNDkMREpjCGsDY6NIggiQW0sLayNgSgijWINLoC1QUguS8yatrK1NBVK7OQ3BcyBayuFdnegFbRUNeGgCmoYkB1DQEBDChuAel1dEB1M1AslBFFbKDCj4oQi/sABkPNvvP42lAAAAAASUVORK5CYII=',
			'B0AA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7QgMYAhimMLQiiwVMYQxhCGWY6oAs1srayujoEBCAok6k0bUh0EEEyX2hUdNWpq6KzJqG5D40dVDzgGKhgaEhaHawoqsDugVdDORmdLGBCj8qQizuAwCbg81iaZ2haAAAAABJRU5ErkJggg==',
			'7929' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAe0lEQVR4nGNYhQEaGAYTpIn7QkMZQxhCGaY6IIu2srYyOjoEBKCIiTS6NgQ6iCCLTRFpdECIQdwUtXRp1sqsqDAk9zE6MAY6tDJMRdbL2sDQ6DCFoQFZTKSBpdEhgAHFjoAGoFscGFDcEtDAGMIaGoDq5gEKPypCLO4DADxHy31x7DpWAAAAAElFTkSuQmCC',
			'9CD1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXUlEQVR4nGNYhQEaGAYTpIn7WAMYQ1lDGVqRxUSmsDa6NjpMRRYLaBVpcG0ICEUXYwWSyO6bNnXaqqWropYiu4/VFUUdBLZiiglA7MDmFhQxqJtDAwZB+FERYnEfALzAzWgvm6p6AAAAAElFTkSuQmCC',
			'D911' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7QgMYQximMLQiiwVMYW1lCGGYiiLWKtLoGMIQii7mgNALdlLU0qVLs6atWorsvoBWxkAHdDtaGRoxxVgwxUBuQRMDuZkx1CE0YBCEHxUhFvcBAOjZzawqUGWVAAAAAElFTkSuQmCC',
			'990C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7WAMYQximMEwNQBITmcLayhDKECCCJBbQKtLo6OjowIIm5toQ6IDsvmlTly5NXRWZhew+VlfGQCR1ENjK0IguJtDKgmEHNrdgc/NAhR8VIRb3AQDIU8r4VHDpDgAAAABJRU5ErkJggg==',
			'5649' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7QkMYQxgaHaY6IIkFNLC2MrQ6BASgiIk0Mkx1dBBBEgsMAPIC4WJgJ4VNmxa2MjMrKgzZfa2iraxAO5D1MrSKNLqGAk1FtgMo5tDogGKHyBSgWxpR3cIagOnmgQo/KkIs7gMAO3jNBCdIYV8AAAAASUVORK5CYII=',
			'2E27' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nM2QwQ2AIAwA6YMNyj51g5pYh3CKftgA3QGmtPxK9KlR7sWFNhdCuxwNf+KVvshJgoAszmFBhYkUneOMGpUHF3K/seH6jn1tdaub72N7lw03C2SuGL6lb2fDtxhAQN6JJIkyD+6r/3uQm74TE7zKLn5CO+0AAAAASUVORK5CYII=',
			'AC4A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7GB0YQxkaHVqRxVgDWIEiDlMdkMREpog0AEUCApDEAlpFGhgCHR1EkNwXtXTaqpWZmVnTkNwHUsfaCFcHhqGhQLHQwNAQNPMc0NQFtALdgiEGcjOq2ECFHxUhFvcBANUUzZ5oJKUtAAAAAElFTkSuQmCC',
			'C82D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7WEMYQxhCGUMdkMREWllbGR0dHQKQxAIaRRpdGwIdRJDFGlhbGRBiYCdFrVoZtmplZtY0JPeB1bUyoukVaXSYgiYGtMMhAFUM7BYHRhS3gNzMGhqI4uaBCj8qQizuAwDfn8sY+Pa/YgAAAABJRU5ErkJggg==',
			'AEA6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7GB1EQxmmMEx1QBJjDRBpYAhlCAhAEhOZItLA6OjoIIAkFtAq0sDaEOiA7L6opVPDlq6KTM1Cch9UHYp5oaFAsdBABxEs5mGKBaDoDWgVDQWKobh5oMKPihCL+wAP3syiNrgTNAAAAABJRU5ErkJggg==',
			'A45E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7GB0YWllDHUMDkMRYAximsoJkkMREpjCEoosFtDK6sk6Fi4GdFLUUCDIzQ7OQ3BfQKtLK0BCIojc0VDTUAU0soBXoFixijI6OGGIMoYwobh6o8KMixOI+AAqNyf+elR71AAAAAElFTkSuQmCC',
			'FA77' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7QkMZAlhDA0NDkMQCGhhDQKQIihhrK6aYSKNDowOQRrgvNGrayqylq1ZmIbkPrG4KQysDil7RUIcAhikMaOY5OjAEoIu5NjA6EBIbqPCjIsTiPgCaAM34ivCb+wAAAABJRU5ErkJggg==',
			'39EA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7RAMYQ1hDHVqRxQKmsLayNjBMdUBW2SrS6NrAEBCALDYFJMboIILkvpVRS5emhq7MmobsvimMgUjqoOYxgPSGhqCIsTSiq4O4BVUM4mZHVPMGKPyoCLG4DwDVvsroS2y0sgAAAABJRU5ErkJggg==',
			'1165' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7GB0YAhhCGUMDkMRYHRgDGB0dHZDViTqwBrA2oIqB9LI2MLo6ILlvZdaqqKVTV0ZFIbkPrM7RoUEEQ28AFrFAB3QxRkeHAGT3iYawhjKEMkx1GAThR0WIxX0AV1PGKjyyU+sAAAAASUVORK5CYII=',
			'BB18' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7QgNEQximMEx1QBILmCLSyhDCEBCALNYq0ugYwugggq5uClwd2EmhUVPDVk1bNTULyX1o6uDmOUxBMw+bGBa9IDczhjqguHmgwo+KEIv7AAAPzbw2e5enAAAAAElFTkSuQmCC',
			'18F8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7GB0YQ1hDA6Y6IImxOrC2sjYwBAQgiYk6iDS6AlWLoOhFUQd20sqslWFLQ1dNzUJyHyMW8xixmkfQDohbQoBubmBAcfNAhR8VIRb3AQD3YMjVwccB2gAAAABJRU5ErkJggg==',
			'E8E9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAW0lEQVR4nGNYhQEaGAYTpIn7QkMYQ1hDHaY6IIkFNLC2sjYwBASgiIk0ujYwOohgqIOLgZ0UGrUybGnoqqgwJPdBzZsqgmEeQwMWMSx2oLoFm5sHKvyoCLG4DwCfcMycdMHs1AAAAABJRU5ErkJggg==',
			'30C8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7RAMYAhhCHaY6IIkFTGEMYXQICAhAVtnK2sraIOgggiw2RaTRtYEBpg7spJVR01amrlo1NQvZfajqoOaBxBhRzcNiBza3YHPzQIUfFSEW9wEAdobLmSFB7pEAAAAASUVORK5CYII=',
			'8704' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nM2QsQ2AMAwETeENwj7OBkbCjadximwQsUGaTElKJ1CCwN+dXq+ToV3O4E95xQ95FSpg7FgokEggecYZUoyUp15G48LOr2o7alNV59d7jLbRuLdQZ7IPDG2JNLkEAxn9kDubnL/634O58TsBiYfN+QBZKysAAAAASUVORK5CYII=',
			'171A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7GB1EQx2mMLQii7E6MDQ6hDBMdUASEwWKOYYwBASg6AXqm8LoIILkvpVZq6atmrYyaxqS+4DqApDUQcWAolMYQ0NQxFgbMNWJYIiJhog0MIY6oogNVPhREWJxHwAyNsgDmqKrPAAAAABJRU5ErkJggg==',
			'98F5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDA0MDkMREprC2sjYwOiCrC2gVaXTFEAOrc3VAct+0qSvDloaujIpCch+rK0gd0Fxkm8HmoYoJQO1AFoO4hSEA2X1gNzcwTHUYBOFHRYjFfQAZ0cqXXBmOuAAAAABJRU5ErkJggg==',
			'1366' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7GB1YQxhCGaY6IImxOoi0Mjo6BAQgiYk6MDS6Njg6CKDoZWhlBZFI7luZtSps6dSVqVlI7gOrc3REMY8RbF6ggwhBMSxuCcF080CFHxUhFvcBALFMyLln+3zVAAAAAElFTkSuQmCC',
			'4F5D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpI37poiGuoY6hjogi4WINLA2MDoEIIkxQsVEkMRYpwDFpsLFwE6aNm1q2NLMzKxpSO4LmAJSEYiiNzQUU4wBZB4WMUZHRxS3gMQYQhlR3TxQ4Uc9iMV9AEyyysSffSRFAAAAAElFTkSuQmCC',
			'9BD8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7WANEQ1hDGaY6IImJTBFpZW10CAhAEgtoFWl0bQh0EEEVa2VtCICpAztp2tSpYUtXRU3NQnIfqyuKOgjEYp4AFjFsbsHm5oEKPypCLO4DALFszVgqLBfyAAAAAElFTkSuQmCC',
			'5311' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7QkNYQximMLQiiwU0iLQyhDBMRRVjaHQMYQhFFgsMAOpD6AU7KWzaqrBV01YtRXFfK4o6mFijA7q9WMREpohg6GUNYA1hDHUIDRgE4UdFiMV9AKpfy8vN3fteAAAAAElFTkSuQmCC',
			'6237' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nGNYhQEaGAYTpIn7WAMYQxhDGUNDkMREprC2sjY6NIggiQW0iABFAlDFGhgaHcCiCPdFRq1aumrqqpVZSO4LmcIwBaiyFdnegFaGACA5BVWM0QFIBjCguqWBtdHRAdXNoqGOoYwoYgMVflSEWNwHAICjzPlAc4GrAAAAAElFTkSuQmCC',
			'EA38' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7QkMYAhhDGaY6IIkFNDCGsDY6BASgiLG2MjQEOoigiIk0OiDUgZ0UGjVtZdbUVVOzkNyHpg4qJhrqgM08LGKuaHpDQ0QaHdHcPFDhR0WIxX0AmsDPOIaAbksAAAAASUVORK5CYII=',
			'317C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7RAMYAlhDA6YGIIkFTGEMAJIBIsgqW1mBYoEOLMhiUxgCGBodHZDdtzJqVdSqpSuzUNwHUjeF0QHF5lagWACmGKMDI4odAUC9rA0MKG4RBboYKIbi5oEKPypCLO4DAArKyKA4LFp5AAAAAElFTkSuQmCC',
			'4F96' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpI37poiGOoQyTHVAFgsRaWB0dAgIQBJjBIqxNgQ6CCCJsU6BiCG7b9q0qWErMyNTs5DcFwBUxxASiGJeaChQDKhXBMUtQHuxiaG5BSTGgO7mgQo/6kEs7gMAEUTLal/eNssAAAAASUVORK5CYII=',
			'B51C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7QgNEQxmmMEwNQBILmCLSwBDCECCCLNYq0sAYwujAgqouhGEKowOy+0Kjpi5dNW1lFrL7AqYwNDog1EHNwyYmAhZDtYO1Feg+FLeEBgBdEuqA4uaBCj8qQizuAwA0P8xQkKc0gwAAAABJRU5ErkJggg==',
			'97DC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7WANEQ11DGaYGIImJTGFodG10CBBBEgtoBYo1BDqwoIq1sgLFkN03beqqaUtXRWYhu4/VlSEASR0EtjI6oIsJAE1jRbNDZIpIAyuaW1iBPFY0Nw9U+FERYnEfANTwy7EDd8BZAAAAAElFTkSuQmCC',
			'1382' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7GB1YQxhCGaY6IImxOoi0Mjo6BAQgiYk6MDS6NgQ6iKDoZQCpaxBBct/KrFVhq0JXrYpCch9UXaMDql6geQGtDJhiU1DFIG5BFhMNAbmZMTRkEIQfFSEW9wEAWY/JKEF7DFMAAAAASUVORK5CYII=',
			'6A8B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7WAMYAhhCGUMdkMREpjCGMDo6OgQgiQW0sLayNgQ6iCCLNYg0OiLUgZ0UGTVtZVboytAsJPeFTEFRB9HbKhrqim5eq0gjupgIFr2sASKNDmhuHqjwoyLE4j4Ay3jMNqXUmrYAAAAASUVORK5CYII=',
			'404F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpI37pjAEMDQ6hoYgi4UwhjC0Ojogq2MMYW1lmIoqxjpFpNEhEC4GdtK0adNWZmZmhmYhuS8AqM61EVVvaChQLDTQAdUtQDvQ1DFMAboFQwzsZlSxgQo/6kEs7gMAcS/J/pdqJfkAAAAASUVORK5CYII=',
			'010E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7GB0YAhimMIYGIImxBjAGMIQCZZDERKYARR0dUcQCWhkCWBsCYWJgJ0UtBaHI0Cwk96GpwykmMoUBww7WAAYMtzA6sIaiu3mgwo+KEIv7AKOqxvghCPV8AAAAAElFTkSuQmCC',
			'0688' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7GB0YQxhCGaY6IImxBrC2Mjo6BAQgiYlMEWlkbQh0EEESC2gVaUBSB3ZS1NJpYatCV03NQnJfQKsohnlAvY2uaOaB7EAXw+YWbG4eqPCjIsTiPgAH8ste4/yz3gAAAABJRU5ErkJggg==',
			'B254' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nM2QsQ2AMAwEP0U2gH1CQW+kuMkGMIVTZIMwQppMScoYKEHg706v18molxP8Ka/4MRlv2Ql1jLJNVhAVS0OcBUn3EOcdmTo/DrWUdQuh82u9DFmc3gM1xl4x42wzObmImbQf08iOodhX/3swN34H7XTPJtmfvM4AAAAASUVORK5CYII=',
			'EB6A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7QkNEQxhCGVqRxQIaRFoZHR2mOqCKNbo2OAQEoKljbWB0EEFyX2jU1LClU1dmTUNyH1idoyNMHZJ5gaEhmGLo6oBuQdULcTMjithAhR8VIRb3AQBKbsz0dhnZUQAAAABJRU5ErkJggg==',
			'EC51' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7QkMYQ1lDHVqRxQIaWBtdGximooqJNADFQtHFWKcywPSCnRQaNW3V0syspcjuA6kDkq3oerGJuWKIsTY6OqK6D+RmoEtCAwZB+FERYnEfAC98zdaczktZAAAAAElFTkSuQmCC',
			'B2C6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7QgMYQxhCHaY6IIkFTGFtZXQICAhAFmsVaXRtEHQQQFHHABRjdEB2X2jUqqVLV61MzUJyH1DdFNYGRjTzGAKAYg4iKGKMDqxAO0RQ3dKA7pbQANFQBzQ3D1T4URFicR8APOXM/g8z3yEAAAAASUVORK5CYII=',
			'585A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAeElEQVR4nGNYhQEaGAYTpIn7QkMYQ1hDHVqRxQIaWFtZGximOqCIiTS6NjAEBCCJBQYA1U1ldBBBcl/YtJVhSzMzs6Yhu6+VFWh+IEwdVEyk0aEhMDQE2Y5WkB2o6kSmsLYyOjqiiLEGMIYwhDKimjdA4UdFiMV9AM74y5u81YOEAAAAAElFTkSuQmCC',
			'A87D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7GB0YQ1hDA0MdkMRYA1hbGRoCHQKQxESmiDQ6AMVEkMQCWoHqGh1hYmAnRS1dGbZq6cqsaUjuA6ubwoiiNzQUaF4AI5p5IkDT0MVYW1mBrgxAEQO6uYERxc0DFX5UhFjcBwCmq8vUEILeCQAAAABJRU5ErkJggg==',
			'11DA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7GB0YAlhDGVqRxVgdGANYGx2mOiCJiTqwBrA2BAQEoOttCHQQQXLfyqxVUUtXRWZNQ3IfmjpksdAQ3OYhxBodUcREQ1hDWUMZUcQGKvyoCLG4DwAKMccpGKhOdQAAAABJRU5ErkJggg==',
			'8640' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7WAMYQxgaHVqRxUSmsLYytDpMdUASC2gVaWSY6hAQgKJOpIEh0NFBBMl9S6Omha3MzMyahuQ+kSmirayNcHVw81xDAzHEHBrR7QC6pRHVLdjcPFDhR0WIxX0AV9fNN+SJOYkAAAAASUVORK5CYII=',
			'D7B3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7QgNEQ11DGUIdkMQCpjA0ujY6OgQgi7UCxRoCGkRQxVpZGx0aApDcF7V01bSloauWZiG5D6guAEkdVIzRgRXDPNYGDLEpIg2saG4JDQCKobl5oMKPihCL+wBM489a7g/QCAAAAABJRU5ErkJggg==',
			'63AE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7WANYQximMIYGIImJTBFpZQhldEBWF9DC0Ojo6Igq1sDQytoQCBMDOykyalXY0lWRoVlI7guZgqIOoreVodE1FIsYmjqQW9D1gtwMFENx80CFHxUhFvcBAH24yxeTKVlUAAAAAElFTkSuQmCC',
			'25A5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAd0lEQVR4nM2Quw2AMAwFncIbmH1Ckd6RcAHTvBTZANggDVOS0ghKkPDrTv6cTMetQH/KJ36sg9EaTB2TVUAWou/TKgjjeGFUZWLkFL3fvrV2zMvi/ZRKgkLcbN9ekl0ZQ3pfjp4JuDJUvZ9Z6Hd1iz/434t58DsBOsvL2vy5LYsAAAAASUVORK5CYII=',
			'68B6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDGaY6IImJTGFtZW10CAhAEgtoEWl0bQh0EEAWawCpc3RAdl9k1MqwpaErU7OQ3BcCNs8R1bxWiHkiBMSwuQWbmwcq/KgIsbgPABjbzQIC7KzKAAAAAElFTkSuQmCC',
			'3A4E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7RAMYAhgaHUMDkMQCpjCGMLQ6OqCobGVtZZiKJjZFpNEhEC4GdtLKqGkrMzMzQ7OQ3QdU59qIbp5oqGtoIJoY0Dw0dQFTMMVEA8BiKG4eqPCjIsTiPgD66cuEGTzcNQAAAABJRU5ErkJggg==',
			'C035' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7WEMYAhhDGUMDkMREWhlDWBsdHZDVBTSytjI0BKKKNYg0OjQ6ujoguS9q1bSVWVNXRkUhuQ+izqFBBF0viMRihwiGWxwCkN0HcTPDVIdBEH5UhFjcBwBgCcyHgDpujAAAAABJRU5ErkJggg==',
			'BB08' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7QgNEQximMEx1QBILmCLSyhDKEBCALNYq0ujo6OgggqaOtSEApg7spNCoqWFLV0VNzUJyH5o6uHmuDYGo5uGwA90t2Nw8UOFHRYjFfQCMis4ZOgtPEAAAAABJRU5ErkJggg==',
			'2557' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7WANEQ1lDHUNDkMREpog0sIJoJLGAVkwxhlaRENapQDlk902bunRpZtbKLGT3BTA0OgBNQLaX0QEsNgXFLQ0ija4NAQHIYkBbWxkdHR2QxUJDGUMYQhlRxAYq/KgIsbgPAEify0LPbI5xAAAAAElFTkSuQmCC',
			'8531' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7WANEQxlDGVqRxUSmiDSwNjpMRRYLaBUBkaFo6kIYGh1gesFOWho1demqqauWIrtPZApQFUId1DygGIhEtQNDTGQKaysrml7WAMYQoJtDAwZB+FERYnEfAL1ozYzuUisGAAAAAElFTkSuQmCC',
			'FA4A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7QkMZAhgaHVqRxQIaGEMYWh2mOqCIsbYyTHUICEARE2l0CHR0EEFyX2jUtJWZmZlZ05DcB1Ln2ghXBxUTDXUNDQwNQTcPQx1xYgMVflSEWNwHAOvPzowXIJOKAAAAAElFTkSuQmCC',
			'38D7' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7RAMYQ1hDGUNDkMQCprC2sjY6NIggq2wVaXRtCEAVA6kDigUguW9l1MqwpauiVmYhuw+irpUB07wpWMQCGDDc4uiAxc0oYgMVflSEWNwHAPUIzJlTTHmUAAAAAElFTkSuQmCC',
			'62EF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDHUNDkMREprC2sjYwOiCrC2gRaXRFF2tgQBYDOykyatXSpaErQ7OQ3BcyhWEKhnmtDAGYYowO6GJAtzSgi7EGiIa6hjqiiA1U+FERYnEfAKZFyTEKWEn9AAAAAElFTkSuQmCC',
			'4E61' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpI37poiGMoQytKKIhYg0MDo6TEUWYwSKsTY4hCKLsU4BicH1gp00bdrUsKVTVy1Fdl8ASJ2jA4odoaEgvQGo9k7BLsaIphfq5tCAwRB+1INY3AcA+lbLaiCELPkAAAAASUVORK5CYII=',
			'5330' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7QkNYQxhDGVqRxQIaRFpZGx2mOqCIMTQ6NAQEBCCJBQYA9TU6OogguS9s2qqwVVNXZk1Ddl8rijqYGNC8QBSxgFZMO0SmYLqFNQDTzQMVflSEWNwHAJKBzTOAXxFpAAAAAElFTkSuQmCC',
			'B633' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7QgMYQxhDGUIdkMQCprC2sjY6OgQgi7WKNALJBhEUdUBeo0NDAJL7QqOmha2aumppFpL7AqaItiKpg5vngG4eNjEsbsHm5oEKPypCLO4DANunzzStCJwgAAAAAElFTkSuQmCC'        
        );
        $this->text = array_rand( $images );
        return $images[ $this->text ] ;    
    }
    
    function out_processing_gif(){
        $image = dirname(__FILE__) . '/processing.gif';
        $base64_image = "R0lGODlhFAAUALMIAPh2AP+TMsZiALlcAKNOAOp4ANVqAP+PFv///wAAAAAAAAAAAAAAAAAAAAAAAAAAACH/C05FVFNDQVBFMi4wAwEAAAAh+QQFCgAIACwAAAAAFAAUAAAEUxDJSau9iBDMtebTMEjehgTBJYqkiaLWOlZvGs8WDO6UIPCHw8TnAwWDEuKPcxQml0Ynj2cwYACAS7VqwWItWyuiUJB4s2AxmWxGg9bl6YQtl0cAACH5BAUKAAgALAEAAQASABIAAAROEMkpx6A4W5upENUmEQT2feFIltMJYivbvhnZ3Z1h4FMQIDodz+cL7nDEn5CH8DGZhcLtcMBEoxkqlXKVIgAAibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkphaA4W5upMdUmDQP2feFIltMJYivbvhnZ3V1R4BNBIDodz+cL7nDEn5CH8DGZAMAtEMBEoxkqlXKVIg4HibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpjaE4W5tpKdUmCQL2feFIltMJYivbvhnZ3R0A4NMwIDodz+cL7nDEn5CH8DGZh8ONQMBEoxkqlXKVIgIBibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpS6E4W5spANUmGQb2feFIltMJYivbvhnZ3d1x4JMgIDodz+cL7nDEn5CH8DGZgcBtMMBEoxkqlXKVIggEibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpAaA4W5vpOdUmFQX2feFIltMJYivbvhnZ3V0Q4JNhIDodz+cL7nDEn5CH8DGZBMJNIMBEoxkqlXKVIgYDibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpz6E4W5tpCNUmAQD2feFIltMJYivbvhnZ3R1B4FNRIDodz+cL7nDEn5CH8DGZg8HNYMBEoxkqlXKVIgQCibbK9YLBYvLtHH5K0J0IACH5BAkKAAgALAEAAQASABIAAAROEMkpQ6A4W5spIdUmHQf2feFIltMJYivbvhnZ3d0w4BMAIDodz+cL7nDEn5CH8DGZAsGtUMBEoxkqlXKVIgwGibbK9YLBYvLtHH5K0J0IADs=";
        $binary = is_file($image) ? join("",file($image)) : base64_decode($base64_image); 
        header("Cache-Control: post-check=0, pre-check=0, max-age=0, no-store, no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-type: image/gif");
        echo $binary;
    }

}
# end of class phpfmgImage
# ------------------------------------------------------
# end of module : captcha


# module user
# ------------------------------------------------------
function phpfmg_user_isLogin(){
    return ( isset($_SESSION['authenticated']) && true === $_SESSION['authenticated'] );
}


function phpfmg_user_logout(){
    session_destroy();
    header("Location: admin.php");
}

function phpfmg_user_login()
{
    if( phpfmg_user_isLogin() ){
        return true ;
    };
    
    $sErr = "" ;
    if( 'Y' == $_POST['formmail_submit'] ){
        if(
            defined( 'PHPFMG_USER' ) && strtolower(PHPFMG_USER) == strtolower($_POST['Username']) &&
            defined( 'PHPFMG_PW' )   && strtolower(PHPFMG_PW) == strtolower($_POST['Password']) 
        ){
             $_SESSION['authenticated'] = true ;
             return true ;
             
        }else{
            $sErr = 'Login failed. Please try again.';
        }
    };
    
    // show login form 
    phpfmg_admin_header();
?>
<form name="frmFormMail" action="" method='post' enctype='multipart/form-data'>
<input type='hidden' name='formmail_submit' value='Y'>
<br><br><br>

<center>
<div style="width:380px;height:260px;">
<fieldset style="padding:18px;" >
<table cellspacing='3' cellpadding='3' border='0' >
	<tr>
		<td class="form_field" valign='top' align='right'>Email :</td>
		<td class="form_text">
            <input type="text" name="Username"  value="<?php echo $_POST['Username']; ?>" class='text_box' >
		</td>
	</tr>

	<tr>
		<td class="form_field" valign='top' align='right'>Password :</td>
		<td class="form_text">
            <input type="password" name="Password"  value="" class='text_box'>
		</td>
	</tr>

	<tr><td colspan=3 align='center'>
        <input type='submit' value='Login'><br><br>
        <?php if( $sErr ) echo "<span style='color:red;font-weight:bold;'>{$sErr}</span><br><br>\n"; ?>
        <a href="admin.php?mod=mail&func=request_password">I forgot my password</a>   
    </td></tr>
</table>
</fieldset>
</div>
<script type="text/javascript">
    document.frmFormMail.Username.focus();
</script>
</form>
<?php
    phpfmg_admin_footer();
}


function phpfmg_mail_request_password(){
    $sErr = '';
    if( $_POST['formmail_submit'] == 'Y' ){
        if( strtoupper(trim($_POST['Username'])) == strtoupper(trim(PHPFMG_USER)) ){
            phpfmg_mail_password();
            exit;
        }else{
            $sErr = "Failed to verify your email.";
        };
    };
    
    $n1 = strpos(PHPFMG_USER,'@');
    $n2 = strrpos(PHPFMG_USER,'.');
    $email = substr(PHPFMG_USER,0,1) . str_repeat('*',$n1-1) . 
            '@' . substr(PHPFMG_USER,$n1+1,1) . str_repeat('*',$n2-$n1-2) . 
            '.' . substr(PHPFMG_USER,$n2+1,1) . str_repeat('*',strlen(PHPFMG_USER)-$n2-2) ;


    phpfmg_admin_header("Request Password of Email Form Admin Panel");
?>
<form name="frmRequestPassword" action="admin.php?mod=mail&func=request_password" method='post' enctype='multipart/form-data'>
<input type='hidden' name='formmail_submit' value='Y'>
<br><br><br>

<center>
<div style="width:580px;height:260px;text-align:left;">
<fieldset style="padding:18px;" >
<legend>Request Password</legend>
Enter Email Address <b><?php echo strtoupper($email) ;?></b>:<br />
<input type="text" name="Username"  value="<?php echo $_POST['Username']; ?>" style="width:380px;">
<input type='submit' value='Verify'><br>
The password will be sent to this email address. 
<?php if( $sErr ) echo "<br /><br /><span style='color:red;font-weight:bold;'>{$sErr}</span><br><br>\n"; ?>
</fieldset>
</div>
<script type="text/javascript">
    document.frmRequestPassword.Username.focus();
</script>
</form>
<?php
    phpfmg_admin_footer();    
}


function phpfmg_mail_password(){
    phpfmg_admin_header();
    if( defined( 'PHPFMG_USER' ) && defined( 'PHPFMG_PW' ) ){
        $body = "Here is the password for your form admin panel:\n\nUsername: " . PHPFMG_USER . "\nPassword: " . PHPFMG_PW . "\n\n" ;
        if( 'html' == PHPFMG_MAIL_TYPE )
            $body = nl2br($body);
        mailAttachments( PHPFMG_USER, "Password for Your Form Admin Panel", $body, PHPFMG_USER, 'You', "You <" . PHPFMG_USER . ">" );
        echo "<center>Your password has been sent.<br><br><a href='admin.php'>Click here to login again</a></center>";
    };   
    phpfmg_admin_footer();
}


function phpfmg_writable_check(){
 
    if( is_writable( dirname(PHPFMG_SAVE_FILE) ) && is_writable( dirname(PHPFMG_EMAILS_LOGFILE) )  ){
        return ;
    };
?>
<style type="text/css">
    .fmg_warning{
        background-color: #F4F6E5;
        border: 1px dashed #ff0000;
        padding: 16px;
        color : black;
        margin: 10px;
        line-height: 180%;
        width:80%;
    }
    
    .fmg_warning_title{
        font-weight: bold;
    }

</style>
<br><br>
<div class="fmg_warning">
    <div class="fmg_warning_title">Your form data or email traffic log is NOT saving.</div>
    The form data (<?php echo PHPFMG_SAVE_FILE ?>) and email traffic log (<?php echo PHPFMG_EMAILS_LOGFILE?>) will be created automatically when the form is submitted. 
    However, the script doesn't have writable permission to create those files. In order to save your valuable information, please set the directory to writable.
     If you don't know how to do it, please ask for help from your web Administrator or Technical Support of your hosting company.   
</div>
<br><br>
<?php
}


function phpfmg_log_view(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );
    
    phpfmg_admin_header();
   
    $file = $files[$n];
    if( is_file($file) ){
        if( 1== $n ){
            echo "<pre>\n";
            echo join("",file($file) );
            echo "</pre>\n";
        }else{
            $man = new phpfmgDataManager();
            $man->displayRecords();
        };
     

    }else{
        echo "<b>No form data found.</b>";
    };
    phpfmg_admin_footer();
}


function phpfmg_log_download(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );

    $file = $files[$n];
    if( is_file($file) ){
        phpfmg_util_download( $file, PHPFMG_SAVE_FILE == $file ? 'form-data.csv' : 'email-traffics.txt', true, 1 ); // skip the first line
    }else{
        phpfmg_admin_header();
        echo "<b>No email traffic log found.</b>";
        phpfmg_admin_footer();
    };

}


function phpfmg_log_delete(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );
    phpfmg_admin_header();

    $file = $files[$n];
    if( is_file($file) ){
        echo unlink($file) ? "It has been deleted!" : "Failed to delete!" ;
    };
    phpfmg_admin_footer();
}


function phpfmg_util_download($file, $filename='', $toCSV = false, $skipN = 0 ){
    if (!is_file($file)) return false ;

    set_time_limit(0);


    $buffer = "";
    $i = 0 ;
    $fp = @fopen($file, 'rb');
    while( !feof($fp)) { 
        $i ++ ;
        $line = fgets($fp);
        if($i > $skipN){ // skip lines
            if( $toCSV ){ 
              $line = str_replace( chr(0x09), ',', $line );
              $buffer .= phpfmg_data2record( $line, false );
            }else{
                $buffer .= $line;
            };
        }; 
    }; 
    fclose ($fp);
  

    
    /*
        If the Content-Length is NOT THE SAME SIZE as the real conent output, Windows+IIS might be hung!!
    */
    $len = strlen($buffer);
    $filename = basename( '' == $filename ? $file : $filename );
    $file_extension = strtolower(substr(strrchr($filename,"."),1));

    switch( $file_extension ) {
        case "pdf": $ctype="application/pdf"; break;
        case "exe": $ctype="application/octet-stream"; break;
        case "zip": $ctype="application/zip"; break;
        case "doc": $ctype="application/msword"; break;
        case "xls": $ctype="application/vnd.ms-excel"; break;
        case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
        case "gif": $ctype="image/gif"; break;
        case "png": $ctype="image/png"; break;
        case "jpeg":
        case "jpg": $ctype="image/jpg"; break;
        case "mp3": $ctype="audio/mpeg"; break;
        case "wav": $ctype="audio/x-wav"; break;
        case "mpeg":
        case "mpg":
        case "mpe": $ctype="video/mpeg"; break;
        case "mov": $ctype="video/quicktime"; break;
        case "avi": $ctype="video/x-msvideo"; break;
        //The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)
        case "php":
        case "htm":
        case "html": 
                $ctype="text/plain"; break;
        default: 
            $ctype="application/x-download";
    }
                                            

    //Begin writing headers
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: public"); 
    header("Content-Description: File Transfer");
    //Use the switch-generated Content-Type
    header("Content-Type: $ctype");
    //Force the download
    header("Content-Disposition: attachment; filename=".$filename.";" );
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: ".$len);
    
    while (@ob_end_clean()); // no output buffering !
    flush();
    echo $buffer ;
    
    return true;
 
    
}
?>