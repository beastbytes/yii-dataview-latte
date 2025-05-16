<?php

declare(strict_types=1);

namespace BeastBytes\Yii\DataView\Latte\Node;

use Latte\Compiler\Node;
use Latte\Compiler\Nodes\Php\FilterNode;
use Latte\Compiler\Nodes\Php\IdentifierNode;
use Latte\Compiler\Nodes\Php\ModifierNode;

trait ConfigurationTrait
{
    public ?ModifierNode $configuration = null;

    private function parseConfiguration($context): string
    {
        $configuration = [];
        $output = '';

        /** @var FilterNode $config */
        foreach ($this->configuration as $filterNode) {
            $name = '';
            $atr = [];

            foreach ($filterNode as $node) {
                if ($node instanceof IdentifierNode) {
                    $name = (string) $node;
                } else {
                    $atr[] = $node;
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
            $output .= "->$modifier($value)";
        }

        return $output;
    }
}