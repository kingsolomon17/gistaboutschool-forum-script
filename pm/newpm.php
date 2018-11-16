<?php 
require('../incfiles/init.php');
if(!functions::isloggedin())
{
functions::go("/login?redirect=$self");
die();
}
$receiver = functions::cleaninput($_GET["user"]);

$ucheck = mysql_num_rows(mysql_query("SELECT `username` FROM `users` WHERE `username`='$receiver'"));

if($ucheck != 1)
{
$pagetitle = "User Not Exist";
require('../incfiles/head.php');
functions::display_error('User Not Exist');
require('../incfiles/end.php');
die();
}

if($receiver == $user)
{
$pagetitle = "You Can't Send Message To Your Self";
require('../incfiles/head.php');
functions::display_error('You '."Can't".' Send Message To Your Self');
require('../incfiles/end.php');
die();
}

$pagetitle = "Send Private Message To $receiver";
require('../incfiles/head.php');
echo '<p><h2>Send Private Message To '.$receiver.'</h2><p><a href="/">'.$config->title.' Forum</a> / <a href="/pm">Private Message</a> / <a href="#">Send Private Message To '.$receiver.'</a><p><div class="display"><form method="POST" action="/pm/do_newpm" id="postform" name="postform">
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
<a href="javascript:void(0);" onclick='addText("body", " :P")'><img src="/smileys/tongue.gif" class="smiley"></a>
<a href="javascript:void(0);" onclick='addText("body", " :-[")'><img src="/smileys/embarassed.gif" class="smiley"></a>
<a href="javascript:void(0);" onclick='addText("body", " :-X")'><img src="/smileys/lipsrsealed.gif" class="smiley"></a>
<a href="javascript:void(0);" onclick='addText("body", " :-\\")'><img src="/smileys/undecided.gif" class="smiley"></a>
<a href="javascript:void(0);" onclick='addText("body", " :-*")'><img src="/smileys/kiss.gif" class="smiley"></a>
<a href="javascript:void(0);" onclick="addText(&quot;body&quot;, &quot; :'(&quot;)"><img src="/smileys/cry.gif" class="smiley"></a>
<select onchange="wrapText('body', '[color='+this.options[this.selectedIndex].value+']', '[/color]'); this.selectedIndex = 0;" style="margin-bottom: 1ex;"><option value="" selected="selected">Change Color</option><option value="#990000">Red</option><option value="#006600">Green</option><option value="#000099">Blue</option><option value="#770077">Purple</option><option value="#550000">Brown</option><option value="#000000">Black</option></select>
</div>
<script>document.getElementById("editbar").style.display = 'block';</script>
<?php
echo '<textarea rows="12" cols="80" name="body" id="body"></textarea><p>
<input type="hidden" name="session" value="'.$sessionkey.'">
<input type="hidden" name="receiver" value="'.$receiver.'">
<input type="submit" name="submit" value="Submit" accesskey="s">
<p></form></div>';
require('../incfiles/end.php');
?>