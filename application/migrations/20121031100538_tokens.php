<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Tokens extends CI_Migration {

        public function up()
        {
                $this->dbforge->add_field(array(
                        'id' => array(
                                'type' => 'INT',
                                'constraint' => 5,
                                'unsigned' => TRUE,
                                'auto_increment' => TRUE
                        ),
                        'token' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '255',
                        ),
                        'type' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '1',
                        ),
                        'user_id' => array(
                                'type' => 'INTEGER'
                        ),
                        'status' => array(
                                'type' => 'INTEGER'
                        ),
                        'ttl' => array(
                                'type' => 'INTEGER'
                        ),
                        'created_at' => array(
                                'type' => 'DATETIME'
                        ),
                        'expire_at' => array(
                                'type' => 'DATETIME'
                        ),
                        'data' => array(
                                'type' => 'TEXT',
                                'null' => TRUE,
                        )
                ));
                $this->dbforge->add_key('id', TRUE);
                $this->dbforge->create_table('tokens');
        }

        public function down()
        {
                $this->dbforge->drop_table('tokens');
        }
}