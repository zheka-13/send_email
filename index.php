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

$form_data = $sendMailService->getFormData();

$document['title'] = $text['send_mail-heading'];
require_once "resources/header.php";

if (!empty($_SESSION['flush_errors'])){
    echo "<div class='alert alert-danger'>";
    foreach ($_SESSION['flush_errors'] as $error){
        echo $error."<br>";
    }
    echo "</div>";
    unset($_SESSION['flush_errors']);
}


echo "<div class='action_bar' id='action_bar'>\n";
echo "	<div class='heading'>";
echo "<b>".$document['title']."</b>";
echo "</div>\n";
echo "	<div class='actions'>\n";
echo "</div>\n";
echo "	<div style='clear: both;'></div>\n";
echo "</div>\n";

echo "<form id='send_form' action='index.php' method='get'>";
echo "<table width='100%' cellpadding='0' cellspacing='0'>\n";
echo "	<tr>\n";
echo "		<td width='30%' class='vncell' valign='top' nowrap='nowrap'>\n";
echo "			".$text['send_mail-language']."\n";
echo "		</td>\n";
echo "		<td width='70%' class='vtable' align='left'>\n";
echo "<select name='lang' id='lang' class='formfld' onchange='send_form_submit(\"lang\")'>\n";
foreach ($form_data['form_data'] as $lang => $val){
    echo "<option value='".$lang."' ".($lang == $form_data['lang'] ? "selected" : "")." >".$lang;
}
echo "</select>";
echo "</td>";
echo "</tr>";
echo "	<tr>\n";
echo "		<td width='30%' class='vncell' valign='top' nowrap='nowrap'>\n";
echo "			".$text['send_mail-category']."\n";
echo "		</td>\n";
echo "		<td width='70%' class='vtable' align='left'>\n";
echo "			<select name='cat' id='cat' onchange='send_form_submit(\"cat\")' class='formfld'>\n";
foreach ($form_data['form_data'][$form_data['lang']] as $cat => $val){
    echo "<option value='".$cat."' ".($cat == $form_data['cat'] ? "selected" : "")." >".$cat;
}

echo "</select>";
echo "</td>";
echo "</tr>";
echo "	<tr>\n";
echo "		<td width='30%' class='vncell' valign='top' nowrap='nowrap'>\n";
echo "			".$text['send_mail-subcategory']."\n";
echo "		</td>\n";
echo "		<td width='70%' class='vtable' align='left'>\n";
echo "			<select id='subcat' name='subcat'  onchange='send_form_submit(\"subcat\")' class='formfld'>\n";
foreach ($form_data['form_data'][$form_data['lang']][$form_data['cat']] as $cat => $val){
    echo "<option value='".$cat."' ".($cat == $form_data['subcat'] ? "selected" : "")." >".$cat;
}

echo "</select>";
echo "</td>";
echo "</tr>";
echo "	<tr>\n";
echo "		<td width='30%' class='vncell' valign='top' nowrap='nowrap'>\n";
echo "			".$text['send_mail-type']."\n";
echo "		</td>\n";
echo "		<td width='70%' class='vtable' align='left'>\n";
echo "			<select name='type' id='type' onchange='send_form_submit(\"type\")' class='formfld'>\n";
foreach ($form_data['form_data'][$form_data['lang']][$form_data['cat']][$form_data['subcat']] as $type => $val){
    echo "<option value='".$type."' ".($type == $form_data['type'] ? "selected" : "")." >".$type;
}

echo "</select>";
echo "</td>";
echo "</tr>";
echo "</table></form>";

$templates = $sendMailService->getTemplates();

echo "<table class='list'>\n";
echo "<tr class='list-header'>\n";
echo "<th class='shrink' nowrap='nowrap'>".$text['send_mail-language']."</th>";
echo "<th class='pct-15' nowrap='nowrap'>".$text['send_mail-category']."</th>";
echo "<th class='pct-15' nowrap='nowrap'>".$text['send_mail-subcategory']."</th>";
echo "<th class='hide-xs pct-30' nowrap='nowrap'>".$text['send_mail-subject']."</th>";
echo "<th nowrap='nowrap'>".$text['send_mail-type']."</th>";
echo "<th class='hide-sm-dn' nowrap='nowrap'>".$text['send_mail-desc']."</th>";
echo "</tr>\n";
foreach($templates as $template){
    echo "<tr class='list-row' href='send.php?uuid=".$template['email_template_uuid']."'>\n";
    echo "<td>".$template['template_language']."</td>";
    echo "<td>".$template['template_category']."</td>";
    echo "<td>".$template['template_subcategory']."</td>";
    echo "<td>".$template['template_subject']."</td>";
    echo "<td>".$template['template_type']."</td>";
    echo "<td>".$template['template_description']."</td>";
    echo "</tr>";
}
echo "</table>";


require_once "resources/footer.php";
?>

<script>
    function send_form_submit(sel){
        if (sel === 'lang'){
            $("#cat").val("");
            $("#subcat").val("");
            $("#type").val("");
        }
        if (sel === 'cat'){
            $("#subcat").val("");
            $("#type").val("");
        }
        if (sel === 'subcat'){
            $("#type").val("");
        }
        $("#send_form").submit();
    }
</script>
