<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Categoria;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categoria=Cache::remember('cachecategoria',20/60, function()
        {
            return Categoria::all();
        });

        return response()->json(['status'=>'ok','data'=>$categoria], 200);
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
        if (!$request->nombre)
		{
			return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan datos para acceder a su solicitud.'])], 422);
        }

        $nuevaCategoria=Categoria::create($request->all());

        return response()->json(['data'=>$nuevaCategoria], 201)->header('Location', url('/api/v1/').'/categoria/'.$nuevaCategoria->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $categoria=Categoria::find($id);

		if (!$categoria)
		{
			return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encontró ninguna categoria con este código.'])], 404);
		}

        return response()->json(['status'=>'ok','data'=>$categoria], 200);
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
    public function update(Request $request, $id)
    {
		$categoria=Categoria::find($id);

		// Si no existe mostramos error.
        if(!$categoria)
        {
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encontró ningún categoria con este código.'])], 404);
        }
		// Almacenamos en variables para facilitar el uso, los campos recibidos.
        $nombre = $request->nombre;

		// Comprobamos si recibimos petición PATCH(parcial) o PUT (Total)
		if ($request->method()=='PATCH')
		{
			$bandera=false;

			// Actualización parcial de datos.
			if ($nombre !=null && $nombre!='')
			{
				$categoria->nombre=$nombre;
				$bandera=true;
			}
			else
			{
				// Devolvemos un código 304 Not Modified.
				return response()->json(['errors'=>array(['code'=>304,'message'=>'No se ha modificado ningún dato de la categoria.'])],304);
			}
		}

		if (!$nombre)
		{
			// Se devuelve código 422 Unprocessable Entity.
			return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan valores para completar el procesamiento.'])],422);
		}

		// Actualizamos los 3 campos:
		$categoria->nombre=$nombre;

		// Grabamos el fabricante
		$categoria->save();
		return response()->json(['status'=>'ok','data'=>$categoria],200);
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
}
