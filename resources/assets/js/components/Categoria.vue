<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>            
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
                <div v-if="listCategorias.length == 0" class="card-body"> No hay registros disponibles </div>
                <div v-else class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select v-model="criterio" class="form-control col-md-3">
                                <option value="nombre">Nombre</option>
                                <option value="descripcion">Descripción</option>
                                </select>
                                <input v-model="buscar" @keyup.enter="listarCategoria(1, buscar, criterio)" type="text" class="form-control" placeholder="Buscar...">
                                <button @click="listarCategoria(1, buscar, criterio)" type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
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
                                            <i class="icon-check"></i>
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
                            <li v-if="pagination.current_page > 1" class="page-item">
                                <a @click.prevent="cambiarPagina(pagination.current_page - 1, buscar, criterio)" class="page-link" href="#" >Ant</a>
                            </li>
                            <li v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']" class="page-item">
                                <a @click.prevent="cambiarPagina(page, buscar, criterio)" v-text="page" class="page-link" href="#"></a>
                            </li>
                            <li v-if="pagination.current_page < pagination.last_page" class="page-item">
                                <a @click.prevent="cambiarPagina(pagination.current_page + 1, buscar, criterio)" class="page-link" href="#">Sig</a>
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
                errorMostrarMsjCategoria: [],
                pagination: {
                    'total ': 0,
                    'current_page': 0,
                    'per_page': 0,
                    'last_page': 0,
                    'from': 0,
                    'to': 0
                },
                offset: 3,
                criterio: 'nombre',
                buscar: ''
            }
        },
        computed: {
            isActived: function(){
                return this.pagination.current_page;
            },
            pagesNumber: function() { // calculo los elementos de la paginación
                if (!this.pagination.to) { return []; }

                var from = this.pagination.current_page - this.offset;
                if (from < 1) { from = 1; }

                var to = from + (this.offset * 2);
                if (to >= this.pagination.last_page) { to = this.pagination.last_page; }

                var pagesArray = [];
                while (from <= to) {
                    pagesArray.push(from);
                    from++;
                }

                return pagesArray;
            }
        },
        methods: {
            listarCategoria(page, buscar, criterio) {
                let resp = this;
                var url = '/categoria?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    //resp.listCategorias = response.data;
                    resp.listCategorias = respuesta.categorias.data;
                    resp.pagination = respuesta.pagination;
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
            cambiarPagina(page, buscar, criterio) {
                let me = this;
                me.pagination.current_page = page; // Actualiza la pagina actual
                me.listarCategoria(page, buscar, criterio); // peticion para la data de la pagina
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
                    resp.listarCategoria(1, '', 'nombre');
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
                    resp.listarCategoria(1, '', 'nombre');
                })
                .catch(function (error) {
                    console.log('error: ', error);
                });
            },
            desactivarCategoria(id){
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

                        let resp = this;

                        axios.put('/categoria/desactivar', {
                            'id': id
                        })
                        .then(function (response) {
                            resp.listarCategoria(1, '', 'nombre');
                            Swal.fire(
                                'Desactivada!',
                                'La categoria fue desactivada correctamente.',
                                'success'
                                )
                            })
                        .catch(function (error) {
                            console.log('error: ', error);
                        });
                        
                    }
                })
            },
            activarCategoria(id){
                Swal.fire({
                title: 'Esta seguro de activar esta categoria?',
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar'
                }).then((result) => {
                    if (result.value) {

                        let resp = this;

                        axios.put('/categoria/activar', {
                            'id': id
                        })
                        .then(function (response) {
                            resp.listarCategoria(1, '', 'nombre');
                            Swal.fire(
                                'Activada!',
                                'La categoria fue activada correctamente.',
                                'success'
                                )
                            })
                        .catch(function (error) {
                            console.log('error: ', error);
                        });
                        
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
            this.listarCategoria(1, this.buscar, this.criterio);
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
