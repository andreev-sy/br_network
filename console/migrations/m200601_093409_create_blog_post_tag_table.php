<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%blog_post_tag}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%blog_post}}`
 * - `{{%blog_tag}}`
 */
class m200601_093409_create_blog_post_tag_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%blog_post_tag}}', [
            'blog_post_id' => $this->integer(),
            'blog_tag_id' => $this->integer(),
            'sort' => $this->integer(),
        ]);
        
        // creates index for column `blog_post_id`
        $this->createIndex(
            '{{%idx-blog_post_id-blog_tag_id}}',
            '{{%blog_post_tag}}',
            ['blog_post_id', 'blog_tag_id'],
            true
        );

        // add foreign key for table `{{%blog_post}}`
        $this->addForeignKey(
            '{{%fk-blog_post_tag-blog_post_id}}',
            '{{%blog_post_tag}}',
            'blog_post_id',
            '{{%blog_post}}',
            'id',
            'CASCADE'
        );

        // add foreign key for table `{{%blog_tag}}`
        $this->addForeignKey(
            '{{%fk-blog_post_tag-blog_tag_id}}',
            '{{%blog_post_tag}}',
            'blog_tag_id',
            '{{%blog_tag}}',
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
            '{{%fk-blog_post_tag-blog_post_id}}',
            '{{%blog_post_tag}}'
        );

        // drops index for column `blog_post_id`
        $this->dropIndex(
            '{{%idx-blog_post_id-blog_tag_id}}',
            '{{%blog_post_tag}}'
        );

        // drops foreign key for table `{{%blog_tag}}`
        $this->dropForeignKey(
            '{{%fk-blog_post_tag-blog_tag_id}}',
            '{{%blog_post_tag}}'
        );

        $this->dropTable('{{%blog_post_tag}}');
    }
}
