<?php

use Phinx\Migration\AbstractMigration;

class CreateTableChatStatuses extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('chat_statuses')
            ->addColumn('status', 'enum', ['values' => ["online", "offline"]])
            ->addColumn('user_id', 'integer')
            ->addTimestamps();

        $table->create();
    }

    public function down()
    {
        $exist = $this->hasTable('chat_statuses');
        if ($exist)
        {
            $this->dropTable('chat_statuses');
        }
    }
}
