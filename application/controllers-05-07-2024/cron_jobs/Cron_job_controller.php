<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Cron_job_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        # models
        $this->load->model(array('common_model', 'causelist_model', 'category_model', 'case_model', 'claimant_respondent_model', 'arbitral_tribunal_model'));
    }

    public function send_reminder_of_hearing()
    {
        // Fetch all the todays cases hearing
        $this->db->select("clt.id, clt.slug,clt.case_no, CONCAT(cdt.case_no_prefix,'/', cdt.case_no, '/', cdt.case_no_year) as case_no_desc, cdt.case_title, arbitrator_name, amt.email as amt_email, amt.contact_no as amt_contact_no, title_of_case, date, time_from, time_to, purpose_cat_id, rt.room_no, rt.room_name, rt.room_name, clt.room_no_id, puc.category_name as purpose_category_name, clt.remarks, clt.active_status, CASE WHEN clt.active_status='2' THEN 'Cancelled' WHEN clt.active_status='1' THEN 'Active' END as active_status_desc, GROUP_CONCAT(amt.name_of_arbitrator) as amt_name_of_arbitrator, clt.mode_of_hearing");
        $this->db->from('cause_list_tbl AS clt');
        $this->db->join('rooms_tbl as rt', 'rt.id = clt.room_no_id', 'left');
        $this->db->join('purpose_category_tbl as puc', 'puc.id = clt.purpose_cat_id', 'left');
        $this->db->join('cs_case_details_tbl as cdt', 'cdt.slug = clt.slug', 'left');
        $this->db->join('cs_arbitral_tribunal_tbl as cat', 'cat.case_no = clt.slug', 'left');
        $this->db->join('master_arbitrators_tbl as amt', 'amt.code = cat.arbitrator_code', 'left');

        $this->db->where('clt.date', date('Y-m-d', strtotime('+1 day')));
        $this->db->where('clt.active_status', 1);
        $this->db->group_by('clt.id');
        $query = $this->db->get();
        $hearings = $query->result_array();
        // echo '<pre>';
        // print_r($hearings);
        // die;

        foreach ($hearings as $key => $hearing) {
            $case_no_slug = $hearing['slug'];
            $hearing_data = $hearing;

            // Get the room details
            $room_info = $this->common_model->get_room_info_using_id($hearing['room_no_id']);

            $case_details_data = $this->getter_model->get([
                'case_slug' => $case_no_slug
            ], 'GET_CASE_DETAILS');

            // ================================================
            // Send the email notification to everyone.
            // ============================================
            // Get the claimant and respondent data
            $case_claimants = $this->claimant_respondent_model->get_cla_res_basic_info_using_case_and_slug($case_no_slug, CLAIMANT_TYPE_CONSTANT);
            $claimants = array_filter($case_claimants, function ($claimant) {
                return !empty($claimant['email']);
            });
            $claimants_emails_temp = array_column($claimants, 'email');
            // $claimants_emails = implode(',', $claimants_emails_temp);

            // Phone Number
            $claimants_for_phone = array_filter($case_claimants, function ($claimant) {
                return !empty($claimant['contact']);
            });
            $claimants_phone_temp = array_column($claimants_for_phone, 'contact');

            // ============================================
            // Get the COUNSELS details of claimants
            $case_claimants_counsels = $this->common_model->get_counsels_of_case($case_no_slug, CLAIMANT_TYPE_CONSTANT);

            $claimant_counsels = array_filter($case_claimants_counsels, function ($counsel) {
                return !empty($counsel['email']);
            });
            $claimant_counsels_emails_temp = array_column($claimant_counsels, 'email');

            // Phone Number
            $claimant_counsels_for_phone = array_filter($case_claimants_counsels, function ($counsel) {
                return !empty($counsel['phone_number']);
            });
            $claimant_counsels_phone_temp = array_column($claimant_counsels_for_phone, 'phone_number');

            // ================================================
            // Respondents =====================
            $case_respondants = $this->claimant_respondent_model->get_cla_res_basic_info_using_case_and_slug($case_no_slug, RESPONDANT_TYPE_CONSTANT);
            $respondants = array_filter($case_respondants, function ($claimant) {
                return !empty($claimant['email']);
            });

            $respondants_emails_temp = array_column($respondants, 'email');
            // $respondants_emails = implode(',', $respondants_emails_temp);
            // Phone Number
            $respondants_for_phone = array_filter($case_respondants, function ($claimant) {
                return !empty($claimant['contact']);
            });
            $respondants_phone_temp = array_column($respondants_for_phone, 'contact');

            // ============================================
            // Get the COUNSELS details of claimants
            $case_respondants_counsels = $this->common_model->get_counsels_of_case($case_no_slug, RESPONDANT_TYPE_CONSTANT);

            $respondant_counsels = array_filter($case_respondants_counsels, function ($counsel) {
                return !empty($counsel['email']);
            });
            $respondant_counsels_emails_temp = array_column($respondant_counsels, 'email');

            // Phone Number
            $respondant_counsels_for_phone = array_filter($case_respondants_counsels, function ($counsel) {
                return !empty($counsel['phone_number']);
            });
            $respondant_counsels_phone_temp = array_column($respondant_counsels_for_phone, 'phone_number');

            // ================================================
            // Get arbitrators of the case
            $case_arbitrators = $this->arbitral_tribunal_model->getBasicArbitratorData($case_no_slug);
            $arbitrators = array_filter($case_arbitrators, function ($arbitrator) {
                return !empty($arbitrator['email']);
            });
            $arbitrators_emails_temp = array_column($arbitrators, 'email');
            // $arbitrators_emails = implode(',', $arbitrators_emails_temp);
            // Phone Number
            $arbitrators_for_phone = array_filter($case_arbitrators, function ($arbitrator) {
                return !empty($arbitrator['contact_no']);
            });
            $arbitrators_phone_temp = array_column($arbitrators_for_phone, 'contact_no');

            // Fetch all users allocated in the case
            $users = $this->common_model->get_all_users_alloted_to_case($case_no_slug);

            if ($users && count($users) < 1) {
                failure_response('No users are not allocated', true, true);
            }

            // Fetch deputy counsel details
            $deputy_counsel_details = $this->common_model->get_deputy_counsel_alloted_to_case($case_no_slug);
            $deputy_counsel_smtp_email = (isset($deputy_counsel_details['email']) && !empty($deputy_counsel_details['email'])) ? $deputy_counsel_details['email'] : DEFAULT_SMTP_EMAIL_ID;
            // if ($users && count($users) < 1) {
            //     failure_response('No deputy counsel is allocated', true, true);
            // }

            $users = array_filter($users, function ($user) {
                return !empty($user['email']);
            });
            $users_emails_temp = array_column($users, 'email');
            // $users_emails = implode(',', $users_emails_temp);

            // Phone Number
            $users_for_phone = array_filter($users, function ($user) {
                return !empty($user['phone_number']);
            });
            $users_phone_temp = array_column($users_for_phone, 'phone_number');

            // Merge all email arrays into a single array
            $all_emails = array_merge(
                $claimants_emails_temp,
                $claimant_counsels_emails_temp,
                $respondants_emails_temp,
                $respondant_counsels_emails_temp,
                $arbitrators_emails_temp,
                $users_emails_temp
            );

            // Implode the merged array with commas
            $all_emails_string = implode(',', $all_emails);

            // =====================================================
            // =====================================================

            $additional_phones = [];
            // Merge all email arrays into a single array
            $all_phone_numbers = array_merge(
                $claimants_phone_temp,
                $claimant_counsels_phone_temp,
                $respondants_phone_temp,
                $respondant_counsels_phone_temp,
                $arbitrators_phone_temp,
                $users_phone_temp
            );

            // Implode the merged array with commas
            // print_r($all_phone_numbers);
            if (count($all_phone_numbers) > 0) {
                $full_case_no = get_full_case_number($case_details_data);

                foreach ($all_phone_numbers as $key => $phone_number) {
                    // ======================================================
                    // SEND THE WHATSAPP MESSAGE WHO ARE IN THE CASE
                    $whatsapp_result = wa_case_hearing_notification($phone_number, $full_case_no, $case_details_data, $hearing_data);

                    // Insert WhatsApp Log Data
                    $insert_whatsapp_log = [
                        "transaction_id"     => $whatsapp_result['template_id'],
                        'type'               => 'CRON_HEARING_REMINDER',
                        "phone_number"       => $phone_number,
                        "message"            => $whatsapp_result['msg'],
                        "status"             => ($whatsapp_result['status']) ? 'SUCCESS' : 'FAILED', //$output['msg']
                        "created_by"         => 'CRON',
                        "created_at"         => currentDateTimeStamp()
                    ];
                    $result_whatsapp = $this->db->insert('support_whatsapp_record', $insert_whatsapp_log);
                }
            }

            // ========================================================
            // Create an array of all the persons who are involved in the case
            $persons = [
                'claimants' => $case_claimants,
                'case_claimants_counsels' => $case_claimants_counsels,
                'respondants' => $case_respondants,
                'case_respondants_counsels' => $case_respondants_counsels,
                'arbitrators' => $case_arbitrators,
                'users' => $users,
            ];

            // Load the data
            $view_data['persons'] = $persons;
            $view_data['case_details_data'] = $case_details_data;
            $view_data['hearing_data'] = $hearing_data;
            $view_data['room_info'] = $room_info;

            // Send emails to everyone
            $subject = 'Hearing scheduled for case number: ' . get_full_case_number($case_details_data);

            $email_html_body = $this->load->view('emails/hearing_notice', $view_data, true);

            $email_send_result = hearing_notice_info_to_all(strtolower($all_emails_string), $subject, $email_html_body, $deputy_counsel_smtp_email);

            // print_r($email_send_result);
            // die;
            // Email log data
            $insert_mail_log = [
                "transaction_id"     => $case_no_slug,
                'mail_type'         => 'CRON_HEARING_REMINDER',
                "to_mail"             => strtolower($all_emails_string),
                "subject"             => $subject,
                "body"                 => $email_html_body,
                "status"             => ($email_send_result['status']) ? 'SUCCESS' : 'FAILED', //$output['msg']
                "created_by"         => 'CRON',
                "created_at"         => currentDateTimeStamp()
            ];
            $result_mail = $this->db->insert('support_mail_record', $insert_mail_log);

            if ($email_send_result['status']) {
                // ============================================
                // Maintain the success log
                echo 'Success' . '<br/>';
            } else {
                // ============================================
                // Maintain the failed log
                echo 'Failed' . '<br/>';
            }
        }
    }
}
