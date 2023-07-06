<?php

use yii\db\Migration;

/**
 * Class m230303_102038_add_columns_banquet_price_person_and_rent_room_only_to_rooms_table
 */
class m230303_102038_add_columns_banquet_price_person_and_rent_room_only_to_rooms_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%rooms}}', 'banquet_price_person', $this->integer()->notNull()->defaultValue(0)->after('banquet_price'));
        $this->addColumn('{{%rooms}}', 'rent_room_only', $this->integer()->notNull()->defaultValue(0)->after('rent_only'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%rooms}}', 'banquet_price_person');
        $this->dropColumn('{{%rooms}}', 'rent_room_only');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230303_102038_add_columns_banquet_price_person_and_rent_room_only_to_rooms_table cannot be reverted.\n";

        return false;
    }
    */
}
