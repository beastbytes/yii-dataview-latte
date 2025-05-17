<?php

declare(strict_types=1);

namespace BeastBytes\Yii\DataView\Latte\Node\Widget;

use BeastBytes\Yii\DataView\Latte\Node\ConfigurationTrait;
use Generator;
use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\IdentifierNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;

class GridViewNode extends StatementNode
{
    use ConfigurationTrait;

    public ExpressionNode $dataReader;
    public AreaNode $columns;
    public IdentifierNode $name;

    public static function create(Tag $tag): Generator
    {
        $tag->expectArguments();
        $node = $tag->node = new self;
        $node->name = new IdentifierNode(ucfirst($tag->name));
        $node->dataReader = $tag->parser->parseExpression();
        $node->configuration = $tag->parser->parseModifier();

        [$node->columns, $endTag] = yield;
        return $node;
    }

    public function print(PrintContext $context): string
    {
        return $context->format(
            <<<'MASK'
            echo Yiisoft\Yii\DataView\%node::widget() %line
                ->dataReader(%node)
                ->columns(%node)
            %raw
            ;
            MASK,
            $this->name,
            $this->position,
            $this->dataReader,
            $this->columns,
            $this->parseConfiguration($context)
        );
    }

    /**
     * @inheritDoc
     */
    public function &getIterator(): Generator
    {
        yield $this->name;
        yield $this->dataReader;;
        yield $this->columns;
    }
}