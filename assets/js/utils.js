class Utils {
  constructor() {
    this.inputIdsByContext = {
      recognition: [],
      category: [],
      event: [],
      eventEdition: [],
    };
  }

  fnCleanInputs(context) {
    const inputArray = this.inputIdsByContext[context];
    inputArray.forEach((element) => {
      const input = document.getElementById(element);
      input.value = "";
    });
    this.inputIdsByContext[context] = [];
    console.log("Context", context, this.inputIdsByContext);
  }

  fnShowInputError(condition, existingSpan, input, context) {
    let inputId = this.inputIdsByContext[context];
    // console.log("Condition",condition,!this.inputIdsByContext[context].includes(input.id));
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
    // console.log("INPUT ID",inputId);
  }

  fnCreateSpanError(input, spanId, elementId) {
    let existingSpan = document.getElementById(spanId);
    if (!existingSpan) {
      const newSpan = document.createElement("span");
      newSpan.classList.add("ue_span_error");
      newSpan.id = spanId;
      newSpan.textContent = this.getMessage(input.name);
      input.parentNode.insertBefore(newSpan, input.nextSibling);
      existingSpan = newSpan;
    }
    return existingSpan;
  }

  getMessage(inputIdMessage) {
    const messages = {
      event: "Evento obligatorio*",
      eventEdition: "obligatorio*",
      excelFile: "Selecciona un archivo excel*",
      name: "Nombre obligatorio*",
      status: "Estado obligatorio*",
      generic: "obligatorio*",
      start_date: "fecha obligatorio*",
      end_date: "fecha obligatorio*",
    };
    return messages[inputIdMessage] || "Error no especificado.";
  }

  createOptSelect(select, value, content) {
    const option = document.createElement("option");
    option.value = `${value}`;
    option.textContent = content;
    select.appendChild(option);
  }

  integerToRoman(num) {
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
}
