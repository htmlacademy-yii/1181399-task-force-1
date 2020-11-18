<?php

use yii\db\Migration;

/**
 * Class m201116_052421_add_views_to_users_table
 */
class m201116_052421_add_views_to_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('users', 'views', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201116_052421_add_views_to_users_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201116_052421_add_views_to_users_table cannot be reverted.\n";

        return false;
    }
    */
}
