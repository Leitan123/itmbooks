<?php

class Migration_Payment_create_table extends CI_Migration
{

    public function __construct()
    {
        parent::__construct();
        $this->load->dbforge();
    }

    public function up()
    {
        $fields = [
            "PaymentId" => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            "LogId" => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            "Option" => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            "Amount" => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            "CreatedBy" => [
                'type' => 'INT',
                'constraint' => 11
            ],
            "CreatedDate" => [
                'type' => 'DATETIME'
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
        $this->dbforge->add_key('PaymentId', TRUE);
        $this->dbforge->create_table('payment', TRUE);

    }

    public function down()
    {
        $this->dbforge->drop_table('payment', TRUE);
    }

}