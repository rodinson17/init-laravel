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
                    <i class="fa fa-align-justify"></i> Articulos
                    <button @click="openModal('articulo','registrar')" type="button" class="btn btn-secondary" >
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div v-if="listArticulos.length == 0" class="card-body"> No hay registros disponibles </div>
                <div v-else class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select v-model="criterio" class="form-control col-md-3">
                                <option value="nombre">Nombre</option>
                                <option value="descripcion">Descripción</option>
                                </select>
                                <input v-model="buscar" @keyup.enter="listarArticulos(1, buscar, criterio)" type="text" class="form-control" placeholder="Buscar...">
                                <button @click="listarArticulos(1, buscar, criterio)" type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Categoría</th>
                                <th>Precio venta</th>
                                <th>Stock</th>
                                <th>Descripción</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="articulo in listArticulos" :key="articulo.id">
                                <td>
                                    <button  @click="openModal('articulo','actualizar', articulo)" type="button" class="btn btn-warning btn-sm">
                                        <i class="icon-pencil"></i>
                                    </button> &nbsp;
                                    <template v-if="articulo.condicion">
                                        <button @click="desactivarArticulo(articulo.id)" type="button" class="btn btn-danger btn-sm">
                                            <i class="icon-trash"></i>
                                        </button>
                                    </template>
                                    <template v-else>
                                        <button @click="activarArticulo(articulo.id)" type="button" class="btn btn-info btn-sm">
                                            <i class="icon-check"></i>
                                        </button>
                                    </template>
                                </td>
                                <td v-text="articulo.codigo"></td>
                                <td v-text="articulo.nombre"></td>
                                <td v-text="articulo.nombre_categoria"></td>
                                <td v-text="articulo.precio_venta"></td>
                                <td v-text="articulo.stock"></td>
                                <td v-text="articulo.descripcion"></td>
                                <td>
                                    <div v-if="articulo.condicion">
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
                                <label class="col-md-3 form-control-label" for="text-input">Codigo</label>
                                <div class="col-md-9">
                                    <input v-model="codigo" type="text" class="form-control" placeholder="Código del articulo.">
                                    <barcode :value="codigo" :options="{ format: 'EAN-13'}"> Generando código de barras</barcode>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                <div class="col-md-9">
                                    <input v-model="nombre" type="text" class="form-control" placeholder="Nombre del articulo.">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Categoria</label>
                                <div class="col-md-9">
                                    <select v-model="idcategoria" class="form-control col-md-3">
                                        <option value="0" disabled>Selecione</option>
                                        <option v-for="categoria in arrayCategorias" :key="categoria.id" :value="categoria.id" v-text="categoria.nombre"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Precio venta</label>
                                <div class="col-md-9">
                                    <input v-model="precio_venta" type="number" class="form-control" placeholder="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input"> Stock </label>
                                <div class="col-md-9">
                                    <input v-model="stock" type="number" class="form-control" placeholder="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                                <div class="col-md-9">
                                    <input v-model="descripcion" type="text" class="form-control" placeholder="Ingrese una descripción.">
                                </div>
                            </div>
                            <div v-show="errorArticulo" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjArticulo" :key="error" v-text="error"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button @click="closeModal()" type="button" class="btn btn-secondary">Cerrar</button>
                        <button @click="registrarArticulo()"  v-if="typeAction == 1" type="button" class="btn btn-primary">Guardar</button>
                        <button @click="actualizarArticulo()" v-if="typeAction == 2" type="button" class="btn btn-primary">Actualizar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
    </main>    
</template>

