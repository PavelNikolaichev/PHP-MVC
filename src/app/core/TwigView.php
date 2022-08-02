<?php

namespace App\core;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigView implements IView
{
    private Environment $twig;

    public function __construct()
    {
        $fileLoader = new FilesystemLoader('../app/views');
        $this->twig = new Environment($fileLoader);
    }

    /**
     * Method to render a view.
     *
     * @param string $name - name of the view file.
     * @param array $data - data to be passed to the view.
     *
     * @return string - rendered view.
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    final public function render(string $name, array $data): string
    {
        return $this->getTwig()->render($name . '.twig', $data);
    }

    public function getTwig(): Environment
    {
        return $this->twig;
    }
}