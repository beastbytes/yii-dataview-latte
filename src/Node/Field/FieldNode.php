<?php

declare(strict_types=1);

namespace BeastBytes\Yii\DataView\Latte\Node\Field;

use BeastBytes\Yii\DataView\Latte\Node\ArgumentTrait;
use Generator;
use Latte\Compiler\Nodes\Php\IdentifierNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;

class FieldNode extends StatementNode
{
    use ArgumentTrait;

    private ?IdentifierNode $name = null;

    public static function create(Tag $tag): self
    {
        $tag->expectArguments();
        $node = $tag->node = new self;
        $node->name = new IdentifierNode(ucfirst($tag->name));
        $node->arguments = $tag->parser->parseArguments();

        return $node;
    }

    public function print(PrintContext $context): string
    {
        return $context->format(
            <<<'MASK'
            new Yiisoft\Yii\DataView\Field\%node(%raw), %line
            MASK,
            $this->name,
            $this->parseArguments($context),
            $this->position,
        );
    }

    /**
     * @inheritDoc
     */
    public function &getIterator(): Generator
    {
        yield $this->name;
        yield $this->arguments;
    }
}