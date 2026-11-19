class AdminsitrarVistas{
    constructor(){
        this.enlace = null;
        this.secciones = null;
        this.vista = null;
        this.productos = null;
        this.usuarios = null;
    }

    inicializar(){
        fetch("http://localhost/Daw2mvc/index.php?controller=carta&action=api")
        .then(respuesta => respuesta.json())
        .then(r => {
            console.log(r)
        })
    } 
}

const administrar = new AdminsitrarVistas();
administrar.inicializar();