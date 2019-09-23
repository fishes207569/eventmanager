<?php

use yii\db\Migration;

class m190919_062802_add_table_by_event_manager extends Migration
{
    public function safeUp()
    {
        $sql = <<<SQL
            CREATE TABLE `biz_event` (
              `event_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `event_name` varchar(200) NOT NULL COMMENT '事件名',
              `event_image_url` varchar(255) NOT NULL COMMENT '事件图片',
              `event_content` text NOT NULL COMMENT '事件内容',
              `event_year` smallint(4) NOT NULL COMMENT '事件所属年',
              `event_month` char(7) NOT NULL COMMENT '事件所属月',
              `event_date` date NOT NULL COMMENT '事件所属日',
              `event_create_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `event_update_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              `event_from_system` varchar(16) DEFAULT 'biz' COMMENT '所属系统',
              `event_author` varchar(32) DEFAULT '' COMMENT '操作者',
              PRIMARY KEY (`event_id`),
              KEY `idx_event_date` (`event_date`) USING BTREE,
              KEY `idx_event_name` (`event_name`) USING BTREE,
              KEY `idx_event_author` (`event_author`) USING BTREE,
              KEY `idx_event_system` (`event_from_system`) USING BTREE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
