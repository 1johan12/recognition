<!-- <link rel="stylesheet" href="assets/css/recognition.css"> -->
<div class="ue_container_module">
    <div class="d-flex mb-5 justify-content-between align-items-center">
        <div class="ue_event_title d-flex gap-5">
            <h1 class="text-red-600">Categoria</h1>
        </div>
        <div class="d-flex gap-2">
            <select class="form-select ue_select w-auto" name="" id="filterCategory"
                onchange="fetchCategory(this.value);">
                <option value="-1">Selecciona estado</option>
                <option value="-1">Todos</option>
                <option value="1">Activo</option>
                <option value="0">Desactivado</option>
            </select>
            <button type="button" class="btn btn-success h-auto w-auto" data-bs-toggle="modal"
                data-bs-target="#addCategoryModal">Agregar</button>
        </div>
    </div>
    <table class="table" id="category_table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nombre</th>
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
</div>



<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="importDataLabel">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="importDataLabel">Agregar Categoria</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-col position-relative">
                    <input class="form-control" type="text" name="name" id="categoryName"
                        onkeyup="fnValidateInput(this)" placeholder="nombre"> <br><br>
                </div>
            </div>
            <div class="modal-footer">
                <div id="ue_btn_container">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="fnRegisterCategory()">Registrar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="updateCategoryModal" tabindex="-1" aria-labelledby="importDataLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="importDataLabel">Actualizar Categoria</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex gap-2">
                    <div class="d-flex flex-col position-relative w-100 pb-4">
                        <input class="form-control w-100" type="text" name="name" id="nameUpdateCategory"
                            onkeyup="fnValidateInput(this)" placeholder="nombre">
                    </div>
                    <div class="d-flex flex-grow-1 flex-col position-relative pb-4 w-100">
                        <select class="form-select w-100" name="status" id="status" onchange="fnValidateInput(this);">
                            <option value="">Selecciona estado</option>
                            <option value="1">Activo</option>
                            <option value="0">Desactivado</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div id="ue_btn_container">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="fnUpdateCategory()">Actualizar</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="assets/js/category.js"></script>