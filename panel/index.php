<?php
require('../incfiles/init.php');
require('access.php');
$pagetitle = ''.($access == 'admin' ? 'Admin' : ''.($access == 'supermod' ? 'Super Moderator' : 'Moderator').'').' Panel';
require('../incfiles/head.php');
echo '<a name="top"></a><p><h2>'.($access == 'admin' ? 'Admin' : ''.($access == 'supermod' ? 'Super Moderator' : 'Moderator').'').' Panel</h2><p><a href="/">'.$config->title.' Forum</a> / <a href="/panel/">'.($access == 'admin' ? 'Admin' : ''.($access == 'supermod' ? 'Super Moderator' : 'Moderator').'').' Panel</a><p>';
echo '<table summary="w">';
if($access == 'admin')
{
echo '<tr><td class="w"><a href="boards.php">Manage Boards</a><tr><td class=""><a href="boards.php?action=add">Add Board</a><tr><td class="w"><a href="boards.php?action=addsub">Add SubBoard</a>';
}
if($access == admin)
{
$css = '';
} else {
$css = 'w';
}
echo '<tr><td class="'.$css.'"><a href="users.php">Manage Users</a>';
if($access == 'admin')
{
echo '<tr><td class="w"><a href="users.php?action=globalnotification">Send Global Notification</a><tr><td class=""><a href="users.php?action=globalemail">Send Global Email Message</a><tr><td class="w"><a href="users.php?action=emailinactiveusers">Email Inactive Members</a>
<tr><td class=""><a href="advt.php?action=add">Add Advert</a><tr><td class="w"><a href="advt.php">Manage Advert</a><tr><td class="">
<a href="updates.php?action=add">Add Updates</a>
<tr><td class="w"><a href="updates.php">Manage Updates</a>
<tr><td class=""><a href="sponsored?action=add">Add Sponsored</a>
<tr><td class="w"><a href="sponsored">Manage Sponsored</a><tr><td class="">
<tr><td class=""><a href="down?action=add">Add download</a>
<tr><td class="w"><a href="down">Manage downloads</a><tr><td class="">';
} else {
echo '<tr><td class=""><a href="updates.php">Manage Updates</a>';
}
echo '</table>';
require('../incfiles/end.php');
?>