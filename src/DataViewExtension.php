<?php

declare(strict_types=1);

namespace BeastBytes\Yii\DataView\Latte;

use Latte\Engine;
use Latte\Extension;

final class DataViewExtension extends Extension
{
    public function getTags(): array
    {
        return [
            'actionButton' => Node\Column\ActionButtonNode::create(...),
            'actionColumn' => Node\Column\ActionColumnNode::create(...),
            'checkboxColumn' => Node\Column\ColumnNode::create(...),
            'dataColumn' => Node\Column\ColumnNode::create(...),
            'dataField' => Node\DataField\DataFieldNode::create(...),
            'detailView' => Node\Widget\DetailViewNode::create(...),
            'gridView' => Node\Widget\GridViewNode::create(...),
            'listView' => Node\Widget\ListViewNode::create(...),
            'radioColumn' => Node\Column\ColumnNode::create(...),
            'serialColumn' => Node\Column\ColumnNode::create(...),
        ];
    }

    public function getCacheKey(Engine $engine): string
    {
        return md5(self::class);
    }
}