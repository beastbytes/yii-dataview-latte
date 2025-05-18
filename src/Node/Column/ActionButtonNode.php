<?php

declare(strict_types=1);

namespace BeastBytes\Yii\DataView\Latte\Node\Column;

use BeastBytes\Yii\DataView\Latte\Node\ArgumentTrait;
use Generator;
use Latte\Compiler\Nodes\Php\Expression\ArrayNode;
use Latte\Compiler\Nodes\Php\IdentifierNode;
use Latte\Compiler\Nodes\Php\Scalar\StringNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;

class ActionButtonNode extends StatementNode
{
    use ArgumentTrait;

    public IdentifierNode $name;

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
            %node => new Yiisoft\Yii\DataView\Column\%node(%raw), %line
            MASK,
            $this->getButtonName(),
            $this->name,
            $this->parseArguments($context),
            $this->position,
        );
    }

    private function getButtonName(): StringNode
    {
        foreach ($this->arguments->items as $n => $item) {
            if ($item->key->name === 'name') {
                /** @var StringNode $name */
                $name = $item->value;
                unset($this->arguments->items[$n]);
                return $name;
            }
        }

        return array_shift($this->arguments->items)->value;
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