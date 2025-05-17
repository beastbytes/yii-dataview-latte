<?php

declare(strict_types=1);

namespace BeastBytes\Yii\DataView\Latte\Node\Widget;

use BeastBytes\Yii\DataView\Latte\Node\ConfigurationTrait;
use Generator;
use Latte\CompileException;
use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\IdentifierNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;

class DetailViewNode extends StatementNode
{
    use ConfigurationTrait;

    public ExpressionNode $data;
    public AreaNode $fields;
    public IdentifierNode $name;

    /**
     * @throws CompileException
     */
    public static function create(Tag $tag): Generator
    {
        $tag->expectArguments();
        $node = $tag->node = new self;
        $node->name = new IdentifierNode(ucfirst($tag->name));
        $node->data = $tag->parser->parseExpression();
        $node->configuration = $tag->parser->parseModifier();

        [$node->fields, $endTag] = yield;
        return $node;
    }

    public function print(PrintContext $context): string
    {
        return $context->format(
            <<<'MASK'
            echo Yiisoft\Yii\DataView\%node::widget() %line
                ->data(%node)
                ->fields(%node)
            %raw
            ;
            MASK,
            $this->name,
            $this->position,
            $this->data,
            $this->fields,
            $this->parseConfiguration($context)

        );
    }

    /**
     * @inheritDoc
     */
    public function &getIterator(): Generator
    {
        yield $this->name;
        yield $this->data;
        yield $this->fields;
    }
}