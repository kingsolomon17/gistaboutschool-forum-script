<?php
require('incfiles/init.php');

$id = (int)$_GET["board"];
$num=mysql_num_rows(mysql_query("SELECT * FROM boards WHERE id=$id"));

if($id < 1 || $num < 1)
{
$pagetitle = "forum category not found";
require('incfiles/head.php');
functions::display_error('forum category not found');
require('incfiles/end.php');
die();
}

$banq = mysql_num_rows(mysql_query("SELECT * FROM `bannedusers` WHERE `username`='$user' AND `boardid`=$id"));
if($banq > 0)
{
$binfo = mysql_fetch_array(mysql_query("SELECT * FROM `bannedusers` WHERE username='$user' AND boardid=$id"));
$reason = $binfo["reason"];
$date = $binfo["date"];
$unbandate = $binfo["unbandate"];
$today = time();
if($today < $unbandate) {
$pagetitle = "You Have Been Banished By one of the Administrator From Posting On This Board!.";
require('incfiles/head.php');
$ud = date('l jS F Y \a\t g:i sa', $unbandate);
$bd = date('l jS F Y \a\t g:i sa', $date);
echo "<h2>You Have Been Banished By one of the Administrator From Posting On This Board!.</h2><div class='display'><p>Reason: $reason<p>Banned Date: $bd<p>Unbanned Date: $ud</div>";
require('incfiles/end.php');
die();
} else {
mysql_query("DELETE FROM bannedusers WHERE username='$user' AND boardid=$id");
$pagetitle = "You have Just Been Unbanned";
require('incfiles/head.php');
$msg="You Have Just Been Unbanned Please Refresh This Page";
echo "<div class='display'>$msg<br/></div>";
}
}

if(!functions::isloggedin())
{
functions::go("/login?redirect=$self");
die();
}

$modcheck = mysql_num_rows(mysql_query("SELECT * FROM moderators WHERE username = '$user' AND boardid = '$id'"));

$admincheck = mysql_num_rows(mysql_query("SELECT * FROM admins WHERE username = '$user'"));

$info = mysql_fetch_array(mysql_query("SELECT * FROM boards WHERE id=$id"));
$locked = $info["locked"];
$name = $info["name"];
$type = $info["type"];


if($type == child)
{
$typeid = $info["typeid"];
$qquery = mysql_fetch_array(mysql_query("SELECT * FROM boards WHERE id=$typeid"));
$ftype = $qquery["type"];
if($ftype == child)
{
$typeid2 = $qquery["typeid"];
$qqquery = mysql_fetch_array(mysql_query("SELECT * FROM boards WHERE id='$typeid2'"));

$typename = ' / <a href="'.urls::board($qqquery['name'], $typeid2).'">'.ucwords($qqquery['name']).'</a>';
} else {
$typename = '';
}

$typename .= ' / <a href="'.urls::board($qquery['name'], $typeid).'">'.ucwords($qquery['name']).'</a>';
} else {
$typename .= '';
}

if($locked > 0 && $admincheck < 1 && $modcheck < 1)
{
$pagetitle = "This Board As Been Locked From Creating New Topic Please Try Again Later";
require('incfiles/head.php');
functions::display_error('This Board As Been Locked From Creating New Topic Please Try Again Later');
require('incfiles/end.php');
die();
}

$pagetitle = "Create New Thread";
$javascript = '<script type="text/javascript" src="http://www.google-analytics.com/ga.js"></script><script type="text/javascript" src="/static/javascript.js"></script>';
require('incfiles/head.php');

