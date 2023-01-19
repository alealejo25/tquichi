<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Proveedor;
use App\CtaCteP;

use Laracasts\Flash\Flash;

class CtaCtePController extends Controller
{
        public function __construct()
    {
        $this->middleware('auth');
    }
	public function index(Request $request)
    {
        
        $proveedores=Proveedor::search($request->name)->orderBy('nombre','ASC')->paginate(10);
        return view('cuentascorrientes.proveedores.index')
            ->with('proveedores',$proveedores);

    }


    public function nuevocomprobante($id){
		$proveedores=Proveedor::where('id',$id)->get();

		// $vales=Vale::where('flete_id',$id)->get();
		$cuentacorrienteproveedor=CtaCteP::where('proveedor_id',$id)->where('tipocomprobante','FACTURA')->pluck('nrocomprobante','id');
		return view('cuentascorrientes.proveedores.nuevocomprobante')
		 	->with('cuentacorrienteproveedor',$cuentacorrienteproveedor)
		 	->with('proveedores',$proveedores)
  			->with('id',$id);


    }

 public function guardarcomprobantep(Request $request,$id)
    {

        /*VALIDACION -----------------------------------------*/
        $campos=[
            'tipocomprobante'=>'required',
            'nrocomprobante'=>'required|unique:CtasCtesP',
            'fechaemision'=>'required',
            'fechavencimiento'=>'required',
            'importe'=>'required|numeric',
          'importesubtotal'=>'required|numeric'
           
        ];
        $Mensaje=["required"=>'El :attribute es requerido'];
        $this->validate($request,$campos,$Mensaje);

    	$acumulado=Proveedor::where('id',$id)->orderBy('id','DESC')->limit(1)->get();

        $datosComprobante=new CtaCteP(request()->except('_token'));
        $datosComprobante->proveedor_id=$id;

        switch ($request->tipocomprobante){
        	case 'FACTURA':
        		$datosComprobante->debe=$request->importe;
        		$datosComprobante->haber=0;
        		$datosComprobante->acumulado=$acumulado[0]->saldo + $request->importe;
                $datosComprobante->importesubtotal=$request->importesubtotal;
                $datosComprobante->iva=$request->iva;
                $datosComprobante->percepcioniva=$request->percepcioniva;
                $datosComprobante->ingresobruto=$request->ingresobruto;
                $datosComprobante->tem=$request->tem;
                $datosComprobante->ganancia=$request->ganancia;
               // $datosComprobante->importefinal=$request->importefinal;

        		$datosComprobante->factura_id=null;
        		$datosComprobante->save();
        		$editarcliente=Proveedor::where('id',$id)
                ->update([
                		'saldo'=>$datosComprobante->acumulado
                          ]);
        	break;

        	
        	case 'NOTA DE DEBITO':
        		$datosComprobante->debe=$request->importe;
        		$datosComprobante->haber=0;
        		$datosComprobante->acumulado=$acumulado[0]->saldo + $request->importe;
        		$datosComprobante->save();
        		$editarcliente=Proveedor::where('id',$id)
                ->update([
                		'saldo'=>$datosComprobante->acumulado
                          ]);

        	break;
        	case 'NOTA DE CREDITO':
        		$datosComprobante->debe=0;
        		$datosComprobante->haber=$request->importe;
        		$datosComprobante->acumulado=$acumulado[0]->saldo - $request->importe;
        		$datosComprobante->save();
				$editarcliente=Proveedor::where('id',$id)
                ->update([
                          'saldo'=>$datosComprobante->acumulado
                          ]);
        	break;
            case 'RECIBO':
                $datosComprobante->debe=$request->importe;
                $datosComprobante->haber=0;
                $datosComprobante->acumulado=$acumulado[0]->saldo + $request->importe;
                $datosComprobante->save();
                $editarcliente=Proveedor::where('id',$id)
                ->update([
                          'saldo'=>$datosComprobante->acumulado
                          ]);
            break;
        	case 'REMITO':
        		$datosComprobante->debe=0;
        		$datosComprobante->haber=$request->importe;
        		$datosComprobante->acumulado=$acumulado[0]->saldo - $request->importe;
        		$datosComprobante->save();
        		$editarcliente=Proveedor::where('id',$id)
                ->update([
                		'saldo'=>$datosComprobante->acumulado
                          ]);


        	break;

        }
   		flash::success('Comprobante ingresado!!! - Tipo '.$request->tipocomprobante. '-' .$request->nrocomprobante);
       return Redirect('cuentascorrientes/proveedores/')->with('Mensaje','Comprobante ingresado!!!');

    }

    public function listarcomprobantes($id)
    {
    	$cuentacorrienteproveedor=CtaCteP::where('proveedor_id',$id)->orderBy('id','DESC')->paginate(30);
    	$proveedor=Proveedor::where('id',$id)->get();


    	$cuentacorrienteproveedor->each(function($cuentacorrienteproveedor){
            $cuentacorrienteproveedor->ctactep;

        });


    	return view('cuentascorrientes.proveedores.listarcomprobantes')
		 	->with('cuentacorrienteproveedor',$cuentacorrienteproveedor)
		 	->with('proveedor',$proveedor);
   }

 public function anularcomprobantes($id)
    {$date = new \DateTime();

        $datoscomprobante=CtaCteP::where('id',$id)->get();
        $acumulado=Proveedor::where('id',$datoscomprobante[0]->proveedor_id)->orderBy('id','DESC')->limit(1)->get();
        $datosComprobante=new CtaCteP(request()->except('_token'));
        $datosComprobante->proveedor_id=$datoscomprobante[0]->proveedor_id;



        switch ($datoscomprobante[0]->tipocomprobante){
            case 'FACTURA':
                $datosComprobante->nrocomprobante=$datoscomprobante[0]->nrocomprobante.'/bis';
                $datosComprobante->fechaemision=$date;
                $datosComprobante->fechavencimiento=$date;
                $datosComprobante->debe=0;
                $datosComprobante->haber=$datoscomprobante[0]->debe;
                $datosComprobante->acumulado=$acumulado[0]->saldo - $datoscomprobante[0]->debe;
                $datosComprobante->importesubtotal=0;
                $datosComprobante->iva=0;
                $datosComprobante->percepcioniva=0;
                $datosComprobante->ingresobruto=0;
                $datosComprobante->tem=0;
                $datosComprobante->ganancia=0;
                
                $datosComprobante->tipocomprobante='ANULACION FACTURA';
                $datosComprobante->observacion='Anulacion por equivocación de carga';
               // $datosComprobante->importefinal=$request->importefinal;
                $datosComprobante->factura_id=$id;
                $datosComprobante->estado='REALIZADO';
                $datosComprobante->save();
                $editarcliente=Proveedor::where('id',$datoscomprobante[0]->proveedor_id)
                ->update([
                        'saldo'=>$datosComprobante->acumulado
                          ]);
                $editarcomprobante=CtaCteP::where('id',$id)
                ->update([
                        'estado'=>'ANULADO',
                          ]);
            break;

        }
        flash::success('Comprobante Anulado!!! - Tipo ');
       return Redirect('cuentascorrientes/proveedores/')->with('Mensaje','Comprobante ingresado!!!');
   }

}
