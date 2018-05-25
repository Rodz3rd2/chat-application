<?php

use AuthSlim\User\Models\VerificationToken;
use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class CreateTableVerificationTokens extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('verification_tokens')
            ->addColumn('type', 'enum', ['values' => [VerificationToken::TYPE_REGISTER, VerificationToken::TYPE_RESET_PASSWORD]])
            ->addColumn('token', 'string', ['limit' => VerificationToken::$FIELDS_LENGTH['token']])
            ->addColumn('data', 'text', ['limit' => MysqlAdapter::TEXT_TINY])
            ->addColumn('is_verified', 'boolean', ['default' => 0])
            ->addTimestamps();

        $table->create();
    }

    public function down()
    {
        $is_exist = $this->hasTable('verification_tokens');
        if ($is_exist)
        {
            $this->dropTable('verification_tokens');
        }
    }
}
