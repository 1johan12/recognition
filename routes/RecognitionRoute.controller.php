<?php

class RecognitionRoute {
    public function index() {
        include_once __DIR__ . '/../src/views/recognition.view.php';
    }

    public function show($id) {
        echo "Detalles del reconocimiento con ID: $id";
    }
}