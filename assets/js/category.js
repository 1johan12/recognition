const util = new Utils();
const context = "category";
let category = [];
const tableBody = document.querySelector("#category_table tbody");
const nameCategory = document.getElementById("categoryName");
let categoryUrl = "src/controller/category.controller.php";
let statusSelect = document.getElementById("status");
let nameUpdateCategory = document.getElementById("nameUpdateCategory");
fetchCategory();
function fetchCategory(status = 1) {
  $.ajax({
    url: categoryUrl,
    type: "GET",
    data: {
      status: status,
    },
    dataType: "json",
    success: function (data) {
      console.log(data);
      tableBody.innerHTML = "";
      data.forEach((item, index) => {
        const row = `
                <tr>
                    <td scope="row">${index + 1}</td>
                    <td>${item.name}</td>
                    <td>
                        <i class="fa-solid fa-trash" onclick='fnDeleteCategory(${
                          item.id
                        })'></i>
                        <i class="fa-solid fa-pen-to-square" data-bs-toggle="modal" data-bs-target="#updateCategoryModal" onclick='fnLoadData(${JSON.stringify(
                          item
                        )})'></i>
                    </td>
                </tr>
            `;
        tableBody.insertAdjacentHTML("beforeend", row);
      });
    },
    error: function () {
      console.log("Error al obtener categorías");
    },
  });
}
function fnValidateInput(input) {
  let condition;
  let spanId = `${input.id}-error`;
  let idInputMessage = input.id;
  const existingSpan = util.fnCreateSpanError(input, spanId, idInputMessage);
  let elementId = input.id;
  switch (elementId) {
    default:
      condition = input.value.length >= 1;
      util.fnShowInputError(condition, existingSpan, input, context);
      break;
  }
}

function addCategory() {
  $.ajax({
    url: categoryUrl,
    type: "POST",
    contentType: "application/json",
    data: JSON.stringify({
      name: nameCategory.value,
    }),
    success: function (response) {
      util.inputIdsByContext[context] = [];
      console.log("response", response);
      if (response.success) {
        util.inputIdsByContext[context] = [];
        fetchCategory();
        $("#addCategoryModal").modal("hide");
      } else {
        alert("Error al agregar categoría");
      }
    },
  });
}

function fnDeleteCategory(category) {
  console.log("fnDeleteCategory", category);
  $.ajax({
    url: categoryUrl,
    type: "DELETE",
    contentType: "application/json",
    data: JSON.stringify({
      id: category,
    }),
    success: function (response) {
      console.log("response", response);
      alert("Categoria Eliminado Correctamente");
      fetchCategory();
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

function fnRegisterCategory() {
  console.log("Register Category");
  inputFn = ["categoryName"];
  inputFn.forEach((element) => {
    const input = document.getElementById(element);
    fnValidateInput(input);
  });
  
  if (inputFn.length === util.inputIdsByContext[context].length) addCategory();
}

function fnLoadData(element) {
  category = element;
  nameUpdateCategory.value = element.name;
  statusSelect.value = element.status;
  // console.log("category", category);
}

function fnUpdateCategory() {
  
  let inputIdContext = util.inputIdsByContext[context];
  console.log("fnUpdateCategory");
  
  inputFn = ["nameUpdateCategory", "status"];
  inputFn.forEach((element) => {
    const input = document.getElementById(element);
    fnValidateInput(input);
  });
  
  if (inputFn.length === inputIdContext.length) {
    category.name = nameUpdateCategory.value;
    category.status = statusSelect.value;
    $.ajax({
      url: categoryUrl,
      type: "PUT",
      contentType: "application/json",
      data: JSON.stringify(category),
      success: function (response) {
        util.inputIdsByContext[context] = [];
        console.log("response", response);
        alert("Categoria actualizada con éxito");
        fetchCategory();
        $("#updateCategoryModal").modal("hide");
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
