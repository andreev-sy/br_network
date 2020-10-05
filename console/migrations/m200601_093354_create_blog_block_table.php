<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%blog_block}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%user}}`
 */
class m200601_093354_create_blog_block_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%blog_block}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'alias' => $this->string()->notNull(),
            'template' => $this->text(),
            'inputs' => $this->text(),
            'type' => $this->string(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE CURRENT_TIMESTAMP'),
            'created_by' => $this->integer(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-blog_block-created_by}}',
            '{{%blog_block}}',
            'created_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-blog_block-created_by}}',
            '{{%blog_block}}',
            'created_by',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            '{{%idx-blog_block-updated_by}}',
            '{{%blog_block}}',
            'updated_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-blog_block-updated_by}}',
            '{{%blog_block}}',
            'updated_by',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-blog_block-created_by}}',
            '{{%blog_block}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-blog_block-created_by}}',
            '{{%blog_block}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-blog_block-updated_by}}',
            '{{%blog_block}}'
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            '{{%idx-blog_block-updated_by}}',
            '{{%blog_block}}'
        );

        $this->dropTable('{{%blog_block}}');
    }
}
