<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%site_object_media_target}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%site_object}}`
 */
class m200506_083921_create_site_object_media_target_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%site_object_media_target}}', [
            'id' => $this->primaryKey(),
            'site_object_id' => $this->integer(),
            'type' => $this->string(),
            'index' => $this->integer(),
        ]);

        // creates index for column `site_object_id`
        $this->createIndex(
            '{{%idx-site_object_media_target-site_object_id}}',
            '{{%site_object_media_target}}',
            'site_object_id'
        );

        // add foreign key for table `{{%site_object}}`
        $this->addForeignKey(
            '{{%fk-site_object_media_target-site_object_id}}',
            '{{%site_object_media_target}}',
            'site_object_id',
            '{{%site_object}}',
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
            '{{%fk-site_object_media_target-site_object_id}}',
            '{{%site_object_media_target}}'
        );

        // drops index for column `site_object_id`
        $this->dropIndex(
            '{{%idx-site_object_media_target-site_object_id}}',
            '{{%site_object_media_target}}'
        );

        $this->dropTable('{{%site_object_media_target}}');
    }
}
