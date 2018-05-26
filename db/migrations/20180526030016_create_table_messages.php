<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class CreateTableMessages extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('messages')
            ->addColumn('message', 'text', ['limit' => MysqlAdapter::TEXT_TINY])
            ->addColumn('from_user_id', 'integer')
            ->addColumn('to_user_id', 'integer')
            ->addColumn('is_read', 'boolean')
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
