<?php

declare(strict_types=1);

function view(string $template, array $data = []): void
{
    $basePath = dirname(__DIR__);

    $smarty = new Smarty();
    $smarty->setTemplateDir($basePath . '/templates');
    $smarty->setCompileDir($basePath . '/var/templates_c');
    $smarty->setCacheDir($basePath . '/var/cache');

    foreach ($data as $name => $value) {
        $smarty->assign($name, $value);
    }

    $smarty->display($template);
}
