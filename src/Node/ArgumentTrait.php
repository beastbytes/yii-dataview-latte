<?php

declare(strict_types=1);

namespace BeastBytes\Yii\DataView\Latte\Node;

use Latte\Compiler\Nodes\Php\Expression\ArrayNode;
use Latte\Compiler\Nodes\Php\IdentifierNode;
use Latte\Compiler\PrintContext;

trait ArgumentTrait
{
    public ArrayNode $arguments;

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