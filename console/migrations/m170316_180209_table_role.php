<?php

use yii\db\Migration;
use yii\db\mysql\Schema;
use common\models\User;
use common\models\Role;

class m170316_180209_table_role extends Migration
{

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->createTable('{{%role}}',
            [
            'id' => Schema::TYPE_PK,
            'title' => Schema::TYPE_STRING,
        ]);

        $this->addColumn('{{%user}}', 'role_id', Schema::TYPE_INTEGER);
        $this->addForeignKey('fk_user_role', '{{%user}}', 'role_id',
            '{{%role}}', 'id', 'RESTRICT', 'CASCADE');

        $this->insert('{{%role}}', ['id' => 1, 'title' => 'администратор']);
        $this->insert('{{%role}}', ['id' => 2, 'title' => 'пользователь']);
        $user = new User([
            'id' => 1,
            'username' => 'Admin',
            'email' => 'admin@sqlup.dev',
            'status' => User::STATUS_ACTIVE,
            'role_id' => Role::ROLE_ADMIN,
        ]);
        $user->generateAuthKey();
        $user->setPassword('admin');
        $user->save();
    }

    public function safeDown()
    {

        $this->dropForeignKey('fk_user_role', '{{%user}}');
        $this->dropColumn('{{%user}}', 'role_id');
        $this->dropTable('{{%role}}');
        $this->delete('{{%user}}', 1);
    }
}
