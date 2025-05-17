<?php

declare(strict_types=1);

namespace BeastBytes\Yii\DataView\Latte\Node\Column;

use Generator;
use Latte\Compiler\Nodes\Php\IdentifierNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;

class RadioColumnNode extends StatementNode
{
    public IdentifierNode $name;

    public static function create(Tag $tag): self
    {
        $node = $tag->node = new self;
        $node->name = new IdentifierNode(ucfirst($tag->name));
        return $node;
    }


    public function print(PrintContext $context): string
    {
        return $context->format(
            <<<'MASK'
            new Yiisoft\Yii\DataView\Column\%node(), %line
            MASK,
            $this->name,
            $this->position,
        );
    }

    /**
     * @inheritDoc
     */
    public function &getIterator(): Generator
    {
        yield $this->name;
    }
}