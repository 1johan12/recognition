class RecognitionModel{
    constructor({nombre,categoria,codigo,correo,category_id}) {
        this.name = nombre;
        this.category = categoria;
        this.accesscode = codigo;
        this.mail = correo ?? "";
        this.category_id = category_id;
    }
}