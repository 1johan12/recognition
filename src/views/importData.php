<body>
    <h1>Procesamiento de datos</h1>
    <input type="file" id="excelFile" accept=".xls,.xlsx"> <br><br>
    <!-- <button onclick="mostrarDatos()">Mostrar Datos</button> -->
    <select name="" id="event" onchange="fetchEventEdition(this.value)">
        <option value="">Selecciona un Evento</option>
    </select>
    <select name="" id="eventEdition" onchange="mostrarDatos(this.value)">
        <option value="">Selecciona una Edicion</option>
    </select>
    <pre id="output"></pre>

    <script>
        const eventTitleSelect = document.getElementById("event");
        const eventEditionSelect = document.getElementById("eventEdition");

        let hojasDatos = {};
        let category = [];
        let categoryUrl = "src/controller/category.controller.php";
        let eventTitleUrl = "src/controller/eventTitle.controller.php";
        let eventEditionUrl = "src/controller/eventEdition.controller.php";
        let recognitionUrl = "src/controller/recognition.controller.php";
        cargarCategorias();
        fetchEventTitle();
        // fetchEventTitle();

        function test() {
            // console.log("Funcion test");
            // eventEditionSelect.options.length = 0;

            // eventEditionSelect.innerHTML = '';
            // createOptSelect(eventEditionSelect, '', 'Selecciona una Edicion')
        }

        function cargarCategorias() {
            $.ajax({
                url: categoryUrl,
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

        function fetchEventTitle() {
            $.ajax({
                url: eventTitleUrl,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    console.log("data");
                    console.log(data);
                    // ev.innerHTML = '';
                    data.forEach(element => {
                        createOptSelect(eventTitleSelect, element.id, element.title);
                        // const option = document.createElement('option');
                        // option.value = `${element.id}`;
                        // option.textContent = element.title;
                        // eventTitleSelect.appendChild(option);
                    });

                },
                error: function(xhr, status, error) {
                    console.error("Error al obtener Event Title:", error);
                    console.error("Error al obtener Event Title:", xhr.responseText);
                }
            });
        }

        function createOptSelect(select, value, content) {
            const option = document.createElement('option');
            option.value = `${value}`;
            option.textContent = content;
            select.appendChild(option);
        }

        function fetchEventEdition(eventTitleId) {
            $.ajax({
                url: eventEditionUrl + "?event_title_id=" + eventTitleId,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    eventEditionSelect.options.length = 1;
                    data.forEach(element => {
                        createOptSelect(eventEditionSelect, element.id, element.edition);
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error al obtener event editions:", error);
                }
            });
        }

        function integerToRoman(num) {
            const romanValues = {
                M: 1000,
                CM: 900,
                D: 500,
                CD: 400,
                C: 100,
                XC: 90,
                L: 50,
                XL: 40,
                X: 10,
                IX: 9,
                V: 5,
                IV: 4,
                I: 1
            };
            let roman = '';
            for (let key in romanValues) {
                while (num >= romanValues[key]) {
                    roman += key;
                    num -= romanValues[key];
                }
            }
            return roman;
        }

        // Driver code
        console.log(integerToRoman(9));
        console.log(integerToRoman(81));


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
            console.log("datos",hojasDatos);
            
        });


        function mostrarDatos(eventEditionId) {
            // console.log("RecognitionModel ", RecognitionModel);
            console.log(hojasDatos);


            for (const hoja in hojasDatos) {
                category.forEach(element => {
                    if (hoja === element.name) {
                        console.log("hola", element.name, element.id);
                        hojasDatos[hoja].forEach(item => {
                            item.category_id = element.id;
                            item.event_edition_id = eventEditionId;
                            if (element.name === "JUECES") item.nombre = item.nombredejuez;
                            if (element.name === "COLEGIOS") item.nombre = item.colegio;

                            const recognitionElement = new RecognitionModel(item);
                            console.log(recognitionElement);
                            
                            $.ajax({
                                url: recognitionUrl,
                                type: "POST",
                                data: JSON.stringify({
                                    recognitions: recognitionElement
                                }),
                                contentType: "application/json",
                                dataType: "json",
                                success: function(response) {
                                    console.log("Registros insertados:", response);
                                },
                                error: function(xhr, status, error) {
                                    console.error("Error al enviar datos:", error);
                                }
                            });
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