<?php

namespace App\core\Services;

use App\core\Database\ICatalogRepository;
use App\Core\IView;
use App\core\Responses\HTMLResponse;

class CatalogService
{
    public function __construct(private ICatalogRepository $repo, private IView $view) {}

    public function run()
    {
        $products = $this->repo->fetchAll();

        $services = $this->repo->fetchRelatedServices($products[0]->ProductType);

        $cart_hist = [];
        $cart_hist[] = (new CartClass())->add($products[0]);
        $cart_hist[] = (clone end($cart_hist))->add($products[2]);
        $cart_hist[] = (clone end($cart_hist))->add($products[4], 0);
        $cart_hist[] = (clone end($cart_hist))->add($products[5], 0);
        $cart_hist[] = (clone end($cart_hist))->remove(0);

        return new HTMLResponse(['100 OK'], $this->view->render('catalog', [
            'products' => $products,
            'services' => $services,
            'cart_hist' => $cart_hist,
        ]));
    }
}