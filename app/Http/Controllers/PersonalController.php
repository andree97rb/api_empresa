<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Personal;

class PersonalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $personal=Cache::remember('cachepersonal',20/60, function()
        {
            return Personal::all();
        });

        return response()->json(['status'=>'ok','data'=>$personal], 200);
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
        if (!$request->nombre || !$request->sueldo || !$request->fechaNacimiento)
		{
			return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan datos para acceder a su solicitud.'])], 422);
        }

        $nuevoPersonal=Personal::create($request->all());

        return response()->json(['data'=>$nuevoPersonal], 201)->header('Location', url('/api/v1/').'/personal/'.$nuevoPersonal->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $personal=Personal::find($id);

		if (!$personal)
		{
			return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encontró ningun personal con este código.'])], 404);
		}

        return response()->json(['status'=>'ok','data'=>$personal], 200);
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
		$personal=Personal::find($id);

		// Si no existe mostramos error.
        if(!$personal)
        {
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encontró ningún personal con este código.'])], 404);
        }
		// Almacenamos en variables para facilitar el uso, los campos recibidos.
        $nombre = $request->nombre;
        $sueldo = $request->sueldo;
        $fechaNacimiento = $request->fechaNacimiento;
        $estado = $request->estado;
        $idCategoria = $request->idCategoria;
        $idEmpresa = $request->idEmpresa;
        


		if ($request->method()=='PATCH')
		{
			$bandera=false;
            
            $personal->sueldo=$sueldo;
            $personal->estado=$estado;
            $personal->idCategoria=$idCategoria;
            $personal->idEmpresa=$idEmpresa;

			// Actualización parcial de datos.
			if ($nombre !=null && $nombre!='')
			{
				$personal->nombre=$nombre;
				$bandera=true;
			}
            if ($fechaNacimiento !=null && $fechaNacimiento!='')
			{
				$personal->fechaNacimiento=$fechaNacimiento;
				$bandera=true;
			}
			else
			{
				// Devolvemos un código 304 Not Modified.
				return response()->json(['errors'=>array(['code'=>304,'message'=>'No se ha modificado ningún dato de la personal.'])],304);
			}
		}

		if (!$nombre || !$fechaNacimiento)
		{
			// Se devuelve código 422 Unprocessable Entity.
			return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan valores para completar el procesamiento.'])],422);
		}

		// Actualizamos los 3 campos:
		$personal->nombre=$nombre;
		$personal->sueldo=$sueldo;        
		$personal->fechaNacimiento=$fechaNacimiento;
		$personal->estado=$estado;
		$personal->idCategoria=$idCategoria;
		$personal->idEmpresa=$idEmpresa;


		// Grabamos el fabricante
		$personal->save();
		return response()->json(['status'=>'ok','data'=>$personal],200);
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
