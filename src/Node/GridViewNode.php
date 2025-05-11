<?php

declare(strict_types=1);

namespace BeastBytes\Yii\DataView\Latte\Node;

use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;

class GridViewNode extends StatementNode
{
    public static function create(Tag $tag): self
    {
        $tag->expectArguments();
        $node = $tag->node = new self;
        return $node;
    }

    public function print(PrintContext $context): string
    {
        // TODO: Implement print() method.
    }

    /**
     * @inheritDoc
     */
    public function &getIterator(): \Generator
    {
        // TODO: Implement getIterator() method.
    }
}