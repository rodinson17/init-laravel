<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Articulo;

class ArticuloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar == ''){
            $articulos = Articulo::join('categorias', 'articulos.idcategoria', '=', 'categorias.id')
            ->select('articulos.id', 'articulos.idcategoria', 'articulos.codigo', 'articulos.nombre', 'categorias.nombre as nombre_categoria', 'articulos.precio_venta', 'articulos.stock', 'articulos.descripcion', 'articulos.condicion')
            ->orderBy('articulos.id', 'desc')->paginate(3);
        } else {
            $articulos = Articulo::join('categorias', 'articulos.idcategoria', '=', 'categorias.id')
            ->select('articulos.id', 'articulos.idcategoria', 'articulos.codigo', 'articulos.nombre', 'categorias.nombre as nombre_categoria', 'articulos.precio_venta', 'articulos.stock', 'articulos.descripcion', 'articulos.condicion')
            ->where('articulos.' . $criterio, 'like', '%'. $buscar .'%')
            ->orderBy('articulos.id', 'desc')->paginate(3);
        }
 
        return [
            'pagination' => [
                'total ' => $articulos->total(),
                'current_page' => $articulos->currentPage(),
                'per_page' => $articulos->perPage(),
                'last_page' => $articulos->lastPage(),
                'from' => $articulos->firstItem(),
                'to' => $articulos->lastItem()
            ],
            'articulos' => $articulos
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $articulos = new Articulo();
        $articulos->idcategoria = $request->idcategoria;
        $articulos->codigo = $request->codigo;
        $articulos->nombre = $request->nombre;
        $articulos->precio_venta = $request->precio_venta;
        $articulos->stock = $request->stock;
        $articulos->descripcion = $request->descripcion;
        $articulos->condicion = '1';
        $articulos->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $articulos = Articulo::findOrFail($request->id);
        $articulos->idcategoria = $request->idcategoria;
        $articulos->codigo = $request->codigo;
        $articulos->nombre = $request->nombre;
        $articulos->precio_venta = $request->precio_venta;
        $articulos->stock = $request->stock;
        $articulos->descripcion = $request->descripcion;
        $articulos->condicion = '1';
        $articulos->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $articulos = Articulo::findOrFail($request->id);
        $articulos->condicion = '1';
        $articulos->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $articulos = Articulo::findOrFail($request->id);
        $articulos->condicion = '0';
        $articulos->save();
    }
}
