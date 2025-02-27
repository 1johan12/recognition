const util = new Utils();
const context = ["event", "eventEdition"];
let eventData = {};
let eventTitle = [];
let eventEdition = {};
let eventDetail = {};
let eventUrl = "src/controller/eventTitle.controller.php";
let eventEditionUrl = "src/controller/eventEdition.controller.php";
const event_table = document.querySelector("#event_table tbody");
const eventEdition_table = document.querySelector("#eventEdition_table tbody");
const filterEventSelect = document.getElementById("eventSelect");
fetchEvent();
// fetchEventEdition();
function fetchEvent(status = 1) {
  $.ajax({
    url: eventUrl,
    type: "GET",
    data: {
      status: status,
    },
    dataType: "json",
    success: function (data) {
      eventTitle = data;

      event_table.innerHTML = "";
      data.forEach((item, index) => {
        const row = `
                <tr>
                    <td scope="row">${index + 1}</td>
                    <td>${item.title}</td>
                    <td>${item.created_at}</td>
                    <td>
                        <i class="fa-solid fa-trash" onclick='fnDeleteCategory(${
                          item.id
                        })'></i>
                        <i class="fa-solid fa-pen-to-square" data-bs-toggle="modal" data-bs-target="#updateEvent" onclick='fnLoadUpdateData(${JSON.stringify(
                          item
                        )})'></i>
                        <i class="fa-solid fa-eye cursor-pointer" data-bs-toggle="modal" data-bs-target="#evenDetailModal" onclick='
                          fnSetTitle(${JSON.stringify(item.title)});
                          fetchEventEdition(1,${item.id},1,10)'>
                        </i>
                    </td>
                </tr>
            `;
        event_table.insertAdjacentHTML("beforeend", row);
      });
    },
    error: function () {
      console.log("Error al obtener categorías");
    },
  });
}
function fetchEventEdition(status = 1, eventId = -1, page = 1, perPage = 10) {
  // console.log("eventDetailTitle",title);
  document.getElementById("detailTitleEvent").innerHTML =
    "Detalle " + eventDetail.title;
  eventDetail.id = eventId;
  eventEdition_table.innerHTML = "";
  $.ajax({
    url: eventEditionUrl,
    type: "GET",
    data: {
      status: status,
      eventId: eventId,
      page: page,
      perPage: perPage,
    },
    dataType: "json",
    success: function (data) {
      console.log("fetch edition", data);
      // eventDetailTitle = data[0].title;
      data.eventEdition.forEach((item, index) => {
        const row = `
          <tr>
            <td class="text-center">${util.integerToRoman(item.edition)}</td>
            <td>${item.start_date}</td>
            <td>${item.end_date}</td>
            <td>${item.created_at}</td>
            <td>${item.status ? "Activo" : "Finalizado"}</td>
          </tr>
        `;
        // <i class="fa-solid fa-trash" onclick='fnDeleteCategory(${item.id})'></i>
        renderPagination(status, eventId, data.total, perPage, page);
        eventEdition_table.insertAdjacentHTML("beforeend", row);
      });
    },
    error: function () {
      console.log("Error al obtener categorías");
    },
  });

  console.log(eventDetail);
}

function fnValidateInput(input) {
  // eventData.name = input.value;
  let condition;
  let spanId = `${input.id}-error`;
  let idInputMessage = input.id;
  const existingSpan = util.fnCreateSpanError(input, spanId, idInputMessage);
  let elementId = input.id;
  switch (elementId) {
    case "edition":
      eventEdition[input.name] = input.value;
      condition = input.value.length >= 1;
      util.fnShowInputError(condition, existingSpan, input, context[1]);
      break;
    case "eventSelect":
      eventEdition[input.name] = input.value;
      condition = input.value.length >= 1;
      util.fnShowInputError(condition, existingSpan, input, context[1]);
      break;
    case "start_date":
      console.log(input.value);

      eventEdition[input.name] = input.value;
      condition = input.value.length >= 1;
      util.fnShowInputError(condition, existingSpan, input, context[1]);
      break;
    case "end_date":
      eventEdition[input.name] = input.value;
      condition = input.value.length >= 1;
      util.fnShowInputError(condition, existingSpan, input, context[1]);
      break;
    case "name":
      eventData[input.name] = input.value;
      condition = input.value.length >= 1;
      util.fnShowInputError(condition, existingSpan, input, context[0]);
      break;
    default:
      condition = input.value.length >= 1;
      util.fnShowInputError(condition, existingSpan, input, context[0]);
      break;
  }
  //   console.log(eventData, eventEdition);
}

