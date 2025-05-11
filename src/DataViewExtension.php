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
            'actionButton' => Node\ActionButtonNode::create(...),
            'actionColumn' => Node\ColumnNode::create(...),
            'checkboxColumn' => Node\ColumnNode::create(...),
            'dataColumn' => Node\ColumnNode::create(...),
            'dataField' => Node\FieldNode::create(...),
            'detailView' => Node\DetailViewNode::create(...),
            'gridView' => Node\GridViewNode::create(...),
            'listView' => Node\ListViewNode::create(...),
            'keysetPagination' => Node\PaginationNode::create(...),
            'offsetPagination' => Node\PaginationNode::create(...),
            'radioColumn' => Node\ColumnNode::create(...),
            'serialColumn' => Node\ColumnNode::create(...),
        ];
    }

    public function getCacheKey(Engine $engine): string
    {
        return md5(self::class);
    }
}