<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%site_object_seo}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%site_object}}`
 */
class m200506_084011_create_site_object_seo_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%site_object_seo}}', [
            'id' => $this->primaryKey(),
            'site_object_id' => $this->integer(),
            'heading' => $this->string(),
            'title' => $this->text(),
            'description' => $this->text(),
            'keywords' => $this->string(),
            'text1' => $this->text(),
            'text2' => $this->text(),
            'text3' => $this->text(),
            'pagination_title' => $this->text(),
            'pagination_description' => $this->text(),
            'pagination_keywords' => $this->string(),
            'pagination_heading' => $this->text(),
            'img_alt' => $this->string(),
        ]);

        // creates index for column `site_object_id`
        $this->createIndex(
            '{{%idx-site_object_seo-site_object_id}}',
            '{{%site_object_seo}}',
            'site_object_id'
        );

        // add foreign key for table `{{%site_object}}`
        $this->addForeignKey(
            '{{%fk-site_object_seo-site_object_id}}',
            '{{%site_object_seo}}',
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
            '{{%fk-site_object_seo-site_object_id}}',
            '{{%site_object_seo}}'
        );

        // drops index for column `site_object_id`
        $this->dropIndex(
            '{{%idx-site_object_seo-site_object_id}}',
            '{{%site_object_seo}}'
        );

        $this->dropTable('{{%site_object_seo}}');
    }
}
