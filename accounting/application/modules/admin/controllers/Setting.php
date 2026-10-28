<?php
include_once APPPATH . "modules/admin/core/MY_Controller.php";

/**
 * Created by PhpStorm.
 * User: ITMart-6
 * Date: 10/20/2016
 * Time: 2:02 PM
 */
class Setting extends MY_Controller
{

    var $page = "setting";

    function __construct()
    {
        parent::__construct();
        $this->load->model('User_model', 'model');
        $this->controller = get_class($this);
    }

    function index()
    {
        $d = [
            'page' => "$this->page/index",
            'obj' => $this->obj
        ];
        $this->view($d);
    }

    function profile()
    {
        $d = [
            'page' => "$this->page/form",
            'obj' => $this->obj
        ];
        $this->_form($d);
    }

    function _form(&$d, $_id = 0)
    {


        $this->form_validation->set_rules("form[CurrentPassword]", "Current Password", "required|callback_oldpassword_check");
        $this->form_validation->set_rules("form[NewPassword]", "New Password", "required|min_length[8]");
        $this->form_validation->set_rules("form[ReTypeNewPassword]", "Confirm Password", "required|matches[form[NewPassword]]");

//        p($this->input->post('form[UserId]'));

        if ($this->form_validation->run()) {

            $_id = $this->input->post('form[UserId]');
//            $post = $this->input->post('form') ;
            $post = array(
                'Password' => sha1($this->input->post('form[ReTypeNewPassword]'))
            );

            $this->db->trans_begin();
            if ($_id) {
                $this->model->update($_id, $post);
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $this->session->set_flashdata('notification', ["alert" => "danger", "text" => '<strong> ' . (!$_id ? "Creation." : "Update.") . ' Failure !!! </strong>']);
            } else {
                p($this->db->last_query());
                $this->session->set_flashdata('notification', ["alert" => "success", "text" => '<strong> User Data Successfully Updated.</strong>']);
                $this->db->trans_commit();
            }
            $this->session->sess_destroy();
            redirect(base_url() . 'admin');
        }

        $this->view($d);

    }

    public function oldpassword_check($old_password)
    {

        $userid = $this->input->post('form[UserId]');

        $old_password_hash = sha1($old_password);
        $old_password_db_hash = $this->model->getById($userid);
//        p($this->db->last_query());
//        p($old_password_db_hash->Password);
//        p($old_password_hash);
        if ($old_password_hash != $old_password_db_hash->Password) {
            $this->form_validation->set_message('oldpassword_check', 'Old password not match');
            return FALSE;
        }
        return TRUE;
    }

}