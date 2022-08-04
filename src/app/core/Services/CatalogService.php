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

//        print('All products: <br>' . print_r($products, true) . '<br>');

        $services = $this->repo->fetchRelatedServices($products[0]->getType());

        $cart_hist = [];
        $cart_hist[] = (new CartClass())->add($products[0]);
        $cart_hist[] = (clone end($cart_hist))->add($products[2]);
        $cart_hist[] = (clone end($cart_hist))->add($products[3]);
        $cart_hist[] = (clone end($cart_hist))->remove($products[0]);

//        print('Related services: <br>' . print_r($services, true) . '<br>');
        return new HTMLResponse(['100 OK'], $this->view->render('catalog', [
            'products' => $products,
            'services' => $services,
            'cart_hist' => $cart_hist,
        ]));
    }
}