echo '<h2>New Topic - '.$name.', '.$config->title.' Forum</h2><p class="bold"><a href="/">'.$config->title.' Forum</a>'.$typename.' / <a href="'.urls::board($name, $id).'">'.$name.'</a> / <a href="">New Topic</a><p>';
echo "<table summary='topic posting form'><tbody><tr><td class='l'><b>Please Observe The Following Rules:</b> (<a href='#skip'>skip</a>)<br>1. Please post on topic always. Don't derail or tribalize threads.<br>2. Don't abuse, bully, deliberately insult/provoke, fight, or wish harm to any ".$config->title." member.<br>3. Don't advocate or encourage violent actions against any person, tribe, race, or group of people.<br>4. Discussions of the art of love-making should be restricted to the hidden sexuality section.<br>5. Don't post pornographic or disgusting pictures or videos on any section of ".$config->title.".<br>6. Don't post adverts or affiliate links outside the areas where adverts are explicitly allowed.<br>7. Don't say or do anything that's detrimental to the security or success of ".$config->title.".<br>8. Don't post false information on ".$config->title.".<br>9. Don't use ".$config->title." for illegal acts, e.g fraud, piracy, and spreading malware.<br>10. Don't expose the identity or post pictures of any ".$config->title." member without his/her consent.<br>11. Don't create distracting posts e.g. posts in giant fonts or ALL CAPS or with silly gifs.<br>12. Don't insert promotional signatures into your posts. Use the signature feature.<br>13. Please report any post or topic that violates the rules of ".$config->title.".<br>14. Please search the forum before creating a new thread on ".$config->title.".<br>15. Don't attempt to post censored words by misspelling them.<br>16. Don't promote MLM schemes, HYIPS, or other questionable schemes on ".$config->title.".<br>18. Don't spam the forum by posting the same content many times.<br>19. Don't create a new account when banned for breaking a rule. If you do, make sure we don't find out.<br>20. Please cooperate with the moderators, super-moderators, and administrator. Treat them with respect. <br>21. Please spell words correctly when you post, and try to use perfect grammar and punctuation.<a name='skip'></a>";
echo '<form method="POST" action="/do_newtopic" id="postform" name="postform" enctype="multipart/form-data">
<p><b>Subject</b>: <input type="text" name="title" id="postformtitle" maxlength="80">
<script type="text/javascript">document.postform.title.focus()</script>
<p><b>Message</b>:
<div id="editbar" style="display: block">';
?>
<a href="javascript:void(0);" onclick='wrapText("body", "[b]", "[/b]")' title="Bold">
<span class="eb"><img src="/icons/bold.gif"></span></a>
<a href="javascript:void(0);" onclick='wrapText("body", "[i]", "[/i]")' title="Italic">
<span class="eb"><img src="/icons/italicize.gif"></span></a>
<a href="javascript:void(0);" onclick='wrapText("body", "[s]", "[/s]")' title="Strikethrough">
<span class="eb"><img src="/icons/strike.gif"></span></a>
<a href="javascript:void(0);" onclick='wrapText("body", "[left]", "[/left]")' title="Align Left">
<span class="eb"><img src="/icons/left.gif"></span></a>
<a href="javascript:void(0);" onclick='wrapText("body", "[right]", "[/right]")' title="Align Right">
<span class="eb"><img src="/icons/right.gif"></span></a>
<a href="javascript:void(0);" onclick='wrapText("body", "[center]", "[/center]")' title="Align Center">
<span class="eb"><img src="/icons/center.gif"></span></a>
<a href="javascript:void(0);" onclick='addText("body", "[hr]")' title="Horizontal Rule">
<span class="eb"><img src="/icons/hr.gif"></span></a>
<a href="javascript:void(0);" onclick='wrapText("body", "[size=8pt]", "[/size]")' title="Font Size">
<span class="eb"><img src="/icons/size.gif"></span></a>
<a href="javascript:void(0);" onclick='wrapText("body", "[font=Lucida Sans Unicode]", "[/font]")' title="Font Face">
<span class="eb"><img src="/icons/face.gif"></span></a>
<a href="javascript:void(0);" onclick='wrapText("body", "[img]", "[/img]")' title="Insert Image/Picture">
<span class="eb"><img src="/icons/img.gif"></span></a>
<a href="javascript:void(0);" onclick='wrapText("body", "[url]", "[/url]")' title="Insert Hyperlink">
<span class="eb"><img src="/icons/url.gif"></span></a>
<a href="javascript:void(0);" onclick='wrapText("body", "[email]", "[/email]")' title="Insert Email">
<span class="eb"><img src="/icons/email.gif"></span></a>
<a href="javascript:void(0);" onclick='wrapText("body", "[sub]", "[/sub]")' title="Subscript">
<span class="eb"><img src="/icons/sub.gif"></span></a>
<a href="javascript:void(0);" onclick='wrapText("body", "[sup]", "[/sup]")' title="Superscript">
<span class="eb"><img src="/icons/sup.gif"></span></a>
<a href="javascript:void(0);" onclick='wrapText("body", "[code]", "[/code]")' title="Code">
<span class="eb"><img src="/icons/code.gif"></span></a>
<a href="javascript:void(0);" onclick='wrapText("body", "[quote]", "[/quote]")' title="Quote">
<span class="eb"><img src="/icons/quote.gif"></span></a>
<a href="javascript:void(0);" onclick='addText("body", " :)")'><img src="/smileys/smiley.gif" class="smiley"></a>
<a href="javascript:void(0);" onclick='addText("body", " ;)")'><img src="/smileys/wink.gif" class="smiley"></a>
<a href="javascript:void(0);" onclick='addText("body", " :D")'><img src="/smileys/cheesy.gif" class="smiley"></a>
<a href="javascript:void(0);" onclick='addText("body", " ;D")'><img src="/smileys/grin.gif" class="smiley"></a>
<a href="javascript:void(0);" onclick='addText("body", " >:(")'><img src="/smileys/angry.gif" class="smiley"></a>
<a href="javascript:void(0);" onclick='addText("body", " :(")'><img src="/smileys/sad.gif" class="smiley"></a>
<a href="javascript:void(0);" onclick='addText("body", " :o")'><img src="/smileys/shocked.gif" class="smiley"></a>
<a href="javascript:void(0);" onclick='addText("body", " 8)")'><img src="/smileys/cool.gif" class="smiley"></a>
<a href="javascript:void(0);" onclick='addText("body", " ???")'><img src="/smileys/huh.gif" style="width:15px;height:22px;"></a>
<a href="javascript:void(0);" onclick='addText("body", " :kiss.me:")'><img src="/smileys/kiss.me.gif" style="width:15px;height:22px;"></a>
<a href="javascript:void(0);" onclick='addText("body", " :you kiss:")'><img src="/smileys/tongue.gif" class="smiley"></a>
<a href="javascript:void(0);" onclick='addText("body", " :-[")'><img src="/smileys/embarassed.gif" class="smiley"></a>
<a href="javascript:void(0);" onclick='addText("body", " :-X")'><img src="/smileys/lipsrsealed.png" class="smiley"></a>
<a href="javascript:void(0);" onclick='addText("body", " :-\\")'><img src="/smileys/undecided.gif" class="smiley"></a>
<a href="javascript:void(0);" onclick='addText("body", " :-*")'><img src="/smileys/kiss.gif" class="smiley"></a>
<a href="javascript:void(0);" onclick="addText(&quot;body&quot;, &quot; :'(&quot;)"><img src="/smileys/cry.gif" class="smiley"></a>
<select onchange="wrapText('body', '[color='+this.options[this.selectedIndex].value+']', '[/color]'); this.selectedIndex = 0;" style="margin-bottom: 1ex;"><option value="" selected="selected">Change Color</option><option value="#990000">Red</option><option value="#006600">Green</option><option value="#000099">Blue</option><option value="#770077">Purple</option><option value="#550000">Brown</option><option value="#000000">Black</option></select>
</div>
<script>document.getElementById("editbar").style.display = 'block';</script>
<textarea rows="12" cols="80" name="body" id="body"></textarea><p>
<input type="submit" name="submit" value="Submit" accesskey="s">
<p>
<?php
echo '<!--<button onclick="previewclick();" type="button">Preview</button>-->
<input type="hidden" name="session" value="'.$session.'">
<input type="hidden" name="board" value="'.$id.'">
<div id="attachments" class="clearfix">
<input type="file" name="attachment[]"><br>
<input type="file" name="attachment[]"><br>
<input type="file" name="attachment[]"><br>
<input type="file" name="attachment[]">
</div>
</form></table>';

require('incfiles/end.php');
?>