<?php

use EventManager\widgets\Timeline;

/** @var $events \EventManager\models\BizEvent */
/** @var $week_days array */
/** @var $now_date array */
?>

<div class="history-view">
    <section class="panel panel-default">
        <div class="panel-body">
            <div class="body-box">
            <?= Timeline::widget([
                'events'    => $events,
                'week_days' => $week_days,
                'target'    => $now_date,
            ]); ?>
        </div>
        </div>
    </section>
</div>