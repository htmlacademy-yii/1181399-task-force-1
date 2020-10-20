<?php

use yii\db\Migration;

/**
 * Class m201020_062741_vk_auth
 */
class m201020_062741_vk_auth extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('users', 'vk_id', $this->string()->null());
        $this->alterColumn('users', 'password', $this->string(255)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201020_062741_vk_auth cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201020_062741_vk_auth cannot be reverted.\n";

        return false;
    }
    */
}
