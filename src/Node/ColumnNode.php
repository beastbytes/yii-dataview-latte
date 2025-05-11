<?php

declare(strict_types=1);

namespace BeastBytes\Yii\DataView\Latte\Node;

use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\IdentifierNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;

class ColumnNode extends StatementNode
{
    private IdentifierNode $name;
    private ExpressionNode|null $header = null;
    private ExpressionNode|null $footer = null;
    private ExpressionNode $columnAttributes;
    private ExpressionNode $bodyAttributes;
    private ExpressionNode $visible;

    public static function create(Tag $tag): self
    {
        $tag->expectArguments();
        $node = $tag->node = new self;
        $node->name = new IdentifierNode($tag->name);
        return $node;
    }


    public function print(PrintContext $context): string
    {
        //$this->parseConfiguration($context);

        return $context->format(
            <<<'MASK'
            echo Yiisoft\Yii\DataView\Column::%node(%node, %node, %node) %line;
            MASK,
            $this->name,
        );
    }

    /**
     * @inheritDoc
     */
    public function &getIterator(): \Generator
    {
        // TODO: Implement getIterator() method.
    }
}