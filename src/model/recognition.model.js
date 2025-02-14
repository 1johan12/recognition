class RecognitionProcessModel {
  constructor({
    nombre,
    categoria,
    codigo,
    correo,
    category_id,
    event_edition_id,
    participant_type,
    ...rest
  }) {
    this.fullname = nombre;
    this.access_code = codigo;
    this.mail = correo ?? "";
    this.category_id = category_id;
    this.participant_type = "";
    this.event_edition_id = event_edition_id;
    // Object.assign(this,rest);
  }
}

class RecognitionModel {
  constructor({
    nombre,
    categoria,
    codigo,
    correo,
    category_id,
    event_edition_id,
    participant_type,
    ...rest
  }) {
    this.fullname = nombre;
    this.access_code = codigo;
    this.mail = correo ?? "";
    this.category_id = category_id;
    this.participant_type = "";
    this.event_edition_id = event_edition_id;
    // Object.assign(this,rest);
  }
}
