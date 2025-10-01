<?php

declare(strict_types=1);

namespace BeastBytes\Yii\DataView\Latte\Node\Widget;

use BeastBytes\Yii\DataView\Latte\Node\ArgumentTrait;
use BeastBytes\Yii\DataView\Latte\Node\ConfigurationTrait;
use Generator;
use Latte\Compiler\Nodes\Php\Expression\VariableNode;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\IdentifierNode;
use Latte\Compiler\Nodes\Php\Scalar\StringNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;

class ListViewNode extends StatementNode
{
    use ArgumentTrait;
    use ConfigurationTrait;

    private IdentifierNode $name;

    public static function create(Tag $tag): self
    {
        $tag->expectArguments();
        $node = $tag->node = new self;
        $node->name = new IdentifierNode(ucfirst($tag->name));
        $node->arguments = $tag->parser->parseArguments();
        $node->configuration = $tag->parser->parseModifier();

        return $node;
    }

    public function print(PrintContext $context): string
    {
        /*
        $itemView = $this->getItemView();

        if ($itemView instanceof VariableNode) {
            return $context->format(
                <<<'MASK'
                if (is_string(%node)) { %line
                    echo Yiisoft\Yii\DataView\ListView\%node::widget()
                        ->dataReader(%node)
                        ->itemView(%node)
                    %raw
                    ;
                } else {
                    echo Yiisoft\Yii\DataView\ListView\%node::widget()
                        ->dataReader(%node)
                        ->itemCallback(%node)
                    %raw
                    ;
                }
                MASK,
                $this->getItemView(),
                $this->position,
                $this->name,
                $this->getDataReader(),
                $this->getItemView(),
                $this->parseConfiguration($context),
                $this->name,
                $this->getDataReader(),
                $this->getItemView(),
                $this->parseConfiguration($context)
            );
        }

        if ($itemView instanceof StringNode) {
            $mask = <<<'MASK'
                echo Yiisoft\Yii\DataView\ListView\%node::widget() %line
                    ->dataReader(%node)
                    ->itemView(%node)
                %raw
                ;
                MASK
            ;
        } else { // ClosureNode
            $mask = <<<'MASK'
                echo Yiisoft\Yii\DataView\ListView\%node::widget() %line
                    ->dataReader(%node)
                    ->itemCallback(%node)
                %raw
                ;
                MASK
            ;
        }

        return $context->format(
            $mask,
            $this->name,
            $this->position,
            $this->getDataReader(),
            $this->getItemView(),
            $this->parseConfiguration($context)
        );
        //*/

        return $context->format(
            <<<'MASK'
            echo Yiisoft\Yii\DataView\ListView\%node::widget() %line
                ->dataReader(%node)
                ->itemView(%node)
            %raw
            ;
            MASK,
            $this->name,
            $this->position,
            $this->getDataReader(),
            $this->getItemView(),
            $this->parseConfiguration($context)
        );
    }

    /**
     * @inheritDoc
     */
    public function &getIterator(): Generator
    {
        yield $this->name;
        yield $this->getDataReader();
        yield $this->getItemView();
    }

    private function getDataReader(): ExpressionNode
    {
        return $this->arguments->items[0]->value;
    }

    private function getItemView(): ExpressionNode
    {
        return $this->arguments->items[1]->value;
    }
}