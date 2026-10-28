<?php
/**
 * Created by PhpStorm.
 * User: IT MARTX
 * Date: 2/14/2019
 * Time: 12:22 PM
 */

class Migration_Employee_create_table extends CI_Migration
{

    public function __construct()
    {
        parent::__construct();
        $this->load->dbforge();
    }

    public function up()
    {
        $fields = [
            "EmployeeId" => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            "FullName" => [
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
            "UserName" => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            "Password" => [
                'type' => 'text',
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
        $this->dbforge->add_key('EmployeeId', TRUE);
        $this->dbforge->create_table('employee', TRUE);

    }

    public function down()
    {
        $this->dbforge->drop_table('employee', TRUE);
    }

}