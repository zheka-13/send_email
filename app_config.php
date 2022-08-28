<?php

$apps[$x]['name'] = "Send Email";
$apps[$x]['uuid'] = "0076904e-2e4e-4080-a342-9aa8b3745826";
$apps[$x]['category'] = "Switch";
$apps[$x]['subcategory'] = "";
$apps[$x]['version'] = "1.0";
$apps[$x]['license'] = "GNU GENERAL PUBLIC LICENSE v. 3";
$apps[$x]['url'] = "https://github.com/zheka-13/send_mail.git";
$apps[$x]['description']['en-us'] = "Send emails to extension users using email templates";

$y=0;
$apps[$x]['permissions'][$y]['name'] = "send_email";
$apps[$x]['permissions'][$y]['menu']['uuid'] = "b729d64c-5b3b-4c8a-8851-a73f57015c0e";
$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
$apps[$x]['permissions'][$y]['groups'][] = "admin";


?>
