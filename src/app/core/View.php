<?php

namespace App\Core;

class View
{
    /**
     * Method to render a view.
     * @param string $name - name of the view file.
     * @param array $data - data to be passed to the view.
     * @return string
     */
    final public function render(string $name, mixed $data): string
    {
        ob_start();

        require_once '../app/views/components/header.php';
        require_once '../app/views/' . $name . '.php';
        require_once '../app/views/components/footer.php';

        return ob_get_clean();
    }
}