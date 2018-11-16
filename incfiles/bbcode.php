<?php
function atuser_search_name($user)
{
$atuser_q = mysql_query("SELECT `username` FROM `users` WHERE `username`='$user'");
$atuser_n = mysql_num_rows($atuser_q);
$at = mysql_fetch_object($atuser_q);

if($atuser_n == 0)
{
return "@$user";
}
else
{
return '@'.functions::user_link($at->username);
}
}
function atuser_name($matches){
while($m = $matches[2]){
return atuser_search_name($m);
}
}

function at_user($text){
return preg_replace_callback("/([@]+)([a-zA-z-0-9]+)/", "atuser_name", $text);
}

class bbcode extends core{
private $string;
   
function __construct($text){
$this->string = $text;
}

public function hide()
{
return '<blockquote>This post has been hidden</blockquote>';
}


private function code($php)
{
$ph=$php;
$php = strtr($php, array('<br />' => '', '\\' => '\\'));
$php = html_entity_decode(trim($php), ENT_QUOTES, 'UTF-8');
$php = substr($php, 0, 2) != "<?" ? "<?php\n" . $php . "\n?>" : $php;
$php = highlight_string(stripslashes($php), true);
$php = strtr($php, array('\\' => '&#92;', ':' => '&#58;', '[' => '&#91;'));
return "<div class='code'><code>$php</code><br /><b><u><font color='red'>Copy Code:</font><br /></u></b><textarea cols='30'>$ph</textarea></div>";
}


private function linkify($value, $protocols = array('http', 'mail', 'https'), array $attributes = array(), $mode = 'normal')
{
            // Link attributes
$attr = '';
foreach ($attributes as $key => $val) {
$attr = ' ' . $key . '="' . htmlentities($val) . '"';
}
            
$links = array();
            
// Extract existing links and tags
$value = preg_replace_callback('~(<a .*?>.*?</a>|<.*?>)~i', function ($match) use (&$links) { return '<' . array_push($links, $match[1]) . '>'; }, $value);
 // Extract text links for each protocol
 foreach ((array)$protocols as $protocol) {
switch ($protocol) {
case 'http':
case 'https': 
$value = preg_replace_callback($mode != 'all' ? '~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i' : '~([^\s<]+\.[^\s<]+)(?<![\.,:])~i', function ($match) use ($protocol, &$links, $attr) { if ($match[1]) $protocol = $match[1]; $link = $match[2] ?: $match[3]; return '<' . array_push($links, '<a' . $attr . ' href="' . $protocol . '://' . $link . '">' . $link . '</a>') . '>'; }, $value); break;
case 'mail': 
$value = preg_replace_callback('~([^\s<]+?@[^\s<]+?\.[^\s<]+)(?<![\.,:])~', function ($match) use (&$links, $attr) { return '<' . array_push($links, '<a' . $attr . ' href="mailto:' . $match[1] . '">' . $match[1] . '</a>') . '>'; }, $value); break;
case 'twitter': $value = preg_replace_callback('~(?<!\w)[@#](\w++)~', function ($match) use (&$links, $attr) { return '<' . array_push($links, '<a' . $attr . ' href="https://twitter.com/' . ($match[0][0] == '@' ? '' : 'search/%23') . $match[1] . '">' . $match[0] . '</a>') . '>'; }, $value); 
break;
default: $value = preg_replace_callback($mode != 'all' ? '~' . preg_quote($protocol, '~') . '://([^\s<]+?)(?<![\.,:])~i' : '~([^\s<]+)(?<![\.,:])~i', function ($match) use ($protocol, &$links, $attr) { return '<' . array_push($links, '<a' . $attr . ' href="' . $protocol . '://' . $match[1] . '">' . $match[1] . '</a>') . '>'; }, $value); break;
}
}
            
 // Insert all link
 return preg_replace_callback('/<(\d+)>/', function ($match) use (&$links) { return $links[$match[1] - 1]; }, $value);
}

private function bbcode($string){
$string = preg_replace("(\[b\](.+?)\[\/b])is",'<b>$1</b>',$string);
$string = preg_replace("(\[br\])is",'<br>',$string);
$string = preg_replace("(\[hr\])is",'<hr>',$string);
$string = preg_replace("(\[i\](.+?)\[\/i])is",'<i>$1</i>',$string);
$string = preg_replace("(\[s\](.+?)\[\/s])is",'<s>$1</s>',$string);
$string = preg_replace("(\[left\](.+?)\[\/left])is",'<left>$1</left>',$string);
$string = preg_replace("(\[right\](.+?)\[\/right])is",'<right>$1</right>',$string);
$string = preg_replace("(\[center\](.+?)\[\/center])is",'<center>$1</center>',$string);
$string = preg_replace("(\[size=(.+?)\](.+?)\[\/size\])is","<font size='$1'>$2</font>",$string);
$string = str_replace('\r\n', '<br>', $string);
$string = preg_replace("(\[color=(.+?)\](.+?)\[\/color\])is","<font color='$1'>$2</font>",$string);
$string = preg_replace("(\[font=(.+?)\](.+?)\[\/font\])is","<font face='$1'>$2</font>",$string);
$string = preg_replace("/\[img\](.+?)\[\/img\]/", '<img src="$1" width="100%"/>', $string);
$string = preg_replace("/\[img=(.+?)\]\[\/img\]/", '<img src="$1" width="100%"/>', $string);
$string = preg_replace("/\[img\](.+?)\[\/img\]/", '<img src="$1" width="100%"/>', $string);
$urlstring = " a-zA-Z0-9\:\&\/\-\?\.\_\=\~\#\'\%";
$string = preg_replace("(\[url\]([$urlstring]*)\[/url\])", '<a href="$1" target="_blank">$1</a>', $string);
$string = preg_replace("(\[url=(.+?)\]([$urlstring]*)\[/url\])", '<a href="$1" target="_blank">$2</a>', $string);
$string = preg_replace("(\[email\](.+?)\[\/email])is",'<email>$1</email>',$string);
$string = preg_replace("(\[sub\](.+?)\[\/sub])is",'<sub>$1</sub>',$string);
$string = preg_replace("(\[sup\](.+?)\[\/sup])is",'<sup>$1</sup>',$string);
$string=preg_replace(array('#\[\/code\](.+?)\[\/code\]#se'), array("''.$this->code('$1').''"), str_replace("]\n", "]", $string));
$string = preg_replace("(\[quote\](.+?)\[\/quote])is","<blockquote>$1</blockquote>",$string);
$string = preg_replace("(\[quote author=(.+?)\](.+?)\[\/quote\])is","<blockquote><b>$1:</b>$2</blockquote>",$string);
$string=nl2br($string);
return $string;
}



private function smiley($string)
{
$smiles=array(':)'=>"<img src='/smileys/smiley.gif' alt=':)'/>",
';)'=>"<img src='/smileys/wink.gif' alt=';)'/>",
':CH'=>"<img src='/smileys/cheesy.gif' alt=':CH' />",
';GR'=>"<img src='/smileys/grin.gif' alt=';GR' />",
'>:('=>"<img src='/smileys/angry.gif' alt='>:(' />",
':('=>"<img src='/smileys/sad.gif' alt=':(' />",
':o'=>"<img src='/smileys/shocked.gif' alt=':o' />",
'8)'=>"<img src='/smileys/cool.gif' alt='8)' />",
'???'=>"<img src='/smileys/huh.gif' alt='???' />",
':TP'=>"<img src='/smileys/tongue.gif' alt=':TP' />",
':-['=>"<img src='/smileys/embarassed.gif' alt=':-[' />",
':-XL'=>"<img src='/smileys/lipsrsealed.png' alt=':-XL' />",
':-\\'=>"<img src='/smileys/undecided.gif' alt=':-\\' />",
':-*'=>"<img src='/smileys/kiss.gif' alt=':-*' />",
':::('=>"<img src='/smileys/cry.gif' alt=':::(' />",
'(stop)'=>"<img src='$config->url/addons/smileys/stop.gif' alt='(stop)' />",
'(1st)'=>"<img src='$config->url/addons/smileys/1st.gif' alt='(1st)' />",
'(2nd)'=>"<img src='$config->url/addons/smileys/2nd.gif' alt='(2nd)' />",
'(3rd)'=>"<img src='$config->url/addons/smileys/3rd.gif' alt='(3rd)' />",
'(best)'=>"<img src='$config->url/addons/smileys/best.gif' alt='(best)' />",
'(block)'=>"<img src='$config->url/addons/smileys/block.gif' alt='(block)' />",
'(clap)'=>"<img src='$config->url/addons/smileys/clap.gif' alt='(clap)' />",
'(cool)'=>"<img src='$config->url/addons/smileys/cool.gif' alt='(cool)' />",
'(thumbsup)'=>"<img src='$config->url/addons/smileys/thumbsup.gif' alt='(thumbsup)' />",
'(blackhat)'=>"<img src='$config->url/addons/smileys/blackhat.gif' alt='(blackhat)' />",
'(devilish)'=>"<img src='$config->url/addons/smileys/devilish.gif' alt='(devilish)' />",
'(superman)'=>"<img src='$config->url/addons/smileys/superman.gif' alt='(superman)' />",
'(tongueup)'=>"<img src='$config->url/addons/smileys/tongueup.gif' alt='(tongueup)' />",
'(welcome2)'=>"<img src='$config->url/addons/smileys/welcome2.gif' alt='(welcome2)' />",
'(yeye)'=>"<img src='$config->url/addons/smileys/yeye.gif' alt='(yeye)' />",
'(barca)'=>"<img src='$config->url/addons/smileys/barca.jpeg' alt='(barca)' />",
'(cheesy)'=>"<img src='$config->url/addons/smileys/cheesy.gif' alt='(cheesy)' />",
'(cool)'=>"<img src='$config->url/addons/smileys/cool.gif' alt='(cool)' />",
'(cry)'=>"<img src='$config->url/addons/smileys/cry.gif' alt='(cry)' />",
'(drunk)'=>"<img src='$config->url/addons/smileys/drunk.gif' alt='(drunk)' />",
'(goodnight)'=>"<img src='$config->url/addons/smileys/goodnight.gif' alt='(goodnight)' />",
'(huh)'=>"<img src='$config->url/addons/smileys/huh.gif' alt='(huh)' />",
'(lies)'=>"<img src='$config->url/addons/smileys/lies.gif' alt='(lies)' />",
'(liverpool)'=>"<img src='$config->url/addons/smileys/liverpool.gif' alt='(liverpool)' />",
'(lol)'=>"<img src='$config->url/addons/smileys/lol.png' alt='(lol)' />",
'(love)'=>"<img src='$config->url/addons/smileys/love.png' alt='(love)' />",
'(love2)'=>"<img src='$config->url/addons/smileys/love-flower.gif' alt='(love2)' />",
'(madrid)'=>"<img src='$config->url/addons/smileys/real.png' alt='(madrid)' />",
'(br)'=>"<br/><br/>",
'(br/)'=>"<br/>",
'(tongue)'=>"<img src='$config->url/addons/smileys/tongue.gif' alt='(tongue)' />",
'(nicethread)'=>"<img src='$config->url/addons/smileys/nicethread.gif' alt='(nicethread)' />");

$string=str_replace(array_keys($smiles), array_values($smiles), $string);
return $string;
}
public function display() {
return $this->linkify(at_user($this->bbcode($this->smiley($this->string))));
}

}

   
?>