<?php

declare(strict_types=1);

namespace BeastBytes\Yii\DataView\Latte\Node\Column;

use BeastBytes\Yii\DataView\Latte\Node\ArgumentTrait;
use Generator;
use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\Php\IdentifierNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;

class ActionColumnNode extends StatementNode
{
    use ArgumentTrait;

    public AreaNode $buttons;
    public IdentifierNode $name;

    public static function create(Tag $tag): Generator
    {
        $node = $tag->node = new self;
        $node->name = new IdentifierNode(ucfirst($tag->name));
        $node->arguments = $tag->parser->parseArguments();

        [$node->buttons, $endTag] = yield;
        return $node;
    }

    public function print(PrintContext $context): string
    {
        return $context->format(
            <<<'MASK'
            
            new Yiisoft\Yii\DataView\GridView\Column\%node( %line
                buttons: [
            %node
                ],
                %raw
            ),
            MASK,
            $this->name,
            $this->position,
            $this->buttons,
            $this->parseArguments($context),
        );
    }

    /**
     * @inheritDoc
     */
    public function &getIterator(): Generator
    {
        yield $this->name;
        yield $this->buttons;
        yield $this->arguments;
    }
}