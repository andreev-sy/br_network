<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%site_object_media}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%site_object}}`
 * - `{{%media}}`
 */
class m200506_083943_create_site_object_media_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%site_object_media}}', [
            'id' => $this->primaryKey(),
            'media_target_id' => $this->integer(),
            'media_id' => $this->integer(),
            'description' => $this->string(),
            'sort' => $this->integer(),
        ]);

        // creates index for column `media_id`
        $this->createIndex(
            '{{%idx-site_object_media-media_id}}',
            '{{%site_object_media}}',
            'media_id'
        );

        // add foreign key for table `{{%media}}`
        $this->addForeignKey(
            '{{%fk-site_object_media-media_id}}',
            '{{%site_object_media}}',
            'media_id',
            '{{%media}}',
            'id',
            'CASCADE'
        );

        // creates index for column `site_object_id`
        $this->createIndex(
            '{{%idx-site_object_media_target_id}}',
            '{{%site_object_media}}',
            'media_target_id'
        );

        // add foreign key for table `{{%site_object}}`
        $this->addForeignKey(
            '{{%fk-site_object_media_target_id}}',
            '{{%site_object_media}}',
            'media_target_id',
            '{{%site_object_media_target}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%site_object}}`
        $this->dropForeignKey(
            '{{%fk-site_object_media-media_id}}',
            '{{%site_object_media}}'
        );

        // drops index for column `site_object_id`
        $this->dropIndex(
            '{{%idx-site_object_media-media_id}}',
            '{{%site_object_media}}'
        );

        // drops foreign key for table `{{%media}}`
        $this->dropForeignKey(
            '{{%fk-site_object_media_target_id}}',
            '{{%site_object_media}}'
        );

        // drops index for column `media_id`
        $this->dropIndex(
            '{{%idx-site_object_media_target_id}}',
            '{{%site_object_media}}'
        );

        $this->dropTable('{{%site_object_media}}');
    }
}
