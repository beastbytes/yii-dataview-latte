<?php

declare(strict_types=1);

namespace BeastBytes\Yii\DataView\Latte\Node\Column;

use Generator;
use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\Php\IdentifierNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;

class ActionColumnNode extends StatementNode
{
    public AreaNode $buttons;
    public IdentifierNode $name;

    public static function create(Tag $tag): Generator
    {
        $node = $tag->node = new self;
        $node->name = new IdentifierNode(ucfirst($tag->name));

        [$node->buttons, $endTag] = yield;
        return $node;
    }

    public function print(PrintContext $context): string
    {
        return $context->format(
            <<<'MASK'
            new Yiisoft\Yii\DataView\Column\%node( %line
                buttons: [
            %node
                ],
            ),
            MASK,
            $this->name,
            $this->position,
            $this->buttons
        );
    }

    /**
     * @inheritDoc
     */
    public function &getIterator(): Generator
    {
        yield $this->name;
        yield $this->buttons;
    }
}