console.log("Recognition.js");
const eventTitleSelect = document.getElementById("event");
const eventEditionSelect = document.getElementById("eventEdition");
// const excelFile = document.getElementById("excelFile");
const tableBody = document.querySelector("#recognition_table tbody");

let hojasDatos = {};
let category = [];
let inputId = [];
let categoryUrl = "src/controller/category.controller.php";
let eventTitleUrl = "src/controller/eventTitle.controller.php";
let eventEditionUrl = "src/controller/eventEdition.controller.php";
let recognitionUrl = "src/controller/recognition.controller.php";
cargarCategorias();
fetchEventTitle();
fetchRecognition(1, 10);
function fetchRecognition(page, perPage) {
  console.log("Recognition", page, perPage);
  filterByName = null;
  filterByEventId = null;
  $.ajax({
    url: recognitionUrl,
    type: "GET",
    data: {
      page: page,
      perPage: perPage,
      filterByName: filterByName,
      filterByEventId: filterByEventId,
    },
    dataType: "json",
    success: function (data) {
      console.log("Fecht Recognition", data);

      tableBody.innerHTML = "";
      data.recognition.forEach((item, index) => {
        const row = `
                <tr>
                    <td scope="row">${index + 1}</td>
                    <td>${item.title}</td>
                    <td>${item.edition}</td>
                    <td>${item.fullname}</td>
                    <td>${item.category}</td>
                    <td>${item.mail ?? "-"}</td>
                    <td>${item.access_code}</td>
                    <td>${item.created_at}</td>
                    <td>${item.participant_type ?? "-"}</td>
                </tr>
            `;
        tableBody.insertAdjacentHTML("beforeend", row);
      });

      renderPagination(data.total, perPage, page);
    },
    error: function () {
      console.log("Error al obtener Reconocimiento");
    },
  });
}

function cargarCategorias() {
  $.ajax({
    url: categoryUrl,
    type: "GET",
    dataType: "json",
    success: function (data) {
      category = data;
      // console.log(category);
    },
    error: function () {
      console.log("Error al obtener categorías");
    },
  });
}

function fetchEventTitle() {
  $.ajax({
    url: eventTitleUrl,
    type: "GET",
    dataType: "json",
    success: function (data) {
      // console.log("data");
      // console.log(data);
      // ev.innerHTML = '';
      data.forEach((element) => {
        createOptSelect(eventTitleSelect, element.id, element.title);
      });
    },
    error: function (xhr, status, error) {
      console.error("Error al obtener Event Title:", error);
      console.error("Error al obtener Event Title:", xhr.responseText);
    },
  });
}

function createOptSelect(select, value, content) {
  const option = document.createElement("option");
  option.value = `${value}`;
  option.textContent = content;
  select.appendChild(option);
}

