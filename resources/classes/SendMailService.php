<?php

class SendMailService
{
    private $db;
    private $form_data = [];



    public function __construct()
    {
        $this->db = new database;
    }



    /**
     * @return array
     */
    public function getFormData()
    {
        $data = [];
        $query = "select template_language, template_category, template_subcategory, template_type  from v_email_templates
                where template_enabled='true' and (domain_uuid is null or domain_uuid = :domain_uuid) 
                and template_language='en-us' and template_category='welcome'
                group by template_language, template_category, template_subcategory, template_type";
        $rows = $this->db->select($query, [
            "domain_uuid" => $_SESSION['domain_uuid']
        ]);
        foreach ($rows as $row){
            if (!isset($data[$row['template_language']])){
                $data[$row['template_language']] = [];
            }
            if (!isset($data[$row['template_language']][$row['template_category']])){
                $data[$row['template_language']][$row['template_category']] = [];
            }
            if (!isset($data[$row['template_language']][$row['template_category']][$row['template_subcategory']])){
                $data[$row['template_language']][$row['template_category']][$row['template_subcategory']] = [];
            }
            $data[$row['template_language']][$row['template_category']][$row['template_subcategory']][$row['template_type']] = 1;
        }
        return $this->generateRequestData($data);
    }

    public function getTemplates()
    {
        $query = "select * from v_email_templates where template_language = :lang 
                      and template_category = :cat and  template_subcategory = :subcat and template_type = :type 
                      and (domain_uuid is null or domain_uuid = :domain_uuid)";
        return $this->db->select($query, [
            "lang" => $this->form_data['lang'],
            "cat" => $this->form_data['cat'],
            "subcat" => $this->form_data['subcat'],
            "type" => $this->form_data['type'],
            "domain_uuid" => $_SESSION['domain_uuid']
        ]);
    }

    /**
     * @throws Exception
     */
    public function getTemplate($uuid)
    {
        $query = "select * from v_email_templates where email_template_uuid = :uuid 
                      and (domain_uuid is null or domain_uuid = :domain_uuid)";
        $data = $this->db->select($query, [
            "domain_uuid" => $_SESSION['domain_uuid'],
            "uuid" => $uuid
        ]);
        if (!empty($data[0])){
            return $data[0];
        }
        throw new Exception(
            "send_mail-template_not_found"
        );

    }

    public function sendEmail($extension, $template){
        $query = "insert into v_email_queue (email_queue_uuid, domain_uuid, hostname, email_date, email_to, email_subject, email_body, email_status)
            values (:email_queue_uuid, :domain_uuid, :hostname, now(), :email_to, :email_subject, :email_body, 'waiting')";
       $this->db->execute($query, [
           "email_queue_uuid" => uuid(),
           "domain_uuid" => $_SESSION['domain_uuid'],
           "hostname" => gethostname(),
           "email_to" => $extension['voicemail_mail_to'],
           'email_subject' => $template['template_subject'],
           'email_body' => $this->parseText($template['template_body'], $extension)
        ]);
    }

    /**
     * @return array
     */
    public function getExtensions()
    {
        $query = "select extension_uuid, extension, voicemail_mail_to, directory_first_name, directory_last_name, call_group, enabled, 
        description, voicemail_password   from v_extensions 
        join (select voicemail_id,  voicemail_mail_to, voicemail_password from v_voicemails where domain_uuid = :domain_uuid) 
        a on (a.voicemail_id=extension)
        where domain_uuid = :domain_uuid and enabled='true' and voicemail_mail_to is not null and length(voicemail_mail_to) > 0";
        return $this->db->select($query, [
            "domain_uuid" => $_SESSION['domain_uuid'],
        ]);

    }

    private function generateRequestData($form_data)
    {
        $data = [
            "lang" => !empty($_REQUEST['lang']) ? $_REQUEST['lang'] : "",
            "cat" => !empty($_REQUEST['cat']) ? $_REQUEST['cat'] : "",
            "subcat" => !empty($_REQUEST['subcat']) ? $_REQUEST['subcat'] : "",
            "type" => !empty($_REQUEST['type']) ? $_REQUEST['type'] : "",
            "form_data" => $form_data
        ];
        if (empty($data['lang']) && !empty(array_key_first($form_data))){
            $data['lang'] = array_key_first($form_data);
        }
        if (empty($data['cat']) && !empty($form_data[$data['lang']])){
            $data['cat'] = array_key_first($form_data[$data['lang']]);
        }
        if (empty($data['subcat']) && !empty($form_data[$data['lang']][$data['cat']])){
            $data['subcat'] = array_key_first($form_data[$data['lang']][$data['cat']]);
        }
        if (empty($data['type']) && !empty($form_data[$data['lang']][$data['cat']][$data['subcat']])){
            $data['type'] = array_key_first($form_data[$data['lang']][$data['cat']][$data['subcat']]);
        }
        $this->form_data = $data;
        return $data;
    }

    /**
     * @param $text
     * @param $extension
     * @return array|string|string[]
     */
    private function parseText($text, $extension)
    {
        $text = str_replace('${first_name}', $extension['directory_first_name'], $text);
        $text = str_replace('${last_name}', $extension['directory_last_name'], $text);
        $text = str_replace('${extension}', $extension['extension'], $text);
        $text = str_replace('${voicemail_pin}', $extension['voicemail_password'], $text);
        return  str_replace('${email_address}', $extension['voicemail_mail_to'], $text);
    }


}
