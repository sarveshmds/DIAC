<?php
class Efiling_model extends CI_model
{
    public $date;

    function __construct()
    {
        parent::__construct();
        if (ENVIRONMENT == 'production') {
            $this->db->save_queries = FALSE;
        }
        date_default_timezone_set('Asia/Kolkata');
        $this->date = date('Y-m-d H:i:s', time());
        $this->role         = $this->session->userdata('role');
        $this->user_name     = $this->session->userdata('user_name');
        $this->user_code     = $this->session->userdata('user_code');
        $this->sess_id         = $this->session->userdata('sess_id');
    }

    public function check_user_exist($user)
    {
        $this->db->select('user_code, user_name, salutation, user_display_name, email, phone_number, primary_role, job_title, is_new_record');
        $this->db->where([
            'user_name' => $user,
            'record_status' => 1,
        ]);
        $this->db->from('user_master');
        $result = $this->db->get()->row_array();
        if ($result) {
            return $result;
        }
        return false;
    }

    public function get_dashboard_data()
    {
        $todays_date = date('d-m-Y');

        $total_case_count = $this->db->where('status', 1)
            ->count_all_results('cs_case_details_tbl');

        $total_documents_count = $this->db->where([
            'status' => 1,
            'user_code' => $this->user_code
        ])
            ->count_all_results('efiling_document_tbl');

        $total_applications_count = $this->db->where(array(
            'status' => 1,
            'user_code' => $this->user_code
        ))->count_all_results('efiling_application_tbl');

        $total_requests_count = $this->db->where(array(
            'status' => 1,
            'user_code' => $this->user_code
        ))->count_all_results('efiling_requests_tbl');

        $total_vakalatnama_count = $this->db->where(array(
            'status' => 1,
            'user_code' => $this->user_code
        ))->count_all_results('efiling_vakalatnama_tbl');

        $total_new_reference_count = $this->db->where(array(
            'status' => 1,
            'user_code' => $this->user_code
        ))->count_all_results('efiling_new_reference_tbl');

        $total_concent_count = $this->db->where(array(
            'status' => 1,
            'user_code' => $this->user_code
        ))->count_all_results('efiling_consent_tbl');

        return array(
            'total_case_count' => $total_case_count,
            'total_documents_count' => $total_documents_count,
            'total_applications_count' => $total_applications_count,
            'total_requests_count' => $total_requests_count,
            'total_vakalatnama_count' => $total_vakalatnama_count,
            'total_new_reference_count' => $total_new_reference_count,
            'total_concent_count' => $total_concent_count
        );
    }

    public function check_advocate_appearing_for($user)
    {
        return $this->db->from('cs_counsels_tbl cct')
            ->select('cct.*, crd.type')
            ->join('cs_claimant_respondant_details_tbl as crd', 'crd.id = cct.appearing_for', 'inner')
            ->where([
                'cct.phone' => $user['phone_number'],
                'cct.status' => 1,
                'cct.discharge' => 0
            ])
            ->get()->row_array();
    }

    function verify_forgot_password_otp()
    {

        $user = $this->session->userdata('user_data');

        $verification_result = $this->db->select('*')
            ->from('cs_otp_verification_tbl')
            ->where('otp', $this->input->post('otp'))
            ->where('username', $user['user_name'])
            ->where('otp_used', 0)
            ->get()
            ->row_array();

        if ($verification_result && strtotime($verification_result['expiration_time']) > strtotime(date('Y-m-d H:i:s'))) {

            $result = $this->db->update('cs_otp_verification_tbl', [
                'otp_used' => 1,
                'updated_by' => ($user['user_code']) ? $user['user_code'] : 'FORGET_PASSWORD'
            ]);

            if ($result) {
                return [
                    'status' => true,
                    'msg' => 'OTP verified successfully. Create your new password.',
                    'redirect_url' => base_url('efiling/set-password')
                ];
            } else {
                return [
                    'status' => false,
                    'msg' => 'Server failed while responding.'
                ];
            }
        } else {
            return [
                'status' => false,
                'msg' => 'Invalid OTP or OTP is expired or OTP is already used.'
            ];
        }
    }

    public function set_new_user_password()
    {
        $user = $this->session->userdata('user_data');

        $this->db->trans_begin();
        try {
            $data = array(
                "password" => $this->input->post('txtPassword'),
                "is_new_record" => 1,
                "updated_by" => $user['user_name'],
                "updated_on" => $this->date
            );
            $this->db->where('user_code', $user['user_code']);
            $update_user = $this->db->update('user_master', $data);

            if ($update_user) {
                $log_rp = array(
                    "role_code"     =>     $user['primary_role'],
                    "user_code"        =>    $user['user_code'],
                    "ip_address"    =>    $this->input->ip_address(),
                    "password"        =>    $this->input->post('txtPassword'),
                    "created_by"    =>  $this->user_name,
                    "last_attempt"  =>  $this->date,
                    "status"        =>  1
                );
                $insert_log_rp = $this->db->insert('log_reset_password_history', $log_rp);
                if ($insert_log_rp) {
                    $this->db->trans_commit();

                    // Unset the session variables 
                    $this->session->unset_userdata('user_data');

                    if ($this->session->has_userdata('otp_verification')) {
                        $this->session->unset_userdata('otp_verification');
                    }

                    $dbstatus = TRUE;
                    $dbmessage = 'Your password has been reset successfully!';
                } else {
                    $this->db->trans_rollback();
                    $dbstatus = FALSE;
                    $dbmessage = 'Your password has been not reset';
                }
            } else {
                $this->db->trans_rollback();
                $dbstatus = FALSE;
                $dbmessage = 'Error While Reset';
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return array('status' => false, 'msg' => $e->getMessage());
        }
        return array('status' => $dbstatus, 'msg' => $dbmessage);
    }
}
