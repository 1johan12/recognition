<body>
    <h1>Procesamiento de datos</h1>
    <input type="file" id="excelFile" accept=".xls,.xlsx"> <br><br>
    <button onclick="mostrarDatos()">Mostrar Datos</button>

    <pre id="output"></pre>

    <script>
        let hojasDatos = {};
        let category = [];
        let controllerPath = "src/controller/category.controller.php";

        cargarCategorias();

        function cargarCategorias() {
            $.ajax({
                url: controllerPath,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    category = data;
                    console.log(category);

                },
                error: function() {
                    console.log("Error al obtener categorías");
                }
            });
        }


        document.getElementById('excelFile').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                const data = new Uint8Array(e.target.result);
                const workbook = XLSX.read(data, {
                    type: 'array'
                });

                hojasDatos = {};

                workbook.SheetNames.forEach(sheetName => {
                    const sheet = workbook.Sheets[sheetName];
                    const jsonData = XLSX.utils.sheet_to_json(sheet, {
                        header: 1
                    });

                    if (jsonData.length === 0) return;

                    const headers = jsonData[0].map(header => normalizeText(header));

                    hojasDatos[sheetName] = jsonData.slice(1)
                        .map(row => {
                            let obj = {};
                            headers.forEach((key, index) => {
                                obj[key] = row[index] || "";
                            });
                            return obj;
                        })
                        .filter(row => Object.values(row).some(value => value !== ""));
                });

                // console.log(hojasDatos);
                document.getElementById('output').textContent = JSON.stringify(hojasDatos, null, 2);
            };
            reader.readAsArrayBuffer(file);
        });


        function mostrarDatos() {
            console.log("RecognitionModel ", RecognitionModel);

            for (const hoja in hojasDatos) {
                category.forEach(element => {
                    if (hoja === element.name) {
                        console.log("hola", element.name, element.id);
                        hojasDatos[hoja].forEach(item => {
                            item.category_id =element.id;
                            // console.log(item);;
                            
                            const user = new RecognitionModel(item);
                            console.log(user);
                        });
                    }
                });

            }

        }

        function normalizeText(text) {
            return text
                .normalize("NFD").replace(/[\u0300-\u036f]/g, "")
                .replace(/[^a-zA-Z0-9]/g, "")
                .toLowerCase();
        }




        function addCategory() {
            $.ajax({
                url: controllerPath,
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify({
                    name: "Cateter"
                }),
                success: function(response) {
                    if (response.success) {
                        console.log(response);

                        cargarCategorias();

                    } else {
                        alert("Error al agregar categoría");
                    }
                }
            });
        }
    </script>
</body>

</html>