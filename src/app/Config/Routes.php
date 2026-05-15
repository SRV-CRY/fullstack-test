<?php

namespace App\Config;

// Создаем экземпляр маршрутизатора
$routes = \CodeIgniter\Config\Services::routes();

// Стандартные маршруты
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Comments');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

// Маршруты для комментариев
$routes->get('/', 'Comments::index');
$routes->get('comments', 'Comments::index');
$routes->post('comments', 'Comments::store');
$routes->delete('comments/(:num)', 'Comments::delete/$1');

// Если файл системных маршрутов существует, подключаем его
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}
