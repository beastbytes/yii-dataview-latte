<?php

declare(strict_types=1);

namespace BeastBytes\Yii\DataView\Latte\Node\Column;

use Generator;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\IdentifierNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;

class DataColumnNode extends StatementNode
{
    private IdentifierNode $name;
    private ExpressionNode $property;

    public static function create(Tag $tag): self
    {
        $tag->expectArguments();
        $node = $tag->node = new self;
        $node->name = new IdentifierNode(ucfirst($tag->name));

        $node->property = $tag->parser->parseExpression();

        return $node;
    }


    public function print(PrintContext $context): string
    {
        return $context->format(
            <<<'MASK'
            echo "\n";
            echo "        new Yiisoft\Yii\DataView\Column\%node(%node)," %line;
            MASK,
            $this->name,
            $this->property,
        );
    }

    /**
     * @inheritDoc
     */
    public function &getIterator(): Generator
    {
        yield $this->name;
        yield $this->property;
    }
}