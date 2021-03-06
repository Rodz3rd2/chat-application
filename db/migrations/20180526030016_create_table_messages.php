<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class CreateTableMessages extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('messages')
            ->addColumn('message', 'text', ['limit' => MysqlAdapter::TEXT_TINY])
            ->addColumn('sender_id', 'integer')
            ->addColumn('receiver_id', 'integer')
            ->addColumn('is_read', 'boolean', ['default' => 0])
            ->addTimestamps();

        $table->create();
    }

    public function down()
    {
        $exist = $this->hasTable('messages');
        if ($exist)
        {
            $this->dropTable('messages');
        }
    }
}
