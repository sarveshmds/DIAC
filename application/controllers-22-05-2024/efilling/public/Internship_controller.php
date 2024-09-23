<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Internship_controller extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$data['gender'] = $this->getter_model->get(null, 'GET_GENDER_FOR_INTERN');
		// print_r($data['gender']);
		// die;
		$this->load->library('captcha_generator');
		$data['image'] = $this->captcha_generator->create_captcha();

		$this->load->view('templates/efiling/efiling-auth-header');
		$this->load->view('efiling/public/apply-internship-form', $data);
		$this->load->view('templates/efiling/efiling-auth-footer');
	}

	function internshipStore()
	{

		$inputCsrfToken = $_POST['csrf_frm_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'csrf_internship_form')) {
				// Validation

				$this->form_validation->set_rules('name_of_applicant', 'Name of Applicant', 'required');
				$this->form_validation->set_rules('date_of_birth', 'Date of Birth', 'required');
				$this->form_validation->set_rules('name_of_college_institute', 'Name of College/Institute', 'required');
				$this->form_validation->set_rules('programme_duration', 'Programme Duration', 'required');
				$this->form_validation->set_rules('semester_pursuing', 'Semester Pursuing', 'required');
				$this->form_validation->set_rules('prefrred_period_internship_from', 'Preferred Period of Internship (From)', 'required');
				$this->form_validation->set_rules('prefrred_period_internship_to', 'Preferred Period of Internship (To)', 'required');
				$this->form_validation->set_rules('father_name', "Father's Name", 'required');
				$this->form_validation->set_rules('mother_name', "Mother's Name", 'required');
				$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
				$this->form_validation->set_rules('mobile', 'Mobile Number', 'required|numeric|min_length[10]|max_length[10]');
				$this->form_validation->set_rules('gender', 'Gender', 'required');
				$this->form_validation->set_rules('permanent_address', 'Permanent Address', 'required');
				$this->form_validation->set_rules('address_during_internship', 'Address During Internship', 'required');
				// $this->form_validation->set_rules('institute_address', 'Institute Address', 'required');
				// $this->form_validation->set_rules('contact_person_of_institute', 'Contact Person of Institute', 'required');
				// $this->form_validation->set_rules('contact_person_no_of_institute', 'Contact Person Number of Institute', 'required');

				$this->form_validation->set_rules('txt_captcha', 'Captcha', 'required');

				if ($this->form_validation->run() == TRUE) {
					if ($this->session->userdata('captchaword') == $this->input->post('txt_captcha')) {

						// Total count
						$total_count = $this->getter_model->get(null, 'GET_TOTAL_INTERNS_COUNT');

						// Generate reference number
						$reference_no = generate_internship_ref_no($total_count);

						$internship_data = array(
							'code' => generateCode(),
							'reference_no' => $reference_no,
							'name_of_applicant' => $this->input->post('name_of_applicant'),
							'date_of_birth' => $this->input->post('date_of_birth'),
							'name_of_college_institute' => $this->input->post('name_of_college_institute'),
							'programme_duration' => $this->input->post('programme_duration'),
							'semester_pursuing' => $this->input->post('semester_pursuing'),
							'pref_period_internship_from' => $this->input->post('prefrred_period_internship_from'),
							'pref_period_internship_to' => $this->input->post('prefrred_period_internship_to'),
							'father_name' => $this->input->post('father_name'),
							'mother_name' => $this->input->post('mother_name'),
							'email' => $this->input->post('email'),
							'mobile' => $this->input->post('mobile'),
							'gender' => $this->input->post('gender'),
							'permanent_address' => $this->input->post('permanent_address'),
							'address_during_internship' => $this->input->post('address_during_internship'),
							'institute_address' => $this->input->post('institute_address'),
							'contact_person_of_institute' => $this->input->post('contact_person_of_institute'),
							'contact_person_no_of_institute' => $this->input->post('contact_person_no_of_institute'),
							'internship_status' => 1,
							'created_by' => $this->input->post('email'),
							'updated_by' => $this->input->post('email'),
						);

						// Upload Profile 
						if ($_FILES['profile_photo']['name'] != '') {

							$this->load->library('fileupload');
							// Upload files ==============
							$profile_photo = $this->fileupload->uploadSingleFile($_FILES['profile_photo'], [
								'raw_file_name' => 'profile_photo',
								'file_name' => 'INTERN_PROFILE_PICTURE' . time(),
								'file_move_path' => INTERN_PROFILE_PICTURE,
								'allowed_file_types' => INTERN_PROFILE_FORMAT,
								'max_size'			=>  INTERN_PROFILE_SIZE,
								'allowed_mime_types' => array('image/jpg', 'image/jpeg', 'image/png')
							]);

							// After getting result of file upload
							if ($profile_photo['status'] == false) {
								$this->db->trans_rollback();
								$profile_photo['msg'] = 'Profile photo:' . $profile_photo['msg'];
								echo json_encode($profile_photo);
								die;
							} else {
								$internship_data['profile_photo'] = $profile_photo['file'];
							}
						} else {
							echo  json_encode([
								'status' => false,
								'msg' => 'Please provide profile picture to proceed'
							]);
							die;
						}

						// Upload Signature 
						if ($_FILES['signature']['name'] != '') {

							$this->load->library('fileupload');
							// Upload files ==============
							$signature = $this->fileupload->uploadSingleFile($_FILES['signature'], [
								'raw_file_name' => 'signature',
								'file_name' => 'INTERN_SIGNATURE_' . time(),
								'file_move_path' => INTERN_SIGNATURE_PICTURE,
								'allowed_file_types' => INTERN_PROFILE_FORMAT,
								'max_size'			=>  INTERN_PROFILE_SIZE,
								'allowed_mime_types' => array('image/jpg', 'image/jpeg', 'image/png')
							]);

							// After getting result of file upload
							if ($signature['status'] == false) {
								$this->db->trans_rollback();
								$signature['msg'] = 'Signature photo:' . $signature['msg'];
								echo json_encode($signature);
								die;
							} else {
								$internship_data['signature'] = $signature['file'];
							}
						} else {
							echo  json_encode([
								'status' => false,
								'msg' => 'Please provide signature to proceed'
							]);
							die;
						}


						// Upload CV document 
						if ($_FILES['cv']['name'] != '') {
							$this->load->library('fileupload');
							// Upload files ==============
							$cv = $this->fileupload->uploadSingleFile($_FILES['cv'], [
								'raw_file_name' => 'cv',
								'file_name' => 'INTERN_CV' . time(),
								'file_move_path' => INTERN_CV,
								'allowed_file_types' => INTERN_DOC_FORMAT,
								'max_size'			=>  INTERN_DOC_SIZE,
								'allowed_mime_types' => array('application/pdf')
							]);

							// After getting result of file upload
							if ($cv['status'] == false) {
								$this->db->trans_rollback();
								$cv['msg'] = 'CV:' . $cv['msg'];
								echo json_encode($cv);
								die;
							} else {
								$internship_data['cv'] = $cv['file'];
							}
						} else {
							echo  json_encode([
								'status' => false,
								'msg' => 'Please provide CV to proceed'
							]);
							die;
						}

						// Upload cover letter document 
						if ($_FILES['cover_letter']['name'] != '') {
							$this->load->library('fileupload');
							// Upload files ==============
							$cover_letter = $this->fileupload->uploadSingleFile($_FILES['cover_letter'], [
								'raw_file_name' => 'cover_letter',
								'file_name' => 'INTERN_COVER_LETTER' . time(),
								'file_move_path' => INTERN_COVER_LETTER,
								'allowed_file_types' => INTERN_DOC_FORMAT,
								'max_size'			=>  INTERN_DOC_SIZE,
								'allowed_mime_types' => array('application/pdf')
							]);

							// After getting result of file upload
							if ($cover_letter['status'] == false) {
								$this->db->trans_rollback();
								$cover_letter['msg'] = 'Cover Letter:' . $cover_letter['msg'];
								echo json_encode($cover_letter);
								die;
							} else {
								$internship_data['cover_letter'] = $cover_letter['file'];
							}
						} else {
							echo  json_encode([
								'status' => false,
								'msg' => 'Please provide cover letter to proceed'
							]);
							die;
						}

						// Upload reference letter document 
						if ($_FILES['reference_letter']['name'] != '') {
							$this->load->library('fileupload');
							// Upload files ==============
							$reference_letter = $this->fileupload->uploadSingleFile($_FILES['reference_letter'], [
								'raw_file_name' => 'reference_letter',
								'file_name' => 'INTERN_REFERENCE_LETTER' . time(),
								'file_move_path' => INTERN_REFERENCE_LETTER,
								'allowed_file_types' => INTERN_DOC_FORMAT,
								'max_size'			=>  INTERN_DOC_SIZE,
								'allowed_mime_types' => array('application/pdf')
							]);

							// After getting result of file upload
							if ($reference_letter['status'] == false) {
								$this->db->trans_rollback();

								$reference_letter['msg'] = 'Reference Letter:' . $reference_letter['msg'];
								echo json_encode($reference_letter);
								die;
							} else {
								$internship_data['reference_letter'] = $reference_letter['file'];
							}
						} else {
							echo  json_encode([
								'status' => false,
								'msg' => 'Please provide reference letter to proceed'
							]);
							die;
						}

						// Save the data
						$result = $this->db->insert('internship_tbl', $internship_data);

						if ($result) {
							echo  json_encode([
								'status' => true,
								'msg' => 'Your Internship Form has been submmited. Please note this reference number for your future communication: <b>' . $reference_no . '</b>'
							]);
							die;
						} else {
							echo  json_encode([
								'status' => false,
								'msg' => 'Server failed while saving data'
							]);
							die;
						}
					} else {
						$data = array(
							'status' => false,
							'msg' => 'Invalid Captcha.Please Try Again!!!'
						);
						echo json_encode($data);
						die;
					}
				} else {
					echo  json_encode([
						'status' => false,
						'msg' => validation_errors()
					]);
					die;
				}
			} else {
				$data = array(
					'status' => false,
					'msg' => 'Invalid Security Token'
				);
				echo json_encode($data);
				die;
			}
		} else {
			$data = array(
				'status' => false,
				'msg' => 'Empty Security Token'
			);
			echo json_encode($data);
			die;
		}
	}
}

/* End of file Internship_controller.php */
/* Location: ./application/controllers/Internship_controller.php */


// cv
// cover_letter
// reference_letter