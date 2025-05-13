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

class ListViewNode extends StatementNode
{
    public ?ModifierNode $config = null;
    public string $configuration = '';
    public ExpressionNode $dataReader;
    public ExpressionNode $itemView;
    private IdentifierNode $name;

    public static function create(Tag $tag): self
    {
        $tag->expectArguments();
        $node = $tag->node = new self;

        $node->name = new IdentifierNode(ucfirst($tag->name));

        $node->dataReader = $tag->parser->parseExpression();
        $node->itemView = $tag->parser->parseExpression();
        $node->config = $tag->parser->parseModifier();

        return $node;
    }

    public function print(PrintContext $context): string
    {
        $this->parseConfiguration($context);

        return $context->format(
            <<<'MASK'
            echo 'Yiisoft\Yii\DataView\%node::widget()' %line;
            echo "\n";
            echo '    ->dataReader(%node)';
            echo "\n";
            echo '    ->itemView(%node)';
            echo "\n";
            echo '%raw';
            echo ';';
            MASK,
            $this->name,
            $this->position,
            $this->dataReader,
            $this->itemView,
            $this->configuration,
        );
    }

    private function parseConfiguration($context): void
    {
        $configuration = [];

        /** @var FilterNode $config */
        foreach ($this->config as $config) {
            $name = '';
            $atr = [];

            foreach ($config as $c) {
                if ($c instanceof IdentifierNode) {
                    $name = (string) $c;
                } else {
                    $atr[] = $c;
                }
            }

            if (empty($atr)) {
                $configuration[$name] = '';
            } else {
                foreach ($atr as $a) {
                    $configuration[$name] = $a instanceof Node ? $a->print($context) : '';
                }
            }
        }

        foreach ($configuration as $modifier => $value) {
            $this->configuration .= "->$modifier($value)";
        }
    }

    /**
     * @inheritDoc
     */
    public function &getIterator(): \Generator
    {
        yield $this->name;
        yield $this->dataReader;;
        yield $this->itemView;
    }
}