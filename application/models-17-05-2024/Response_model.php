
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Response_model extends CI_Model
{

    public $date;
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->load->helper('date');

        if (ENVIRONMENT == 'production') {
            $this->db->save_queries = FALSE;
        }
        date_default_timezone_set('Asia/Kolkata');
        $this->date = date('Y-m-d H:i:s', time());

        $this->role         = $this->session->userdata('role');
        $this->user_name     = $this->session->userdata('user_name');
        $this->user_code     = $this->session->userdata('user_code');
    }


    public function operation_response_format($data)
    {

        // Case Allotment details
        $this->form_validation->set_rules('hidden_case_no', 'Case No.', 'required|xss_clean');
        $this->form_validation->set_rules('hidden_option', 'Option', 'required|xss_clean');
        $this->form_validation->set_rules('response_subject', 'Subject', 'required|xss_clean');
        $this->form_validation->set_rules('response_body', 'Body', 'required|xss_clean');


        if ($this->form_validation->run()) {
            $this->db->trans_begin();
            try {
                $case_no = $this->security->xss_clean($data['hidden_case_no']);

                $option = $this->security->xss_clean($data['hidden_option']);

                $subject = $this->security->xss_clean($data['response_subject']);

                $body = $this->security->xss_clean($data['response_body']);

                $email_to = $this->security->xss_clean($data['response_to']);
                // $cc = $this->security->xss_clean($data['response_cc']);
                // $bcc = $this->security->xss_clean($data['response_bcc']);

                if ($option == 'SAVE_AND_SEND') {
                    if (empty($email_to) || $email_to == '') {
                        return array(
                            'status' => false,
                            'msg' => 'To send mail, please enter the email address to which you want to send the mail.'
                        );
                    }
                }

                // Check if the case is already alloted or not
                // if not alloted, return with error message
                $res = $this->common_model->check_case_allotted($case_no, $this->user_code);

                if (!$res) {
                    $this->db->trans_rollback();
                    $output = array('status' => false, 'msg' => 'Case is not alloted to you.');
                }

                $generated_file_upload_path = 'public/upload/response_format/generated/';

                // Load dom pdf library =====================
                $this->load->library('dom_pdf');
                // Make PDF Format
                $data['page_title'] = 'Response Format';
                $pdf_data = $this->load->view('system/templates/header', $data, true);
                $pdf_data .= $body;
                $pdf_data .= $this->load->view('system/templates/footer', $data, true);
                // Save pdf to folder
                $file_path = $this->dom_pdf->save_to_folder($pdf_data, 'response_format', '', $generated_file_upload_path);
                // ==================================================

                $data = array(
                    'case_no' => $case_no,
                    'subject' => $subject,
                    'body' => $body,
                    'email_to' => $email_to,
                    // 'cc' => $cc,
                    // 'bcc' => $bcc,
                    'file_path' => $file_path,
                    'response_status' => ($option == 'SAVE_AND_SEND') ? 1 : 2,
                    'status' => 1,
                    'created_by' => $this->user_code,
                    'created_at' => currentDateTimeStamp(),
                    'updated_by' => $this->user_code,
                    'updated_at' => currentDateTimeStamp()
                );

                $result = $this->db->insert('cs_response_format_tbl', $data);

                if ($result) {

                    $table_id = $this->db->insert_id();

                    $redirectLink = '';
                    if ($option == 'SAVE_AND_DOWNLOAD') {

                        $redirectLink = base_url('pdf/response-format?case_no=' . $case_no . '&response_id=' . urlencode($table_id));

                        $optionMessage = 'Your file is downloaded.';
                    } elseif ($option == 'SAVE_AND_SEND') {

                        // Load the email messages helper
                        $this->load->helper('email_messages_helper');
                        $emailStatus = send_response_format($data);

                        $optionMessage = 'email sent successfully.';

                        if ($emailStatus['status'] == false) {
                            $optionMessage = 'Error while sending the mail. Please send the mail again.';
                        }
                    }

                    $this->db->trans_commit();
                    $output = array(
                        'status' => true,
                        'msg' => 'Response format saved successfully and ' . $optionMessage,
                        'redirect_link' => $redirectLink
                    );
                } else {
                    $this->db->trans_rollback();
                    $output = array('status' => false, 'msg' => 'Something went wrong');
                }
            } catch (Exception $e) {
                $this->db->trans_rollback();
                $output = array('status' => false, 'msg' => 'Something went wrong');
            }
        } else {
            $output = array('status' => 'validationerror', 'msg' => validation_errors());
        }
        return $output;
    }
}
