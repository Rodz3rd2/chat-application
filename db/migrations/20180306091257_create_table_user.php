<?php

use AuthSlim\User\Models\User;
use Phinx\Migration\AbstractMigration;

class CreateTableUser extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('users')
            ->addColumn('first_name', 'string', ['limit' => User::$FIELDS_LENGTH['first_name']])
            ->addColumn('last_name', 'string', ['limit' => User::$FIELDS_LENGTH['last_name']])
            ->addColumn('email', 'string', ['limit' => User::$FIELDS_LENGTH['email']])
            ->addColumn('password', 'string', ['limit' => User::$FIELDS_LENGTH['password']])
            ->addColumn('auth_token', 'string', ['limit' => User::$FIELDS_LENGTH['auth_token'], 'null' => true])
            ->addTimestamps();

        $table->create();
    }

    public function down()
    {
        $is_exist = $this->hasTable('users');
        if ($is_exist)
        {
            $this->dropTable('users');
        }
    }
}