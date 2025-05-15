<?php

declare(strict_types=1);

namespace BeastBytes\Yii\DataView\Latte\Node\Field;

use Generator;
use Latte\Compiler\Node;
use Latte\Compiler\Nodes\Php\Expression\ArrayNode;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\FilterNode;
use Latte\Compiler\Nodes\Php\IdentifierNode;
use Latte\Compiler\Nodes\Php\ModifierNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;

class FieldNode extends StatementNode
{
    private ArrayNode $arguments;
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

    private function parseArguments(PrintContext $context): string
    {
        $arguments = [];

        foreach ($this->arguments as $argument) {
            $key = $argument->key instanceof IdentifierNode
                ? $argument->key->print($context) . ': '
                : ''
            ;
            $arguments[] = $key . $argument->value->print($context);
        }

        return implode(', ', $arguments);
    }
}