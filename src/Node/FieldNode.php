<?php

declare(strict_types=1);

namespace BeastBytes\Yii\DataView\Latte\Node;

use Latte\Compiler\Node;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\FilterNode;
use Latte\Compiler\Nodes\Php\IdentifierNode;
use Latte\Compiler\Nodes\Php\ModifierNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;

class FieldNode extends StatementNode
{
    private ExpressionNode $field;
    public ?ModifierNode $modifiers = null;
    private ?IdentifierNode $name = null;
    public string $parameters = '';

    public static function create(Tag $tag): self
    {
        $tag->expectArguments();
        $node = $tag->node = new self;
        $node->name = new IdentifierNode(ucfirst($tag->name));

        $node->field = $tag->parser->parseExpression();

        /*
        foreach ($tag->parser->parseArguments() as $argument) {
            $node->field = $argument->value;
        }

        $node->modifiers = $tag->parser->parseModifier();
        */

        return $node;
    }

    public function print(PrintContext $context): string
    {
        //$this->parseParameters($context);

        return $context->format(
            <<<'MASK'
            echo "\n";
            echo "new Yiisoft\Yii\DataView\Field\%node(%node)," %line;
            MASK,
            $this->name,
            $this->field,
        );
    }

    /**
     * @inheritDoc
     */
    public function &getIterator(): \Generator
    {
        yield $this->name;
        yield $this->field;
    }

    private function parseParameters($context): void
    {
        $parameters = [];

        /** @var FilterNode $modifier */
        foreach ($this->modifiers as $modifier) {
            $name = '';
            $atr = [];

            foreach ($modifier as $m) {
                if ($m instanceof IdentifierNode) {
                    $name = (string) $m;
                } else {
                    $atr = $m;
                }
            }

            $parameters[$name] = $atr instanceof Node ? $atr->print($context) : '';
        }

        if (!empty($parameters)) {
            if ($this->name !== null) {
                $this->parameters .= ', ';
            }

            foreach ($parameters as $parameter => $value) {
                $this->parameters .= "$parameter: $value, ";
            }

            $this->parameters = rtrim($this->parameters, ', ');
        }
    }
}