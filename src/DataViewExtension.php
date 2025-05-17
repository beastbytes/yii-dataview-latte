<?php

declare(strict_types=1);

namespace BeastBytes\Yii\DataView\Latte;

use Latte\Compiler\Nodes\TemplateNode;
use Latte\Engine;
use Latte\Extension;

final class DataViewExtension extends Extension
{
    public function getTags(): array
    {
        return [
            'actionButton' => Node\Column\ActionButtonNode::create(...),
            'actionColumn' => Node\Column\ActionColumnNode::create(...),
            'checkboxColumn' => Node\Column\CheckboxColumnNode::create(...),
            'dataColumn' => Node\Column\DataColumnNode::create(...),
            'dataField' => Node\Field\FieldNode::create(...),
            'detailView' => Node\DetailViewNode::create(...),
            'gridView' => Node\GridViewNode::create(...),
            'listView' => Node\ListViewNode::create(...),
            'radioColumn' => Node\Column\RadioColumnNode::create(...),
            'serialColumn' => Node\Column\SerialColumnNode::create(...),
        ];
    }

    public function getCacheKey(Engine $engine): string
    {
        return md5(self::class);
    }
}