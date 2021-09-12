<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Empresa;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $empresa=Cache::remember('cacheempresa',20/60, function()
        {
            return Empresa::all();
        });

        return response()->json(['status'=>'ok','data'=>$empresa], 200);
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
        if (!$request->razonsocial || !$request->ruc || !$request->tipo)
		{
			return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan datos para acceder a su solicitud.'])], 422);
        }

        $nuevaEmpresa=Empresa::create($request->all());

        return response()->json(['data'=>$nuevaEmpresa], 201)->header('Location', url('/api/v1/').'/empresa/'.$nuevaEmpresa->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $empresa=Empresa::find($id);

		if (!$empresa)
		{
			return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encontró ninguna empresa con este código.'])], 404);
		}

        return response()->json(['status'=>'ok','data'=>$empresa], 200);
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
		$empresa=Empresa::find($id);

		// Si no existe mostramos error.
        if(!$empresa)
        {
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encontró ningún empresa con este código.'])], 404);
        }
		// Almacenamos en variables para facilitar el uso, los campos recibidos.
        $razonsocial = $request->razonsocial;
        $ruc = $request->ruc;
        $tipo = $request->tipo;

		// Comprobamos si recibimos petición PATCH(parcial) o PUT (Total)
		if ($request->method()=='PATCH')
		{
			$bandera=false;

			// Actualización parcial de datos.
			if ($razonsocial !=null && $razonsocial!='')
			{
				$empresa->razonsocial=$razonsocial;
				$bandera=true;
			}
            if ($ruc !=null && $ruc!='')
			{
				$empresa->ruc=$ruc;
				$bandera=true;
			}
            if ($tipo !=null && $tipo!='')
			{
				$empresa->tipo=$tipo;
				$bandera=true;
			}
			else
			{
				// Devolvemos un código 304 Not Modified.
				return response()->json(['errors'=>array(['code'=>304,'message'=>'No se ha modificado ningún dato de la empresa.'])],304);
			}
		}

		if (!$razonsocial || !$ruc || !$tipo)
		{
			// Se devuelve código 422 Unprocessable Entity.
			return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan valores para completar el procesamiento.'])],422);
		}

		// Actualizamos los 3 campos:
		$empresa->razonsocial=$razonsocial;
		$empresa->ruc=$ruc;
		$empresa->tipo=$tipo;

		// Grabamos el fabricante
		$empresa->save();
		return response()->json(['status'=>'ok','data'=>$empresa],200);
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
