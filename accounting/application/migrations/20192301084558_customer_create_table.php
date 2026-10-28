<?php

class Migration_Customer_create_table extends CI_Migration
{

    public function __construct()
    {
        parent::__construct();
        $this->load->dbforge();
    }

    public function up()
    {
        $fields = [
            "CustomerId" => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            "CustomerNo" => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            "CardNo" => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            "CustomerName" => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            "Address" => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            "ContactNo" => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            "NICNo" => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            "ProposalAmount" => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            "LoanInt" => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            "Days" => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            "InstallmentAmount" => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            "LoanTakenDate" => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            "SettlementDate" => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            "Remark" => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            "Image" => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            "CreatedBy" => [
                'type' => 'Int',
                'constraint' => 11
            ],
            "CreatedDate" => [
                'type' => 'DATETIME'
            ],
            "ModifiedBy" => [
                'type' => 'Int',
                'constraint' => 11
            ],
            "ModifiedDate" => [
                'type' => 'TIMESTAMP'
            ],
            "IsDeleted" => [
                'type' => 'INT',
                'constraint' => 0,
                'default' => 0
            ]
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('CustomerId', TRUE);
        $this->dbforge->create_table('customer', TRUE);

    }

    public function down()
    {
        $this->dbforge->drop_table('customer', TRUE);
    }

}