<?php

use ccheng\eventmanager\widgets\Timeline;

/** @var $events \ccheng\eventmanager\models\BizEvent */
/** @var $week_days array */
/** @var $now_date array */
?>

<div class="history-view">
    <div class="body-box">
        <?= Timeline::widget([
            'events'    => $events,
            'week_days' => $week_days,
            'target'    => $now_date,
        ]); ?>
    </div>
</div>