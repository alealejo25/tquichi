<?php

namespace App\Http\Controllers;
use App\Transporte;
use Illuminate\Http\Request;

class TransporteController extends Controller
{

    public function index(Request $request)
    {
        $transportes=Transporte::search($request->name)->orderBy('nombre','ASC')->paginate(10);
        return view('abms.transportes.index')
        ->with('transportes',$transportes);
    }
       /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('abms.transportes.create');
    }
    public function store(Request $request)
    {
        /*VALIDACION -----------------------------------------*/
        $campos=[
            'nombre'=>'required|string|max:50',
            'direccion'=>'required|string|max:50',
            'telefono'=>'required',
            'email1'=>'required',
            'contacto'=>'required|string|max:50',
            'telefono_contacto'=>'required',
            'cuit'=>'required|integer'
        ];
        $Mensaje=["required"=>'El :attribute es requerido'];
        $this->validate($request,$campos,$Mensaje);
       
       $datostransporte=request()->except('_token');
       Transporte::insert($datostransporte);
       //return response()->json($datosCamion);
       return Redirect('abms/transportes')->with('Mensaje','Transporte agregado con éxito');
    }

    public function edit($id)
    {
        $transportes=Transporte::find($id);
        return view('abms.transportes.edit')
            ->with('transportes',$transportes);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\vr  $vr
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {


         $campos=[
            'nombre'=>'required|string|max:50',
            'direccion'=>'required|string|max:50',
            'telefono'=>'required',
            'email1'=>'required',
            'contacto'=>'required|string|max:50',
            'telefono_contacto'=>'required',
            'cuit'=>'required|integer',
            'saldo'=>'required|numeric',
            'email1'=>'required',
        ];
        $Mensaje=["required"=>'El :attribute es requerido'];
        $this->validate($request,$campos,$Mensaje);
        $datosTransporte=request()->except(['_token','_method']);
      

        Transporte::where('id','=',$id)->update($datosTransporte);
        return Redirect('abms/transportes')->with('Mensaje','Transporte Modificado con éxito!!!!!');
    }
     public function destroy($id)
        {
            
            Transporte::destroy($id);
            return Redirect('abms/transportes')->with('Mensaje','Tranporte Eliminado con éxito!!!!!!');

            //codigo para eliminar fotos
            // $camion=Camion::findOrFail($id);
            // if (Storage::delete('public/'.$camion->foto)){
            //     Camion::destroy($id);
            // }
            
            // return redirect('/abms/camiones');
        }
}
