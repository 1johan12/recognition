<!-- <link rel="stylesheet" href="assets/css/recognition.css"> -->
<div class="ue_container_module mb-5">
    <div class="ue_recognition_header d-flex justify-content-between align-items-center mb-2">
        <div class="ue_event_title ">
            <h1 class="text-red-600">Eventos</h1>
        </div>
        <div class="d-flex w-auto h-100">
            <button type="button" class="btn btn-success w-auto h-auto" data-bs-toggle="modal" data-bs-target="#addEvent">Agregar Evento</button>
        </div>
    </div>
    <table class="table" id="event_table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nombre</th>
                <th scope="col">Creado el</th>
                <th scope="col">Opcion</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>No-data</td>
            </tr>
        </tbody>
    </table>
    <nav>
        <ul class="pagination justify-content-end" id="pagination"></ul>
    </nav>
    <!-- </div>
<div class="ue_container_module"> -->
</div>

<div class="modal fade" id="addEvent" tabindex="-1" aria-labelledby="importDataLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="importDataLabel">Añadir Evento</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex gap-2">
                    <div class="d-flex flex-col position-relative w-100 ">
                        <input class="form-control w-100" type="text" name="name" id="name"
                            onkeyup="fnValidateInput(this);" placeholder="nombre">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div id="ue_btn_container">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="addEventTitle()">Registrar</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="evenDetailModal" tabindex="-1" aria-labelledby="importDataLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="w-100 d-flex justify-content-between align-items-center">
                    <h1 class="modal-title fs-5" id="detailTitleEvent">Detalle Evento</h1>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addEventEdition" onclick="modalUp()">Generate Edition</button>
                </div>
                <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
            </div>
            <div class="modal-body">
                <table class="table" id="eventEdition_table">
                    <thead>
                        <tr>
                            <!-- <th scope="col">#</th> -->
                            <!-- <th scope="col">Evento</th> -->
                            <th class="text-center" scope="col">Edicion</th>
                            <th scope="col">Fecha Inicio</th>
                            <th scope="col">Fecha Fin</th>
                            <th scope="col">Creado el</th>
                            <th scope="col">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>No-data</td>
                        </tr>
                    </tbody>
                </table>
                <nav>
                    <ul class="pagination justify-content-end" id="eventEditionPagination"></ul>
                </nav>
            </div>
            <div class="modal-footer">
                <div id="ue_btn_container">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <!-- <button type="button" class="btn btn-primary" onclick="addEventTitle()">Registrar</button> -->
                </div>
            </div>
        </div>
    </div>
</div>




<div class="modal fade" id="addEventEdition" tabindex="-1" aria-labelledby="importDataLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" >
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="importDataLabel">Añadir Edicion</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-column">
                    <!-- <div class="d-flex flex-col position-relative w-100 pb-4">
                        <select class="form-select" name="event" id="eventSelect" onchange="fnValidateInput(this)">
                            <option value="">Selecciona Evento</option>
                        </select>
                    </div> -->
                    <div class="d-flex flex-col position-relative align-items-center w-100 pb-4 ">
                        <label for="" class="w-50 ">Fecha Inicio:</label>
                        <input class="form-control w-100" type="date" name="start_date" id="start_date"
                            onchange="fnValidateInput(this)" placeholder="Fecha de inicio">
                    </div>
                    <div class="d-flex flex-col position-relative align-items-center w-100 pb-4 ">
                        <label for="" class="w-50 ">Fecha Fin:</label>
                        <input class="form-control w-100" type="date" name="end_date" id="end_date"
                            onchange="fnValidateInput(this)" placeholder="Fecha de inicio">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div id="ue_btn_container">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="fnAddEventEdition()">Generar Edicion</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="updateEvent" tabindex="-1" aria-labelledby="importDataLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" >
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="importDataLabel">Modificar Evento</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-column">
                    <!-- <div class="d-flex flex-col position-relative w-100 pb-4">
                        <select class="form-select" name="event" id="eventSelect" onchange="fnValidateInput(this)">
                            <option value="">Selecciona Evento</option>
                        </select>
                    </div> -->
                    <div class="d-flex flex-col position-relative pb-4 ">
                        <input class="form-control w-100" type="text" name="name" id="updateEventName"
                            onchange="fnValidateInput(this)" placeholder="nombre">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div id="ue_btn_container">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="fnAddEventEdition()">Generar Edicion</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="assets/js/event.js"></script>
