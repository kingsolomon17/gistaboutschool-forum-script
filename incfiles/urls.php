<?php
class urls extends core{
public static function converturl2($text)
{
$text = html_entity_decode(trim($text), ENT_QUOTES, 'UTF-8');  
$text=strtolower($text);
return $text;
}

public static function user($user)
{

return '/'.urlencode($user).'';
}

public static function board($name, $id, $page, $sort)
{
$query = mysql_fetch_array(mysql_query("SELECT * FROM boards WHERE id='$id'"));
$url = $query["url"];

If(isset($sort) && strlen($sort) > 0)
{
if(isset($page))
{
return '/'.self::converturl2($url).'/'.$sort.'/'.$page.'';
}
else
{
return '/'.self::converturl2($url).'/'.$sort.'';
}
} else {
if(isset($page))
{
return '/'.self::converturl2($url).'/'.$page.'';
}
else
{
return '/'.self::converturl2($url).'';
}

}


}


public static function topic($name, $id, $page)
{
if(isset($page))
{
return '/'.$id.'/'.functions::converturl($name).'/'.$page.'';
}
else
{
return '/'.$id.'/'.functions::converturl($name).'';
}
}

}

?>