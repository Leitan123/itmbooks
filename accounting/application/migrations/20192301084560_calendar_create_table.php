<?php
/**
 * Created by PhpStorm.
 * User: IT MARTX
 * Date: 2/14/2019
 * Time: 12:22 PM
 */

class Migration_Calendar_create_table extends CI_Migration
{

    public function __construct()
    {
        parent::__construct();
        $this->load->dbforge();
    }

    public function up()
    {
        $fields = [
            "CalendarId" => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            "Id" => [
                'type' => 'INT',
                'constraint' => 5
            ],
            "Date" => [
                'type' => 'DATE',
//                'constraint' => 5
            ],
            "Month" => [
                'type' => 'INT',
                'constraint' => 5
            ],
            "Year" => [
                'type' => 'INT',
                'constraint' => 11
            ],
            "Status" => [
                'type' => 'INT',
                'constraint' => 0,
                'default' => 1
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
        $this->dbforge->add_key('CalendarId', TRUE);
        $this->dbforge->create_table('calendar', TRUE);

    }

    public function down()
    {
        $this->dbforge->drop_table('calendar', TRUE);
    }

}