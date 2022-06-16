<?php

namespace App\Core;

class View
{
    /**
     * Method to render a view.
     * @param string $name - name of the view file.
     * @param array $data - data to be passed to the view.
     * @return void
     */
    final public function render(string $name, array $data = []): void
    {
        require_once '../app/views/' . $name . '.php';
    }
}