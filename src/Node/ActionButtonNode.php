<?php

declare(strict_types=1);

namespace BeastBytes\Yii\DataView\Latte\Node;

use Generator;
use Latte\Compiler\Nodes\Php\Expression\ArrayNode;
use Latte\Compiler\Nodes\Php\IdentifierNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;

final class ActionButtonNode extends StatementNode
{
    private ArrayNode $arguments;

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
            echo "\n";
            echo "new Yiisoft\Yii\DataView\Column\ActionButton(%raw)," %line;
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