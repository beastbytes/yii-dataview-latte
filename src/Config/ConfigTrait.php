<?php

declare(strict_types=1);

namespace BeastBytes\Yii\DataView\Latte\Config;

use Latte\Compiler\Nodes\Php\FilterNode;
use Latte\Compiler\Nodes\Php\ModifierNode;

trait ConfigTrait
{
    protected ?ModifierNode $config = null;

    private function getConfig($context): string
    {
        $configuration = [];

        /** @var FilterNode $config */
        foreach ($this->config as $config) {
            $args = [];
            $name = $config->name->name;

            foreach ($config->args as $arg) {
                $args[] = $arg->print($context);
            }

            $configuration[] = "'" . $name . "()'=>[" . join(',', $args) . ']';
        }

        return '[' . join(',', $configuration) . ']';
    }
}