function fetchEventEdition(eventTitleId) {
  $.ajax({
    url: eventEditionUrl + "?event_title_id=" + eventTitleId,
    type: "GET",
    dataType: "json",
    success: function (data) {
      eventEditionSelect.options.length = 1;
      data.forEach((element) => {
        createOptSelect(eventEditionSelect, element.id, element.edition);
      });
    },
    error: function (xhr, status, error) {
      console.error("Error al obtener event editions:", error);
    },
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
    I: 1,
  };
  let roman = "";
  for (let key in romanValues) {
    while (num >= romanValues[key]) {
      roman += key;
      num -= romanValues[key];
    }
  }
  return roman;
}

// console.log(integerToRoman(9));
// console.log(integerToRoman(81));

function fnUploadDataExcel(input) {
  const file = input.files[0];
  if (!file) return;

  const reader = new FileReader();
  reader.onload = function (e) {
    const data = new Uint8Array(e.target.result);
    const workbook = XLSX.read(data, {
      type: "array",
    });

    hojasDatos = {};

    workbook.SheetNames.forEach((sheetName) => {
      const sheet = workbook.Sheets[sheetName];
      const jsonData = XLSX.utils.sheet_to_json(sheet, {
        header: 1,
      });

      if (jsonData.length === 0) return;

      const headers = jsonData[0].map((header) => normalizeText(header));

      hojasDatos[sheetName] = jsonData
        .slice(1)
        .map((row) => {
          let obj = {};
          headers.forEach((key, index) => {
            obj[key] = row[index] || "";
          });
          return obj;
        })
        .filter((row) => Object.values(row).some((value) => value !== ""));
    });
  };
  reader.readAsArrayBuffer(file);
  console.log("datos", hojasDatos);
}

function mostrarDatos(eventEditionId) {
  // console.log(hojasDatos);
  // mostrarDatos(this.value)
  // for (const hoja in hojasDatos) {
  //   category.forEach((element) => {
  //     if (hoja === element.name) {
  //       console.log("hola", element.name, element.id);
  //       hojasDatos[hoja].forEach((item) => {
  //         item.category_id = element.id;
  //         item.event_edition_id = eventEditionId;
  //         if (element.name === "JUECES") item.nombre = item.nombredejuez;
  //         if (element.name === "COLEGIOS") item.nombre = item.colegio;
  //         const recognitionElement = new RecognitionProcessModel(item);
  //         console.log(recognitionElement);
  //         $.ajax({
  //           url: recognitionUrl,
  //           type: "POST",
  //           data: JSON.stringify({
  //             recognitions: recognitionElement,
  //           }),
  //           contentType: "application/json",
  //           dataType: "json",
  //           success: function (response) {
  //             console.log("Registros insertados:", response);
  //           },
  //           error: function (xhr, status, error) {
  //             console.error("Error al enviar datos:", error);
  //           },
  //         });
  //       });
  //     }
  //   });
  // }
}

function normalizeText(text) {
  return text
    .normalize("NFD")
    .replace(/[\u0300-\u036f]/g, "")
    .replace(/[^a-zA-Z0-9]/g, "")
    .toLowerCase();
}

function addCategory() {
  $.ajax({
    url: controllerPath,
    type: "POST",
    contentType: "application/json",
    data: JSON.stringify({
      name: "Cateter",
    }),
    success: function (response) {
      if (response.success) {
        console.log(response);

        cargarCategorias();
      } else {
        alert("Error al agregar categoría");
      }
    },
  });
}

function renderPagination(total, perPage, currentPage) {
  const totalPaginas = Math.ceil(total / perPage);
  const pagination = $("#pagination");
  document.getElementById("pagination").innerHTML = "";

  const maxVisible = 3;
  let startPage = Math.max(1, currentPage - Math.floor(maxVisible / 2));
  let endPage = Math.min(totalPaginas, startPage + maxVisible - 1);

  if (endPage - startPage + 1 < maxVisible) {
    startPage = Math.max(1, endPage - maxVisible + 1);
  }

  if (currentPage > 1) {
    pagination.append(`
          <li class="page-item">
              <a class="page-link" href="#" onclick="fetchRecognition(${
                currentPage - 1
              }, ${perPage})">«</a>
          </li>
      `);
  }

  if (startPage > 1) {
    pagination.append(`
          <li class="page-item">
              <a class="page-link" href="#" onclick="fetchRecognition(1, ${perPage})">1</a>
          </li>
          <li class="page-item disabled"><span class="page-link">...</span></li>
      `);
  }

  for (let i = startPage; i <= endPage; i++) {
    pagination.append(`
          <li class="page-item ${i === currentPage ? "active" : ""}">
              <a class="page-link" href="#" onclick="fetchRecognition(${i}, ${perPage})">${i}</a>
          </li>
      `);
  }

  if (endPage < totalPaginas) {
    pagination.append(`
          <li class="page-item disabled"><span class="page-link">...</span></li>
          <li class="page-item">
              <a class="page-link" href="#" onclick="fetchRecognition(${totalPaginas}, ${perPage})">${totalPaginas}</a>
          </li>
      `);
  }

  if (currentPage < totalPaginas) {
    pagination.append(`
          <li class="page-item">
              <a class="page-link" href="#" onclick="fetchRecognition(${
                currentPage + 1
              }, ${perPage})">»</a>
          </li>
      `);
  }
}

function fnShowInputError(condition, existingSpan, input) {
  if (condition) {
    existingSpan.classList.add("d-none");
    input.classList.remove("ue_input_border_red", "ue_select_warning");
    input.classList.add("ue_input_border_black");
    if (!inputId.includes(input.id)) inputId.push(input.id);
  } else {
    existingSpan.classList.remove("d-none");
    input.classList.remove("ue_input_border_black");
    input.classList.add("ue_input_border_red", "ue_select_warning");
    inputId = inputId.filter((item) => item !== input.id);
  }
}

function fnCreateSpanError(input, spanId, elementId) {
  let existingSpan = document.getElementById(spanId);
  if (!existingSpan) {
    const newSpan = document.createElement("span");
    newSpan.classList.add("ue_span_error");
    newSpan.id = spanId;
    newSpan.textContent = getMessage(elementId);
    input.parentNode.insertBefore(newSpan, input.nextSibling);
    existingSpan = newSpan;
  }
  return existingSpan;
}

function fnValidateInput(input) {
  let condition;
  let spanId = `${input.id}-error`;
  let idInputMessage = input.id;
  const existingSpan = fnCreateSpanError(input, spanId, idInputMessage);
  let elementId = input.id;
  switch (elementId) {
    case "event":
      condition = input.value.length >= 1;
      console.log("event", input.value, condition);
      fnShowInputError(condition, existingSpan, input);
      fetchEventEdition(input.value);
      break;
    case "excelFile":
      condition = input.value.length >= 1;
      fnShowInputError(condition, existingSpan, input);
      fnUploadDataExcel(input);
      break;
    default:
      condition = input.value.length >= 1;
      fnShowInputError(condition, existingSpan, input);
      break;
  }
}

function getMessage(inputIdMessage) {
  const messages = {
    event: "Evento obligatorio*",
    eventEdition: "obligatorio*",
    excelFile: "Selecciona un archivo excel*",
  };
  return messages[inputIdMessage] || "Error no especificado.";
}

function fnRegisterData() {
  inputFn = ["event", "eventEdition", "excelFile"];
  inputFn.forEach((element) => {
    const input = document.getElementById(element);
    fnValidateInput(input);
  });
  if(inputFn === inputId){
    console.log(true);
    
  }
  
}
