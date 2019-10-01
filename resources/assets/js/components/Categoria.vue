<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item"><a href="#">Admin</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Categorías
                    <button @click="openModal('categoria','registrar')" type="button" class="btn btn-secondary" >
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" id="opcion" name="opcion">
                                <option value="nombre">Nombre</option>
                                <option value="descripcion">Descripción</option>
                                </select>
                                <input type="text" id="texto" name="texto" class="form-control" placeholder="Texto a buscar">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="categoria in listCategorias" :key="categoria.id">
                                <td>
                                    <button  @click="openModal('categoria','actualizar', categoria)" type="button" class="btn btn-warning btn-sm">
                                        <i class="icon-pencil"></i>
                                    </button> &nbsp;
                                    <template v-if="categoria.condicion">
                                        <button @click="desactivarCategoria(categoria.id)" type="button" class="btn btn-danger btn-sm">
                                            <i class="icon-trash"></i>
                                        </button>
                                    </template>
                                    <template v-else>
                                        <button @click="activarCategoria(categoria.id)" type="button" class="btn btn-info btn-sm">
                                            <i class="icon-info"></i>
                                        </button>
                                    </template>
                                </td>
                                <td v-text="categoria.nombre"></td>
                                <td v-text="categoria.descripcion"></td>
                                <td>
                                    <div v-if="categoria.condicion">
                                         <span class="badge badge-success">Activo</span>
                                    </div>
                                    <div v-else>
                                         <span class="badge badge-danger">Inactivo</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item">
                                <a class="page-link" href="#">Ant</a>
                            </li>
                            <li class="page-item active">
                                <a class="page-link" href="#">1</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">3</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">4</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">Sig</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
        <!--Inicio del modal agregar/actualizar-->
        <div :class="{'mostrar': modal}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 v-text="titleModal" class="modal-title"></h4>
                        <button @click="closeModal()" type="button" class="close" aria-label="Close">
                        <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                <div class="col-md-9">
                                    <input v-model="nombre" type="text" class="form-control" placeholder="Nombre de categoría.">
                                    <!-- <span class="help-block">(*) Ingrese el nombre de la categoría</span> -->
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                                <div class="col-md-9">
                                    <input v-model="descripcion" type="text" class="form-control" placeholder="Ingrese una descripción.">
                                </div>
                            </div>
                            <div v-show="errorCategoria" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjCategoria" :key="error" v-text="error"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button @click="closeModal()" type="button" class="btn btn-secondary">Cerrar</button>
                        <button @click="registrarCategoria()"  v-if="typeAction == 1" type="button" class="btn btn-primary">Guardar</button>
                        <button @click="actualizarCategoria()" v-if="typeAction == 2" type="button" class="btn btn-primary">Actualizar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
        <!-- Inicio del modal Eliminar -->
        <!-- <div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-danger" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Eliminar Categoría</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Estas seguro de eliminar la categoría?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-danger">Eliminar</button>
                    </div>
                </div>
                < !-- /.modal-content -- >
            </div>
            < !-- /.modal-dialog -- >
        </div> -->
        <!-- Fin del modal Eliminar -->
    </main>
    
</template>

<script>
    export default {
        data() {
            return {
                categoria_id: 0,
                nombre: '',
                descripcion: '',
                listCategorias: [],
                modal: 0,
                titleModal: '',
                typeAction: 0,
                errorCategoria: 0,
                errorMostrarMsjCategoria: []
            }
        },
        methods: {
            listarCategoria() {
                let resp = this;
                axios.get('/categoria')
                .then(function (response) {
                    // handle success
                    resp.listCategorias = response.data;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });
                /* .finally(function () {
                    // always executed
                    console.log('finally');                    
                }); */
            },
            registrarCategoria() {
                if (this.validarCategoria()){ return; }

                let resp = this;

                axios.post('/categoria/registrar', {
                    'nombre': this.nombre,
                    'descripcion': this.descripcion
                })
                .then(function (response) {
                    resp.closeModal();
                    resp.listarCategoria();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            actualizarCategoria(){
                if (this.validarCategoria()){ return; }

                let resp = this;

                axios.put('/categoria/actualizar', {
                    'id': this.categoria_id,
                    'nombre': this.nombre,
                    'descripcion': this.descripcion
                })
                .then(function (response) {
                    resp.closeModal();
                    resp.listarCategoria();
                })
                .catch(function (error) {
                    console.log('error: ', error);
                });
            },
            desactivarCategoria(){
                Swal.fire({
                title: 'Esta seguro de desactivar esta categoria?',
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar'
                }).then((result) => {
                    if (result.value) {
                        Swal.fire(
                        'Desactivada!',
                        'La categoria fue desactivada.',
                        'success'
                        )
                    }
                })
            },
            openModal(model, action, data = []){
                switch(model) {
                    case "categoria":
                    {
                        switch(action){
                            case 'registrar':
                                {
                                    this.modal = 1;
                                    this.titleModal = 'Registrar Categoria';
                                    this.nombre = '';
                                    this.descripcion = '';
                                    this.typeAction = 1;
                                    break;
                                }
                            case 'actualizar':
                                {
                                    this.modal = 1;
                                    this.titleModal = 'Actualizar Categoria';
                                    this.typeAction = 2;    
                                    this.categoria_id = data['id'];                                
                                    this.nombre = data['nombre'];
                                    this.descripcion = data['descripcion'];
                                    break;
                                }
                        }
                    }
                }
            }, 
            closeModal(){
                this.modal = 0;
                this.titleModal = '';
                this.nombre = '';
                this.descripcion = '';
            },
            validarCategoria(){
                this.errorCategoria = 0;
                this.errorMostrarMsjCategoria = [];

                if (!this.nombre) this.errorMostrarMsjCategoria.push("El nombre de la Categoría no puede estar vacio");

                if (this.errorMostrarMsjCategoria.length) this.errorCategoria  = 1;

                return this.errorCategoria;
            }
        },
        mounted() {
            this.listarCategoria();
            /* console.log('Component mounted.') */
        }
    }
</script>
<style>
    .modal-content {
        width: 100% !important;
        position:  absolute !important;
    }
    .mostrar {
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error {
        color: red !important;
        font-weight: bold;
    }
</style>
