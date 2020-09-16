<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%media}}`.
 */
class m200506_083920_create_media_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%media}}', [
            'id' => $this->primaryKey(),
            'file' => $this->string(),
            'file_ext' => $this->string(),
            'folder' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%media}}');
    }
}
