<?php

class EventRoute {
    public function index() {
        include_once __DIR__ . '/../src/views/event.view.php';
    }

    public function show($id): void {
        echo "Detalles del evento con ID: $id";
    }
}