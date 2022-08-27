<?php
/*
	FusionPBX
	Version: MPL 1.1

	The contents of this file are subject to the Mozilla Public License Version
	1.1 (the "License"); you may not use this file except in compliance with
	the License. You may obtain a copy of the License at
	http://www.mozilla.org/MPL/

	Software distributed under the License is distributed on an "AS IS" basis,
	WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License
	for the specific language governing rights and limitations under the
	License.

	The Original Code is FusionPBX

	The Initial Developer of the Original Code is
	Mark J Crane <markjcrane@fusionpbx.com>
	Portions created by the Initial Developer are Copyright (C) 2018 - 2019
	the Initial Developer. All Rights Reserved.

	Contributor(s):
	Mark J Crane <markjcrane@fusionpbx.com>
*/

//includes
require_once "root.php";
require_once "resources/require.php";
require_once "resources/check_auth.php";

//check permissions
if (permission_exists('send_mail')) {
    //access granted
}
else {
    echo "access denied";
    exit;
}

$language = new text;
$text = $language->get();
$sendMailService = new SendMailService();

$document['title'] = $text['send_mail-heading'];
require_once "resources/header.php";

echo "<div class='action_bar' id='action_bar'>\n";
echo "	<div class='heading'>";
echo "<b>".$document['title']."</b>";
echo "</div>\n";
echo "	<div class='actions'>\n";
echo "</div>\n";
echo "	<div style='clear: both;'></div>\n";
echo "</div>\n";

echo "<table width='100%' cellpadding='0' cellspacing='0'>\n";
echo "	<tr>\n";
echo "		<td width='30%' class='vncell' valign='top' nowrap='nowrap'>\n";
echo "			".$text['send_mail-language']."\n";
echo "		</td>\n";
echo "		<td width='70%' class='vtable' align='left'>\n";
echo "			<select name='lang' class='formfld'>\n";
echo "</select>";
echo "</td>";
echo "</tr>";
echo "	<tr>\n";
echo "		<td width='30%' class='vncell' valign='top' nowrap='nowrap'>\n";
echo "			".$text['send_mail-category']."\n";
echo "		</td>\n";
echo "		<td width='70%' class='vtable' align='left'>\n";
echo "			<select name='lang' class='formfld'>\n";
echo "</select>";
echo "</td>";
echo "</tr>";
echo "	<tr>\n";
echo "		<td width='30%' class='vncell' valign='top' nowrap='nowrap'>\n";
echo "			".$text['send_mail-subcategory']."\n";
echo "		</td>\n";
echo "		<td width='70%' class='vtable' align='left'>\n";
echo "			<select name='lang' class='formfld'>\n";
echo "</select>";
echo "</td>";
echo "</tr>";
echo "	<tr>\n";
echo "		<td width='30%' class='vncell' valign='top' nowrap='nowrap'>\n";
echo "			".$text['send_mail-type']."\n";
echo "		</td>\n";
echo "		<td width='70%' class='vtable' align='left'>\n";
echo "			<select name='lang' class='formfld'>\n";
echo "</select>";
echo "</td>";
echo "</tr>";
echo "</table>";





require_once "resources/footer.php";
?>