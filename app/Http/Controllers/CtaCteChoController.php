<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Chofer;
use App\Transporte;
use App\CtaCteCho;
use App\CtaCteTransporte;

use Laracasts\Flash\Flash;

class CtaCteChoController extends Controller
{
      public function __construct()
    {
        $this->middleware('auth');
    }
	public function index(Request $request)
    {
        
        $transportes=Transporte::orderBy('nombre','ASC')->paginate(20);
        return view('cuentascorrientes.transportes.index')
            ->with('transportes',$transportes);

    }
     public function nuevocomprobante($id){
		$transportes=Transporte::where('id',$id)->get();
		$cuentacorrientetransporte=CtaCteTransporte::where('transporte_id',$id)->where('tipocomprobante','FACTURA')->pluck('nrocomprobante','id');
		return view('cuentascorrientes.transportes.nuevocomprobante')
		 	->with('cuentacorrientetransporte',$cuentacorrientetransporte)
		 	->with('transportes',$transportes)
  			->with('id',$id);


    }



   public function guardarcomprobantet(Request $request,$id)
    {

        /*VALIDACION -----------------------------------------*/
        $campos=[
            'tipocomprobante'=>'required',
            'nrocomprobante'=>'required|unique:ctasctestransporte',
            'fechaemision'=>'required',
            'fechavencimiento'=>'required',
            'importe'=>'required|numeric'

           
        ];
        $Mensaje=["required"=>'El :attribute es requerido'];
        $this->validate($request,$campos,$Mensaje);

        $acumulado=Transporte::where('id',$id)->orderBy('id','DESC')->limit(1)->get();

        $datosComprobante=new CtaCteTransporte(request()->except('_token'));
        $datosComprobante->transporte_id=$id;

        switch ($request->tipocomprobante){
            case 'FACTURA':
                $datosComprobante->debe=$request->importe;
                $datosComprobante->haber=0;
                $datosComprobante->acumulado=$acumulado[0]->saldo + $request->importe;
               
                
               // $datosComprobante->importefinal=$request->importefinal;

              
                $datosComprobante->save();
                $editarcliente=Transporte::where('id',$id)
                ->update([
                        'saldo'=>$datosComprobante->acumulado
                          ]);
            break;

            
            case 'NOTA DE DEBITO':
                $datosComprobante->debe=$request->importe;
                $datosComprobante->haber=0;
                $datosComprobante->acumulado=$acumulado[0]->saldo + $request->importe;
                $datosComprobante->save();
                $editarcliente=Transporte::where('id',$id)
                ->update([
                        'saldo'=>$datosComprobante->acumulado
                          ]);

            break;
            case 'NOTA DE CREDITO':
                $datosComprobante->debe=0;
                $datosComprobante->haber=$request->importe;
                $datosComprobante->acumulado=$acumulado[0]->saldo - $request->importe;
                $datosComprobante->save();
                $editarcliente=Transporte::where('id',$id)
                ->update([
                          'saldo'=>$datosComprobante->acumulado
                          ]);
            break;
            case 'RECIBO':
                $datosComprobante->debe=$request->importe;
                $datosComprobante->haber=0;
                $datosComprobante->acumulado=$acumulado[0]->saldo + $request->importe;
                $datosComprobante->save();
                $editarcliente=Transporte::where('id',$id)
                ->update([
                          'saldo'=>$datosComprobante->acumulado
                          ]);
            break;
            case 'REMITO':
                $datosComprobante->debe=0;
                $datosComprobante->haber=$request->importe;
                $datosComprobante->acumulado=$acumulado[0]->saldo - $request->importe;
                $datosComprobante->save();
                $editarcliente=Transporte::where('id',$id)
                ->update([
                        'saldo'=>$datosComprobante->acumulado
                          ]);


            break;

        }
        flash::success('Comprobante ingresado!!! - Tipo '.$request->tipocomprobante. '-' .$request->nrocomprobante);
       return Redirect('cuentascorrientes/transportes/')->with('Mensaje','Comprobante ingresado!!!');

    }
    public function listarcomprobantes($id)
    {
      $cuentacorrientetransporte=CtaCteTransporte::where('transporte_id',$id)->orderBy('id','DESC')->paginate(30);
      $transporte=Transporte::where('id',$id)->get();
      /*$cuentacorrientechofer->each(function($cuentacorrientechofer){
            $cuentacorrientechofer->ctactecho;

        });
*/

      return view('cuentascorrientes.transportes.listarcomprobantes')
      ->with('cuentacorrientetransporte',$cuentacorrientetransporte)
      ->with('transporte',$transporte);
   }
}
