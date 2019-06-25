<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%street}}`.
 */
class m190625_203132_create_street_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%street}}', [
            'id' => $this->primaryKey(),
            'city_id' => $this->integer()->notNull(),
            'name' => $this->string(100)->notNull(),
            'ref' => $this->string(40)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-city-street_id',
            'street',
            'city_id'
        );

        $this->addForeignKey(
            'fk-city-street_id',
            'street',
            'city_id',
            'city',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%street}}');
    }
}