<script>
    import VueBarcode from 'vue-barcode';
    export default {
        data() {
            return {
                articulo_id: 0,
                idcategoria: 0,
                nombre_categoria: '',
                codigo: '',
                nombre: '',
                precio_venta: 0,
                stock: 0,
                descripcion: '',
                listArticulos: [],
                modal: 0,
                titleModal: '',
                typeAction: 0,
                errorArticulo: 0,
                errorMostrarMsjArticulo: [],
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
                buscar: '',
                arrayCategorias: []
            }
        },
        components: {
            'barcode': VueBarcode
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
            listarArticulos(page, buscar, criterio) {
                let resp = this;
                var url = '/articulo?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    resp.listArticulos = respuesta.articulos.data;
                    resp.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectCategoria() {
                let resp = this;
                
                axios.get('/categoria/selectCategoria')
                .then(function (response) {
                    resp.arrayCategorias = response.data.categorias;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page, buscar, criterio) {
                let me = this;
                me.pagination.current_page = page; // Actualiza la pagina actual
                me.listarArticulos(page, buscar, criterio); // peticion para la data de la pagina
            },
            registrarArticulo() {
                if (this.validarArticulo()){ return; }

                let resp = this;

                axios.post('/articulo/registrar', {
                    'idcategoria': this.idcategoria,
                    'codigo': this.codigo,
                    'nombre': this.nombre,
                    'stock': this.stock,
                    'precio_venta': this.precio_venta,
                    'descripcion': this.descripcion
                })
                .then(function (response) {
                    resp.closeModal();
                    resp.listarArticulos(1, '', 'nombre');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            actualizarArticulo(){
                if (this.validarArticulo()){ return; }

                let resp = this;

                axios.put('/articulo/actualizar', {
                    'id': this.articulo_id,
                    'idcategoria': this.idcategoria,
                    'codigo': this.codigo,
                    'nombre': this.nombre,
                    'stock': this.stock,
                    'precio_venta': this.precio_venta,
                    'descripcion': this.descripcion
                })
                .then(function (response) {
                    resp.closeModal();
                    resp.listarArticulos(1, '', 'nombre');
                })
                .catch(function (error) {
                    console.log('error: ', error);
                });
            },
            desactivarArticulo(id){
                Swal.fire({
                title: 'Esta seguro de desactivar este articulo?',
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar'
                }).then((result) => {
                    if (result.value) {

                        let resp = this;

                        axios.put('/articulo/desactivar', {
                            'id': id
                        })
                        .then(function (response) {
                            resp.listarArticulos(1, '', 'nombre');
                            Swal.fire(
                                'Desactivado!',
                                'El articulo fue desactivado correctamente.',
                                'success'
                                )
                            })
                        .catch(function (error) {
                            console.log('error: ', error);
                        });
                        
                    }
                })
            },
            activarArticulo(id){
                Swal.fire({
                title: 'Esta seguro de activar este articulo?',
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar'
                }).then((result) => {
                    if (result.value) {

                        let resp = this;

                        axios.put('/articulo/activar', {
                            'id': id
                        })
                        .then(function (response) {
                            resp.listarArticulos(1, '', 'nombre');
                            Swal.fire(
                                'Activado!',
                                'El articulo fue activado correctamente.',
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
                    case "articulo":
                    {
                        switch(action){
                            case 'registrar':
                                {
                                    this.modal = 1;
                                    this.titleModal = 'Registrar Artículo';
                                    this.idcategoria = 0;
                                    this.codigo = '';
                                    this.nombre_categoria = '';
                                    this.nombre = '';
                                    this.precio_venta = 0;
                                    this.stock = 0;
                                    this.descripcion = '';
                                    this.typeAction = 1;
                                    break;
                                }
                            case 'actualizar':
                                {
                                    this.modal = 1;
                                    this.titleModal = 'Actualizar Artículo';
                                    this.typeAction = 2;    
                                    this.articulo_id = data['id'];   
                                    this.idcategoria = data['idcategoria'];
                                    this.codigo = data['codigo'];
                                    this.nombre = data['nombre'];
                                    this.precio_venta = data['precio_venta'];
                                    this.stock = data['stock'];
                                    this.descripcion = data['descripcion'];
                                    break;
                                }
                        }
                    }
                }
                this.selectCategoria();
            }, 
            closeModal(){
                this.modal = 0;
                this.titleModal = '';
                this.idcategoria = 0;
                this.codigo = '';
                this.nombre_categoria = '';
                this.nombre = '';
                this.precio_venta = 0;
                this.stock = 0;
                this.descripcion = '';
                this.errorArticulo = 0;
            },
            validarArticulo(){
                this.errorArticulo = 0;
                this.errorMostrarMsjArticulo = [];

                if (this.idcategoria == 0) this.errorMostrarMsjArticulo.push("Seleccione una categoría.");
                if (!this.nombre) this.errorMostrarMsjArticulo.push("El nombre del articulo no puede estar vacio");
                if (!this.stock) this.errorMostrarMsjArticulo.push("El stock del articulo debe ser un número y no puede estar vacio");
                if (!this.precio_venta) this.errorMostrarMsjArticulo.push("El precio de venta del articulo debe ser un número y no puede estar vacio");

                if (this.errorMostrarMsjArticulo.length) this.errorArticulo  = 1;

                return this.errorArticulo;
            }
        },
        mounted() {
            this.listarArticulos(1, this.buscar, this.criterio);
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
