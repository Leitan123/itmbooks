<?php
include_once APPPATH . "modules/admin/core/MY_Controller.php";


/**
 * Created by PhpStorm.
 * User: ITMart-6
 * Date: 10/20/2016
 * Time: 12:09 PM
 */
class Reorder extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Update_order_model', 'model');
        $this->load->helper(array('form', 'url'));
    }

    public function do_update()
    {
//        $updateRecordsArray = $this->input->post('conid');
//        $table = $this->input->post('table');
//        $listingCounter = 1;
////
//        foreach ($updateRecordsArray as $recordIDValue) {
//            $data = array('Order' => $listingCounter);
//
//            $this->db->update($table,$data,"{$this->input->post('id')}=$recordIDValue");
//            $listingCounter++;
//        }

        // echo 'sadsadsa';

        $this->model->update_order();
    }

    public function do_category_update()
    {
        $this->model->update_category_order();

    }





}