<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%blog_post_block}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%blog_post}}`
 * - `{{%blog_block}}`
 */
class m200601_093418_create_blog_post_block_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%blog_post_block}}', [
            'id' => $this->primaryKey(),
            'blog_post_id' => $this->integer(),
            'blog_block_id' => $this->integer(),
            'content' => $this->text(),
            'sort' => $this->integer(),
        ]);

        // creates index for column `blog_post_id`
        $this->createIndex(
            '{{%idx-blog_post_block-blog_post_id}}',
            '{{%blog_post_block}}',
            'blog_post_id'
        );

        // add foreign key for table `{{%blog_post}}`
        $this->addForeignKey(
            '{{%fk-blog_post_block-blog_post_id}}',
            '{{%blog_post_block}}',
            'blog_post_id',
            '{{%blog_post}}',
            'id',
            'CASCADE'
        );

        // creates index for column `blog_block_id`
        $this->createIndex(
            '{{%idx-blog_post_block-blog_block_id}}',
            '{{%blog_post_block}}',
            'blog_block_id'
        );

        // add foreign key for table `{{%blog_block}}`
        $this->addForeignKey(
            '{{%fk-blog_post_block-blog_block_id}}',
            '{{%blog_post_block}}',
            'blog_block_id',
            '{{%blog_block}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%blog_post}}`
        $this->dropForeignKey(
            '{{%fk-blog_post_block-blog_post_id}}',
            '{{%blog_post_block}}'
        );

        // drops index for column `blog_post_id`
        $this->dropIndex(
            '{{%idx-blog_post_block-blog_post_id}}',
            '{{%blog_post_block}}'
        );

        // drops foreign key for table `{{%blog_block}}`
        $this->dropForeignKey(
            '{{%fk-blog_post_block-blog_block_id}}',
            '{{%blog_post_block}}'
        );

        // drops index for column `blog_block_id`
        $this->dropIndex(
            '{{%idx-blog_post_block-blog_block_id}}',
            '{{%blog_post_block}}'
        );

        $this->dropTable('{{%blog_post_block}}');
    }
}
