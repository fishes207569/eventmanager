<?php
namespace ccheng\eventmanager\helpers;

use ccheng\eventmanager\helpers\ConfigHelper;

class HtmlHelper
{

    public static function buildTags($event)
    {
        $tagHtml = '';
        $color   = ConfigHelper::getTagColor($event['event_level']);
        $tags    = explode(',', $event['event_tags']);
        foreach ($tags as $tag) {
            $tagHtml .= "<span class='label' style='background-color: {$color};margin:0 2px'>$tag</span>";
        }

        return $tagHtml;
    }

}