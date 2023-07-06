<?php

use yii\db\Migration;

/**
 * Class m230407_105014_add_columns_capacity_min_rooms_table
 */
class m230407_105014_add_columns_capacity_min_rooms_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%rooms}}', 'capacity_min', $this->integer()->defaultValue(NULL)->after('capacity'));
        $this->addColumn('{{%rooms}}', 'banquet_price_min', $this->integer()->defaultValue(NULL)->after('banquet_price'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%rooms}}', 'banquet_price_min');
        $this->dropColumn('{{%rooms}}', 'capacity_min');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230407_105014_add_columns_capacity_min_rooms_table cannot be reverted.\n";

        return false;
    }
    */
}
