<?php

use yii\db\Migration;

/**
 * Class m201016_141130_rename_checkbox
 */
class m201016_141130_rename_checkbox extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('users', 'public_contacts', 'private_contacts');
        $this->renameColumn('users', 'public_profile', 'private_profile');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201016_141130_rename_checkbox cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201016_141130_rename_checkbox cannot be reverted.\n";

        return false;
    }
    */
}
