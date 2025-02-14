<?php

class CategoryRoute {
    public function index() {
        include_once __DIR__ . '/../src/views/category.view.php';
        // echo __DIR__ . 'Listado de categorías';
    }

    public function show($id) {
        echo "Detalles de la categoría con ID: $id";
    }
}