function addEventTitle() {
  $.ajax({
    url: eventUrl,
    type: "POST",
    data: JSON.stringify({
      eventTitle: eventData,
    }),
    dataType: "json",
    success: function (data) {
      console.log("Success", data);
      fetchEvent();
    },
    error: function (xhr, status, error) {
      try {
        var errorResponse = JSON.parse(xhr.responseText);
        if (errorResponse.error) {
          console.error("Error: " + errorResponse.error);
          alert("Ocurrió un error inesperado. Error");
        }
      } catch (e) {
        console.error("Error inesperado:", e);
        alert("Ocurrió un error inesperado.");
      }
    },
  });
}

function fnLoadSelectEvent() {
  console.log("fnLoadSelectEvent", eventTitle);
  filterEventSelect.options.length = 1;
  eventTitle.forEach((element) => {
    util.createOptSelect(filterEventSelect, element.id, element.title);
  });
}

function fnAddEventEdition() {
  let eventEditionAdd = {};
  // eventDetail.id = null;
  let inputEdition = ["start_date", "end_date"];
  console.log(util.inputIdsByContext[context[1]]);
  inputEdition.forEach((element) => {
    const input = document.getElementById(element);
    fnValidateInput(input);
  });
  if (
    inputEdition.length === util.inputIdsByContext[context[1]].length &&
    eventDetail.id != null
  ) {
    // console.log(util.inputIdsByContext[context[1]]);
    $.ajax({
      url: eventEditionUrl,
      type: "POST",
      data: JSON.stringify({
        p_event_title_id: eventDetail.id,
        p_start_date: eventEdition.start_date,
        p_end_date: eventEdition.end_date,
      }),
      dataType: "json",
      success: function (data) {
        console.log("Success", data);
        fetchEventEdition(1, eventDetail.id);
        util.fnCleanInputs(context[1]);
        $("#addEventEdition").modal("hide");
        // eventDetail = {};
      },
      error: function (xhr, status, error) {
        try {
          var errorResponse = JSON.parse(xhr.responseText);
          if (errorResponse.error) {
            console.error("Error: " + errorResponse.error);
            alert("Ocurrió un error inesperado. Error");
          }
        } catch (e) {
          console.error("Error inesperado:", e);
          alert("Ocurrió un error inesperado.");
        }
      },
    });
  }
}

function renderPagination(status, eventId, total, perPage, currentPage) {
  const totalPaginas = Math.ceil(total / perPage);
  const pagination = $("#eventEditionPagination");
  document.getElementById("eventEditionPagination").innerHTML = "";

  const maxVisible = 3;
  let startPage = Math.max(1, currentPage - Math.floor(maxVisible / 2));
  let endPage = Math.min(totalPaginas, startPage + maxVisible - 1);

  if (endPage - startPage + 1 < maxVisible) {
    startPage = Math.max(1, endPage - maxVisible + 1);
  }

  if (currentPage > 1) {
    pagination.append(`
          <li class="page-item">
              <a class="page-link" href="#" onclick="fetchEventEdition(${status},${eventId},${
      currentPage - 1
    }, ${perPage})">«</a>
          </li>
      `);
  }

  if (startPage > 1) {
    pagination.append(`
          <li class="page-item">
              <a class="page-link" href="#" onclick="fetchEventEdition(${status},${eventId},1, ${perPage})">1</a>
          </li>
          <li class="page-item disabled"><span class="page-link">...</span></li>
      `);
  }

  for (let i = startPage; i <= endPage; i++) {
    pagination.append(`
          <li class="page-item ${i === currentPage ? "active" : ""}">
              <a class="page-link" href="#" onclick="fetchEventEdition(${status},${eventId},${i}, ${perPage})">${i}</a>
          </li>
      `);
  }

  if (endPage < totalPaginas) {
    pagination.append(`
          <li class="page-item disabled"><span class="page-link">...</span></li>
          <li class="page-item">
              <a class="page-link" href="#" onclick="fetchEventEdition(${status},${eventId},${totalPaginas}, ${perPage})">${totalPaginas}</a>
          </li>
      `);
  }

  if (currentPage < totalPaginas) {
    pagination.append(`
          <li class="page-item">
              <a class="page-link" href="#" onclick="fetchEventEdition(
              ${status},
              ${eventId},
              ${currentPage + 1}, 
              ${perPage}
              )">»</a>
          </li>
      `);
  }
}

function fnSetTitle(title) {
  eventDetail.title = title;
}

const modalEventEdition = document.getElementById("evenDetailModal");
const modalEventDetail = document.getElementById("addEventEdition");
function modalUp() {
  modalEventEdition.classList.add("modalIndex");
}
function modalDown() {
  modalEventEdition.classList.remove("modalIndex");
}

modalEventDetail.addEventListener("hidden.bs.modal", function (event) {
  modalDown();
});

function fnLoadUpdateData(data) {
  console.log(data);
  const nameUpdate = document.getElementById("updateEventName");
  nameUpdate.value = data.title;
}