<?php

use yii\db\Migration;

class m190926_062802_alter_table_by_event_manager_optimize_v1 extends Migration
{
    public function safeUp()
    {
        $sql = <<<SQL
            ALTER TABLE `biz_event` ADD COLUMN (`event_level` varchar(32) NOT NULL DEFAULT 'success' COMMENT '事件级别',
  `event_user_id` int(11) NOT NULL DEFAULT '0' COMMENT '添加用户',`event_time` time NOT NULL COMMENT '事件发生时间',`event_tags` varchar(255) DEFAULT '' COMMENT '事件标签');
SQL;
        $this->execute($sql);
    }

    public function safeDown()
    {
        echo "m190919_062802_add_table_by_event_manager cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190919_062802_add_table_by_event_manager cannot be reverted.\n";

        return false;
    }
    */
}
