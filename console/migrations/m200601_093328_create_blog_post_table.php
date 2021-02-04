<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%blog_post}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%user}}`
 */
class m200601_093328_create_blog_post_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%blog_post}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'alias' => $this->string()->notNull(),
            'intro' => $this->text(),
            'short_intro' => $this->text(),
            'featured' => $this->tinyInteger()->defaultValue(0),
            'published' => $this->tinyInteger()->defaultValue(0),
            'published_at' => $this->timestamp()->defaultValue(null),
            'sort' => $this->integer(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE CURRENT_TIMESTAMP'),
            'created_by' => $this->integer(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-blog_post-created_by}}',
            '{{%blog_post}}',
            'created_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-blog_post-created_by}}',
            '{{%blog_post}}',
            'created_by',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            '{{%idx-blog_post-updated_by}}',
            '{{%blog_post}}',
            'updated_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-blog_post-updated_by}}',
            '{{%blog_post}}',
            'updated_by',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-blog_post-alias',
            'blog_post',
            'alias',
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-blog_post-created_by}}',
            '{{%blog_post}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-blog_post-created_by}}',
            '{{%blog_post}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-blog_post-updated_by}}',
            '{{%blog_post}}'
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            '{{%idx-blog_post-updated_by}}',
            '{{%blog_post}}'
        );

        $this->dropIndex(
            'idx-blog_post-alias',
            'blog_post'
        );

        $this->dropTable('{{%blog_post}}');
    }
}
