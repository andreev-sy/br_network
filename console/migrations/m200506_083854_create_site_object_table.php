<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%site_object}}`.
 */
class m200506_083854_create_site_object_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%site_object}}', [
            'id' => $this->primaryKey(),
            'table_name' => $this->string(),
            'row_id' => $this->integer(),
        ]);

        $this->createIndex(
            'idx-unique-teilnehmer-durchfuehrung-spieler',
            'site_object',
            ['table_name', 'row_id'],
            true
        );
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-unique-teilnehmer-durchfuehrung-spieler', 'site_object');
        $this->dropTable('{{%site_object}}');
    }
}
