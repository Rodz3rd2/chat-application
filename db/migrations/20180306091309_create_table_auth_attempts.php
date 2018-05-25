<?php

use AuthSlim\User\Models\AuthAttempt;
use Phinx\Migration\AbstractMigration;

class CreateTableAuthAttempts extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('auth_attempts')
            ->addColumn('email', 'string', ['limit' => AuthAttempt::$FIELDS_LENGTH['email']])
            ->addColumn('request_uri', 'string', ['limit' => AuthAttempt::$FIELDS_LENGTH['request_uri']])
            ->addColumn('ip_address', 'string', ['limit' => AuthAttempt::$FIELDS_LENGTH['ip_address']])
            ->addTimestamps();

        $table->create();
    }

    public function down()
    {
        $is_exist = $this->hasTable('auth_attempts');
        if ($is_exist)
        {
            $this->dropTable('auth_attempts');
        }
    }
}