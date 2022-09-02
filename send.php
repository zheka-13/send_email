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
if (permission_exists('send_email')) {
    //access granted
}
else {
    echo "access denied";
    exit;
}

$language = new text;
$text = $language->get();
$sendMailService = new SendMailService();

try{
    $template = $sendMailService->getTemplate($_GET['uuid']);
}
catch (Exception $e){
    $_SESSION['flush_errors'] = [$text[$e->getMessage()]];
    header('Location: send_mail.php');
}

$document['title'] = $text['send_mail-heading'];
require_once "resources/header.php";

if (isset($_POST['template'])){
    $color = 'positive';
    $sent = 0;
    if (is_array($_POST['extensions'])){
        $extensions = [];
        foreach($_POST['extensions'] as $val){
            if ($val['checked'] == 'true'){
                $extensions[] = $val['uuid'];
            }
        }
        if (!empty($extensions)){
            $users = $sendMailService->getExtensions();
            foreach ($users as $user){
                if (in_array($user['extension_uuid'], $extensions)) {
                    $sendMailService->sendEmail($user, $template);
                    $sent++;
                }
            }
        }
    }

    ?>
    <script>
    $(function(){
        display_message('<?php echo $text['send_mail-mail_sent']." <strong>".$sent."</strong> ".$text['send_mail-recipients']; ?>', '<?php echo $color;?>');
    });
    </script>

<?php
}

echo "<div class='action_bar' id='action_bar'>\n";
echo "	<div class='heading'>";
echo "<b>".$text['send_mail-send_header']."</b>";
echo "</div>\n";
echo "	<div class='actions'>\n";
echo button::create(['type'=>'button',
    'label'=>$text['send_mail-button'],
    'icon'=>$_SESSION['theme']['button_icon_envelope'],
    'id'=>'btn_email',
    'name'=>'btn_toggle',
    'onclick'=>"modal_open('modal-toggle','btn_email');"]);
echo button::create(['type'=>'button','label'=>$text['button-back'],'icon'=>$_SESSION['theme']['button_icon_back'],'link'=>'index.php']);
echo "</div>\n";
echo "	<div style='clear: both;'></div>\n";
echo "</div>\n";

echo modal::create(['id'=>'modal-toggle','type'=>'toggle','actions'=>button::create(['type'=>'button','label'=>$text['button-continue'],'icon'=>'check','id'=>'btn_toggle','style'=>'float: right; margin-left: 15px;','collapse'=>'never','onclick'=>"modal_close(); list_action_set('toggle'); list_form_submit('form_list');"])]);

echo "<div class='alert alert-warning'>";
echo "<strong>".$text['send_mail-language'].":</strong> ".$template['template_language']."<br>";
echo "<strong>".$text['send_mail-category'].":</strong> ".$template['template_category']."<br>";
echo "<strong>".$text['send_mail-subcategory'].":</strong> ".$template['template_subcategory']."<br>";
echo "<strong>".$text['send_mail-subject'].":</strong> ".$template['template_subject']."<br>";
echo "</div>";

$extensions = $sendMailService->getExtensions();

echo "<form id='form_list' method='post'>";
echo "<input type='hidden' name='action' id='action' value=''>";
echo "<input type='hidden' name='template' value='".$_GET['uuid']."'>";
echo "<table class='list'>";
echo "<tr class='list-header'>";
    echo "<th class='checkbox'>";
        echo "<input type='checkbox' id='checkbox_all' name='checkbox_all' onclick='list_all_toggle();' array=''>";
    echo "</th>";
    echo "<th nowrap='nowrap'>".$text['send_mail-extension']."</th>";
    echo "<th nowrap='nowrap'>".$text['send_mail-email']."</th>";
    echo "<th nowrap='nowrap'>".$text['send_mail-fullname']."</th>";
    echo "<th nowrap='nowrap'>".$text['send_mail-callgroup']."</th>";
    echo "<th nowrap='nowrap'>".$text['send_mail-description']."</th>";
echo "</tr>";
foreach ($extensions as $extension){
    echo "<tr class='list-row'>";
    echo "<td class='checkbox'>";
        echo "<input type='checkbox' name='extensions[0][checked]' id='checkbox_0' value='true' onclick='if (!this.checked) { document.getElementById('checkbox_all').checked = false; }'>";
        echo "<input type='hidden' name='extensions[0][uuid]' value='".$extension['extension_uuid']."'>";
    echo "</td>";
    echo "<td>".$extension['extension']."</td>";
    echo "<td>".$extension['voicemail_mail_to']."</td>";
    echo "<td>".$extension['directory_first_name']." ".$extension['directory_last_name']."</td>";
    echo "<td>".$extension['call_group']."</td>";
    echo "<td>".$extension['description']."</td>";
    echo "</tr>";
}
echo "</table>";
echo "</form>";



require_once "resources/footer.php";
?>
