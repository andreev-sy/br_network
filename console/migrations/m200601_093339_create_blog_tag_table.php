<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%blog_tag}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%user}}`
 */
class m200601_093339_create_blog_tag_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%blog_tag}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'alias' => $this->string()->notNull(),
            'parent_id' => $this->integer(),
            'sort' => $this->integer(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE CURRENT_TIMESTAMP'),
            'created_by' => $this->integer(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-blog_tag-created_by}}',
            '{{%blog_tag}}',
            'created_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-blog_tag-created_by}}',
            '{{%blog_tag}}',
            'created_by',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            '{{%idx-blog_tag-updated_by}}',
            '{{%blog_tag}}',
            'updated_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-blog_tag-updated_by}}',
            '{{%blog_tag}}',
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
            '{{%fk-blog_tag-created_by}}',
            '{{%blog_tag}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-blog_tag-created_by}}',
            '{{%blog_tag}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-blog_tag-updated_by}}',
            '{{%blog_tag}}'
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            '{{%idx-blog_tag-updated_by}}',
            '{{%blog_tag}}'
        );

        $this->dropTable('{{%blog_tag}}');
    }
}
