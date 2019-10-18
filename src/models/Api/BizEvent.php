<?php

namespace ccheng\eventmanager\models\api;

use ccheng\eventmanager\models\BizEvent as EventBase;

/**
 * This is the model class for table "biz_event".
 *
 * @property integer $event_id
 * @property string  $event_name
 * @property string  $event_content
 * @property string  $event_image
 * @property integer $event_year
 * @property string  $event_month
 * @property string  $event_date
 * @property string  $event_time
 * @property string  $event_create_at
 * @property string  $event_update_at
 * @property string  $event_from_system
 * @property string  $event_author
 * @property string  $event_level
 * @property string  $event_user_id
 * @property string  $event_tags
 */
class BizEvent extends EventBase
{
    public function fields()
    {
        $fields = parent::fields();
        unset(
            $fields['event_image'],
            $fields['event_year'],
            $fields['event_month'],
            $fields['event_create_at'],
            $fields['event_update_at'],
            $fields['event_user_id']
        );

        return $fields;
    }
}
