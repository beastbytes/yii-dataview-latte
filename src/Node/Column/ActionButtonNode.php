<?php

declare(strict_types=1);

namespace BeastBytes\Yii\DataView\Latte\Node\Column;

use BeastBytes\Yii\DataView\Latte\Node\ArgumentTrait;
use Generator;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;

final class ActionButtonNode extends StatementNode
{
    use ArgumentTrait;

    public static function create(Tag $tag): self
    {
        $tag->expectArguments();
        $node = $tag->node = new self;
        $node->arguments = $tag->parser->parseArguments();

        return $node;
    }

    public function print(PrintContext $context): string
    {
        return $context->format(
            <<<'MASK'
            new Yiisoft\Yii\DataView\Column\ActionButton(%raw), %line
            MASK,
            $this->parseArguments($context),
            $this->position,
        );
    }

    /**
     * @inheritDoc
     */
    public function &getIterator(): Generator
    {
        yield $this->arguments;
    }
}