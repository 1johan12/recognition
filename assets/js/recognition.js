const util = new Utils();
console.log("Recognition.js");
const eventTitleSelect = document.getElementById("filterEvent");
const filterEventTitleSelect = document.getElementById("event");
const eventEditionSelect = document.getElementById("eventEdition");
const filterEventEditionSelect = document.getElementById("filterEventEdition");
const filterCategorySelect = document.getElementById("filterCategory");
// const excelFile = document.getElementById("excelFile");
const tableBody = document.querySelector("#recognition_table tbody");
const modal = document.getElementById("importData");
let hojasDatos = {};
let category = [];
let inputId = [];
let categoryUrl = "src/controller/category.controller.php";
let eventTitleUrl = "src/controller/eventTitle.controller.php";
let eventEditionUrl = "src/controller/eventEdition.controller.php";
let recognitionUrl = "src/controller/recognition.controller.php";
let globalEventId = 0;

let inputIdCase = "recognition";

fetchCategory();
fetchEventTitle();
fetchRecognition();
function fetchRecognition(
  page = 1,
  perPage = 10,
  filterByEventId = -1,
  filterByEditionId = -1,
  filterByName = null
) {
  filterByEventId = document.getElementById("filterEvent").value;
  filterByEditionId = document.getElementById("filterEventEdition").value;
  filterByCategoryId = document.getElementById("filterCategory").value;
  if (filterByEventId === -1) filterByEditionId = -1;

  $.ajax({
    url: recognitionUrl,
    type: "GET",
    data: {
      page: page,
      perPage: perPage,
      filterByName: filterByName,
      filterByEventId: filterByEventId,
      filterByEditionId: filterByEditionId,
      filterByCategoryId: filterByCategoryId,
    },
    dataType: "json",
    success: function (data) {      
      tableBody.innerHTML = "";
      data.recognition.forEach((item, index) => {
        const row = `
                <tr>
                    <td scope="row">${index + 1}</td>
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

function fetchCategory() {
  $.ajax({
    url: categoryUrl,
    type: "GET",
    dataType: "json",
    data: { status : 1},

    success: function (data) {
      category = data;
      console.log("fetchCategory", data);

      filterCategorySelect.options.length = 1;
      data.forEach((element) => {
        createOptSelect(filterCategorySelect, element.id, element.name);
      });
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
      data.forEach((element) => {
        createOptSelect(eventTitleSelect, element.id, element.title);
        createOptSelect(filterEventTitleSelect, element.id, element.title);
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

function fetchEventEdition(eventTitleId, selectType = 1) {
  $.ajax({
    url: eventEditionUrl + "?event_title_id=" + eventTitleId,
    type: "GET",
    dataType: "json",
    success: function (data) {
      if (selectType === 1) {
        eventEditionSelect.options.length = 1;
        data.forEach((element) => {
          createOptSelect(eventEditionSelect, element.id, element.edition);
        });
      } else {
        filterEventEditionSelect.options.length = 1;
        data.forEach((element) => {
          createOptSelect(
            filterEventEditionSelect,
            element.id,
            element.edition
          );
        });
      }
    },
    error: function (xhr, status, error) {
      console.error("Error al obtener event editions:", error);
    },
  });
}

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
function fnUploadDataToBD() {
  console.log("fnUploadDataToBD");
  
  let maxLength = Object.values(hojasDatos).reduce((total, array) => {
    return total + (Array.isArray(array) ? array.length : 0);
  }, 0);

  let bulkData = [];

  function updateProgressBar(progress) {
    let progressBar = document.getElementById("progressBar");
    progressBar.style.width = `${progress}%`;
    progressBar.textContent = `${Math.round(progress)}%`;
    progressBar.setAttribute("aria-valuenow", progress);
  }

  let progress = 0;
  const progressIncrement = 50 / maxLength;

  for (const hoja in hojasDatos) {
    category.forEach((element) => {
      if (hoja === element.name) {
        console.log("Procesando hoja:", element.name, element.id);
        hojasDatos[hoja].forEach((item) => {
          item.category_id = element.id;
          item.event_edition_id = eventEditionSelect.value;
          if (element.name === "JUECES") item.nombre = item.nombredejuez;
          if (element.name === "COLEGIOS") item.nombre = item.colegio;

          // console.log(item);
          bulkData.push(new RecognitionProcessModel(item));

          progress += progressIncrement;
          updateProgressBar(progress);
        });
      }
    });
  }
  // return console.log("bulkData", bulkData);

  $.ajax({
    url: recognitionUrl,
    type: "POST",
    data: JSON.stringify({
      recognitions: bulkData,
    }),
    contentType: "application/json",
    dataType: "json",
    success: function (response) {
      console.log("Datos insertados correctamente:", response);

      updateProgressBar(100);

      $("#importData").modal("hide");
      fetchRecognition(1, 10);
      util.fnCleanInputs(inputIdCase);
      document.getElementById("ue_btn_container").classList.remove("d-none");
      document.getElementById("progressBarContainer").classList.add("d-none");
    },
    error: function (xhr, status, error) {
      console.error("Error al enviar datos:", error);
    },
  });
}

function normalizeText(text) {
  return text
    .normalize("NFD")
    .replace(/[\u0300-\u036f]/g, "")
    .replace(/[^a-zA-Z0-9]/g, "")
    .toLowerCase();
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

function fnValidateInput(input) {
  let condition;
  let spanId = `${input.id}-error`;
  let idInputMessage = input.id;
  const existingSpan = util.fnCreateSpanError(input, spanId, idInputMessage);
  let elementId = input.id;
  switch (elementId) {
    case "event":
      condition = input.value.length >= 1;
      util.fnShowInputError(condition, existingSpan, input, inputIdCase);
      break;
    case "excelFile":
      condition = input.value.length >= 1;
      util.fnShowInputError(condition, existingSpan, input, inputIdCase);
      fnUploadDataExcel(input);
      break;
    case "eventEdition":
      condition = input.value.length >= 1;
      util.fnShowInputError(condition, existingSpan, input, inputIdCase);
      break;
    default:
      condition = input.value.length >= 1;
      util.fnShowInputError(condition, existingSpan, input, inputIdCase);
      break;
  }
}

function fnRegisterData() {
  console.log("fnRegisterData",util.inputIdsByContext[inputIdCase][0],inputId);
  
  inputFn = ["event", "eventEdition", "excelFile"];

  let editionValue = eventEditionSelect.value;
  inputFn.forEach((element) => {
    const input = document.getElementById(element);
    fnValidateInput(input);
    if (element === eventEditionSelect.id)
      eventEditionSelect.value = editionValue;
  });
  if (inputFn.length === util.inputIdsByContext[inputIdCase].length) {
    document.getElementById("ue_btn_container").classList.add("d-none");
    document.getElementById("progressBarContainer").classList.remove("d-none");
    fnUploadDataToBD();
  }
}
