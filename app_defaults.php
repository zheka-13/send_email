<?php
if ($domains_processed == 1) {
    $database = new database;
    $query = <<<'SQL'
    insert into v_email_templates (email_template_uuid, template_language, 
           template_category, template_subcategory, template_subject, 
           template_body, template_type, template_enabled, template_description) 
            values ('16d8515c-068c-480d-abfe-645c159910b4', 
                    'en-us', 'send_mail', 'welcome', 'Welcome to your new Phone System',
                    E'<html>
        <body>
        <br />
        Welcome ${first_name} ${last_name},<br />
        <br />
        The ${provider_name} Hosted Phone System is a VoIP PBX that will allow you to take advantage of 
        unified communications and make you more productive.<br />
        <br />
        On the day of installation please make sure you have cleaned out your current voicemail box, you will not be able to keep old voicemails. 
        Also please ensure there is adequate area to access the computer and cables around your workstation so the technician can easily remove your current phone and connect your new phone. 
        <br />
        You have been assigned the following extension number "${extension}" with a PIN "${voicemail_pin}"<br />
        <br />
        You can use your extension number and PIN to access your voice mail from your phone by hitting the envelope key on your telephone or dialing "*97#"<br />
            <br />
        For the best experience please setup your Voicemail Greeting and Name right away. Pickup the handset and follow the instructions below.<br />
        <br />
        Voicemail Greeting:<br />
        a. Press the Envelope Key<br />
        b. Type in your PIN<br />
        c. Press 5 for advanced options<br />
        d. Press 1 to record your greeting (this is the message that will play when you are not on your phone and someone calls and you do not answer)<br />
        <br />
        Voicemail Name:<br />
        a. Press the Envelope Key<br />
        b. Type in your PIN<br />
        c. Press 5 for advanced options<br />
        d. Press 3 to record your name (this will be used for the company directory)<br />
        <br />
        Other Items you may want to change right away:<br />
        Press 6 to change your password<br />
        <br />
        <br />
        Your Voicemail will delivered to email: <br />
        ${email_address}
        <br /><br />
        <br />
        Please don\'t hesitate to email or call us with any questions or concerns.<br />
        <br />
        Best Regards,<br />
        <br />
        </body>
        </html>', 'html', 'true', 'welcome message');
SQL;
    $database->execute($query);


}
?>
