<link rel="stylesheet" href="assets/css/recognition.css">
<div class="ue_container_module">
    <div class="ue_recognition_header mb-5">
        <div class="ue_event_title ">
            <h1 class="text-red-600">Reconocimientos</h1>
        </div>
        <div class="ue_container-btn">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importData">Importar Datos</button>
        </div>
    </div>
    <table class="table" id="recognition_table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Evento</th>
                <th scope="col">Edicion</th>
                <th scope="col">Nombre</th>
                <th scope="col">Categoria</th>
                <th scope="col">Email</th>
                <th scope="col">Codigo</th>
                <th scope="col">Fecha</th>
                <th scope="col">Tipo</th>
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
</div>

<!-- Modal -->
<div class="modal fade" id="importData" tabindex="-1" aria-labelledby="importDataLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="importDataLabel">Importar Datos</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="ue_import_data">
                    <div class="d-flex flex-grow-1 flex-col position-relative pb-4">
                        <select class="ue_select" name="" id="event" onchange="fnValidateInput(this);">
                            <option value="">Selecciona Evento</option>
                        </select>
                    </div>
                    <div class="d-flex flex-col position-relative pb-4">
                        <select class="ue_select " name="" id="eventEdition" onchange="fnValidateInput(this);">
                            <option value=""> Edicion</option>
                        </select>
                    </div>
                </div>
                <div class="d-flex flex-col position-relative pb-4">
                    <input class="ue_select" type="file" id="excelFile" onchange="fnValidateInput(this)" accept=".xls,.xlsx"> <br><br>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="fnRegisterData()">Registrar Datos</button>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/recognition.js"></script>