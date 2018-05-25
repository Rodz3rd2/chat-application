<?php

use Phinx\Migration\AbstractMigration;

class AddColumnPictureInUsersTable extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('users')
            ->addColumn('picture', 'string', ['after' => "password"]);

        $table->save();
    }

    public function down()
    {
        $table_exist = $this->hasTable('users');

        if ($table_exist)
        {
            $table = $this->table('users');

            $column_exist = $table->hasColumn('picture');
            if ($column_exist)
            {
                $table->removeColumn('picture');
            }
        }
    }
}
