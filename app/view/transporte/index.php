<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <h2 class="mb-4 text-center">Gestión del Transporte Universitario</h2>

    <div class="row">
        <!-- Tarjetas resumen -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Buses Registrados</h5>
                    <h3 class="text-primary"><?= $totalBuses ?? 0 ?></h3>
                    <a href="index.php?controller=Bus&action=index" class="btn btn-outline-primary btn-sm mt-2">Ver Buses</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-
