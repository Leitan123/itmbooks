<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Job_card extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
    }

    /*
    INDEX
    */

    public function index()
    {
        $this->db->select('
            job_card.*,
            customer.CustomerName,
            customer.CompanyName,
            items.ItemName
        ');

        $this->db->from('job_card');

        $this->db->join(
            'customer',
            'customer.CustomerId = job_card.CustomerID',
            'left'
        );

        $this->db->join(
            'items',
            'items.ItemID = job_card.ServiceID',
            'left'
        );

        $this->db->where(
            'job_card.IsDeleted',
            0
        );

        $data['records'] =
            $this->db->get()->result();

        $this->load->view(
            'pages/job_card/index',
            $data
        );
    }

    /*
    CREATE
    */

    public function create($QuotationID = 0)
    {
        $data['obj'] = null;

        $data['quotation'] = null;

        $data['quotation_items'] = [];

        /*
        CUSTOMERS
        */

        $data['customers'] = $this->db
            ->get_where(
                'customer',
                ['IsDeleted' => 0]
            )
            ->result();

        /*
        ITEMS
        */

        $data['items'] = $this->db
            ->get_where(
                'items',
                ['IsDeleted' => 0]
            )
            ->result();

        /*
        EMPLOYEES
        */

        $data['employees'] = $this->db
            ->get_where(
                'employee',
                ['IsDeleted' => 0]
            )
            ->result();

        /*
        QUOTATION
        */

        if($QuotationID > 0)
        {
            $data['quotation'] = $this->db
                ->get_where(
                    'quotation',
                    [
                        'QuotationID' =>
                        $QuotationID
                    ]
                )
                ->row();

            $data['quotation_items'] = $this->db
                ->get_where(
                    'quotation_items',
                    [
                        'QuotationID' =>
                        $QuotationID
                    ]
                )
                ->result();

            /*
            UPDATE STATUS
            */

            $this->db
                ->where(
                    'QuotationID',
                    $QuotationID
                );

            $this->db->update(
                'quotation',
                [
                    'Status' => 'Approved'
                ]
            );
        }

        $this->load->view(
            'pages/job_card/form',
            $data
        );
    }

    /*
    EDIT
    */

    public function edit($JobCardID)
    {
        $data['obj'] = $this->db
            ->get_where(
                'job_card',
                [
                    'JobCardID' =>
                    $JobCardID
                ]
            )
            ->row();

        $data['quotation'] = null;

        $data['quotation_items'] = [];

        /*
        CUSTOMERS
        */

        $data['customers'] = $this->db
            ->get_where(
                'customer',
                ['IsDeleted' => 0]
            )
            ->result();

        /*
        ITEMS
        */

        $data['items'] = $this->db
            ->get_where(
                'items',
                ['IsDeleted' => 0]
            )
            ->result();

        /*
        EMPLOYEES
        */

        $data['employees'] = $this->db
            ->get_where(
                'employee',
                ['IsDeleted' => 0]
            )
            ->result();

        /*
        GET QUOTATION
        */

        if(!empty($data['obj']->QuotationID))
        {
            $data['quotation'] = $this->db
                ->get_where(
                    'quotation',
                    [
                        'QuotationID' =>
                        $data['obj']->QuotationID
                    ]
                )
                ->row();

            $data['quotation_items'] = $this->db
                ->get_where(
                    'quotation_items',
                    [
                        'QuotationID' =>
                        $data['obj']->QuotationID
                    ]
                )
                ->result();
        }

        /*
        EMPLOYEE WORKS
        */

        $data['jobEmployees'] = $this->db
            ->get_where(
                'job_card_employees',
                [
                    'JobCardID' =>
                    $JobCardID,

                    'IsDeleted' => 0
                ]
            )
            ->result();

        $this->load->view(
            'pages/job_card/form',
            $data
        );
    }

    /*
    SAVE
    */

    public function save()
    {
        $form = $this->input->post('form');

        $data = [

            'JobCardNo' =>
                $form['JobCardNo'],

            'JobCardDate' =>
                $form['JobCardDate'],

            'CustomerID' =>
                $form['CustomerID'],

            'CustomerName' =>
                $form['CustomerName'],

            'ServiceID' =>
                $form['ServiceID'],

            'Title' =>
                $form['Title'],

            'Description' =>
                $form['Description'],

            'Priority' =>
                $form['Priority'],

            'Status' =>
                $form['Status'],

            'QuotationID' =>
                $form['QuotationID'],

            'EstimatedAmount' =>
                $form['EstimatedAmount'],

            'EstimatedDays' =>
                $form['EstimatedDays'],

            'StartDate' =>
                $form['StartDate'],

            'Deadline' =>
                $form['Deadline'],

            'Remarks' =>
                $form['Remarks']

        ];

        /*
        UPDATE
        */

        if(!empty($form['JobCardID']))
        {
            $JobCardID =
                $form['JobCardID'];

            $data['ModifiedDate'] =
                date('Y-m-d H:i:s');

            $this->db
                ->where(
                    'JobCardID',
                    $JobCardID
                );

            $this->db->update(
                'job_card',
                $data
            );

            /*
            DELETE OLD EMPLOYEES
            */

            $this->db
                ->where(
                    'JobCardID',
                    $JobCardID
                );

            $this->db->delete(
                'job_card_employees'
            );
        }
        else
        {
            /*
            INSERT
            */

            $data['CreatedDate'] =
                date('Y-m-d H:i:s');

            $data['IsDeleted'] = 0;

            $this->db->insert(
                'job_card',
                $data
            );

            $JobCardID =
                $this->db->insert_id();
        }

        /*
        SAVE EMPLOYEE WORKS
        */

        $employees =
            $this->input->post('employees');

        if(!empty($employees))
        {
            foreach($employees as $emp)
            {
                if(empty($emp['EmployeeID']))
                {
                    continue;
                }

                $days =
                    isset($emp['Days'])
                    ? $emp['Days']
                    : 0;

                $hours =
                    isset($emp['Hours'])
                    ? $emp['Hours']
                    : 0;

                $minutes =
                    isset($emp['Minutes'])
                    ? $emp['Minutes']
                    : 0;

                $cost =
                    isset($emp['CostPerMinute'])
                    ? $emp['CostPerMinute']
                    : 0;

                /*
                TOTAL MINUTES
                */

                $totalMinutes =
                    ($days * 24 * 60)
                    +
                    ($hours * 60)
                    +
                    $minutes;

                /*
                TOTAL COST
                */

                $totalCost =
                    $totalMinutes * $cost;

                /*
                INSERT
                */

                $this->db->insert(
                    'job_card_employees',
                    [

                        'JobCardID' =>
                            $JobCardID,

                        'EmployeeID' =>
                            $emp['EmployeeID'],

                        'WorkDate' =>
                            $emp['WorkDate'],

                        'Days' =>
                            $days,

                        'Hours' =>
                            $hours,

                        'Minutes' =>
                            $minutes,

                        'TotalMinutes' =>
                            $totalMinutes,

                        'CostPerMinute' =>
                            $cost,

                        'TotalCost' =>
                            $totalCost,

                        'WorkDescription' =>
                            isset($emp['WorkDescription'])
                            ? $emp['WorkDescription']
                            : '',

                        'CreatedDate' =>
                            date('Y-m-d H:i:s'),

                        'IsDeleted' => 0

                    ]
                );
            }
        }

        redirect('admin/job_card');
    }

    /*
    DELETE
    */

    public function delete($JobCardID)
    {
        $this->db
            ->where(
                'JobCardID',
                $JobCardID
            );

        $this->db->update(
            'job_card',
            [
                'IsDeleted' => 1
            ]
        );

        redirect('admin/job_card');
    }

    /*
    GET EMPLOYEE COST
    */

    public function get_employee_cost()
    {
        $EmployeeID =
            $this->input->post('EmployeeID');

        $employee = $this->db
            ->get_where(
                'employee',
                [
                    'EmployeeID' =>
                    $EmployeeID
                ]
            )
            ->row();

        echo json_encode($employee);
    }

}