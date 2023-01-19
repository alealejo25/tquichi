<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Estacion;
use App\Flete;
use App\Chofer;
use App\Tarifa;
use App\Anticipo;
use App\Vale;
use App\GastoVarioFlete;
use App\MovimientoCaja;
use App\Camion;
use App\Cliente;
use App\CtaCteC;
use App\RemitoFlete;
use App\Transporte;
use App\Proveedor;
use App\Operacion;
use App\CtaCteTransporte;
use App\CtaCteP;



use Laracasts\Flash\Flash;
use Barryvdh\DomPDF\Facade as PDF;

use DB;


class OperacionController extends Controller
{
 


     public function index(Request $request)
    {
        $operaciones=Operacion::orderBy('id','DESC')->paginate(30);
        $operaciones->each(function($operaciones){
            $operaciones->transporte;
            $operaciones->proveedor;
            $operaciones->cliente;
            
        });
        return view('operaciones.index')
            ->with('operaciones',$operaciones);
    }
    public function create()
    {
        $transportes=Transporte::orderBy('nombre','ASC')->pluck('nombre','id');
        $proveedores=Proveedor::orderBy('nombre','ASC')->pluck('nombre','id');
        $clientes=Cliente::orderBy('nombre','ASC')->get();
        return view('operaciones.create')
            ->with('transportes',$transportes)
            ->with('clientes',$clientes)
            ->with('proveedores',$proveedores);
    }
    public function store(Request $request)
     {


        $date = new \DateTime();
        /*VALIDACION -----------------------------------------*/
        $campos=[
            'proveedor_id'=>'required',
            'transporte_id'=>'required',
            'dineroentregado'=>'required',
            'descripcion'=>'required',
            'precioconvenido'=>'required|numeric',
            'kilosprovistos'=>'required|numeric',
            'importepagadoproveedor'=>'required|numeric',
            'cliente_id'=>'required',
            'precioventa'=>'required|numeric',
            'kiloscliente'=>'required|numeric',  
            'importepagadocliente'=>'required|numeric',
            'importefletextonelada'=>'required',
            'rendicionflete'=>'required'
        ];
        $Mensaje=["required"=>'El :attribute es requerido'];
        $this->validate($request,$campos,$Mensaje);
        /*--------------------------------------------------------*/

        if($request->cliente_id1!=NULL){
            $campos=[
            'precioventa1'=>'required|numeric',
            'kiloscliente1'=>'required|numeric',  
            'importepagadocliente1'=>'required|numeric',
            
        ];
        $Mensaje=["required"=>'El :attribute es requerido'];
        $this->validate($request,$campos,$Mensaje);
        }

        $acumulado=Transporte::where('id',$request->transporte_id)->orderBy('id','DESC')->limit(1)->get();
        /*INICIO DE OPERACION*/
        $datosOperacion=new Operacion(request()->except('_token'));
        $datosOperacion->fechainicio=$date;
        $datosOperacion->estado='INICIADO';
        $datosOperacion->saldofleteoperacion=$request->dineroentregado;
        $datosOperacion->save();
        $operacion=Operacion::orderBy('id','DESC')->get();
        /*--------------------------------------------------------*/

        /*GRABAR EL COMPROBANTE DE ENTEGA DE DINERO AL TRANSPORTE*/
        $datosComprobante=new CtaCteTransporte(request()->except('_token'));

        $datosComprobante->tipocomprobante="ENTREGA INICIAL";
        $datosComprobante->transporte_id=$request->transporte_id;
        $datosComprobante->operacion_id=$operacion[0]->id;
        $datosComprobante->nrocomprobante="1111";
        $datosComprobante->fechaemision=$date;
        $datosComprobante->debe=0;
        $datosComprobante->haber=$request->dineroentregado;
        $datosComprobante->acumulado=$acumulado[0]->saldo + $request->dineroentregado;
        $datosComprobante->observacion="ENTREGA DE DINERO AL CHOFER";
        $datosComprobante->estado="EN CURSO";
        $datosComprobante->save();
        /*--------------------------------------------------------*/

        /*ACTUALIZA EL SALDO DE LA EMPRESA DE TRANSPORTE*/
        $actualizatransporte=Transporte::where('id',$request->transporte_id)
                ->update([
                    'saldo'=>$datosComprobante->acumulado
                          ]);
        /*--------------------------------------------------------*/

        $operacion=Operacion::orderBy('id','DESC')->get();
        $operaciones=Operacion::where('id',$operacion[0]->id)->paginate(30);
      


/////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////// MOVIMIENTO PROVEEDOR ---------------------------------------------------
        //////////////////////////////////////////////////////////////////////////////


        $datosOperacion=Operacion::where('id',$operacion[0]->id)->get();
        if($request->importepagadoproveedor==0)
        {
            $saldoproveedoroperacion=($request->precioconvenido*$request->kilosprovistos)/1000;
        }
        else
        {
            $saldoproveedoroperacion=(($request->precioconvenido*$request->kilosprovistos)/1000)-$request->importepagadoproveedor;
        }
        /*ACTUALIZA saldo del proveedor*/
        $datosproveedor=Proveedor::where('id',$datosOperacion[0]->proveedor_id)->get();
       
        /*--------------------------------------------------------*/

        /*ACTUALIZA OPERACIONES*/
        $actualizaroperaciones=Operacion::where('id',$operacion[0]->id)
                ->update([
                    'precioconvenido'=>$request->precioconvenido,
                    'kilosprovistos'=>$request->kilosprovistos,
                    'importepagadoproveedor'=>$request->importepagadoproveedor,
                    'importeconvenido'=>($request->precioconvenido*$request->kilosprovistos)/
                    1000,
                    'estado'=>'PROV',
                    'saldoproveedoroperacion'=>$saldoproveedoroperacion,
                    'saldofleteoperacion'=>$datosOperacion[0]->saldofleteoperacion-$request->importepagadoproveedor,
                     ]);
        /*--------------------------------------------------------*/
        /*GRABAR EL COMPROBANTE DE ENTEGA DE DINERO AL PROVEEDOR*/

        $acumulado=Proveedor::where('id',$datosOperacion[0]->proveedor_id)->orderBy('id','DESC')->limit(1)->get();
        $datosComprobante=new CtaCteP(request()->except('_token'));
        $datosComprobante->tipocomprobante="RECIBO";
        $datosComprobante->operacion_id=$operacion[0]->id;
        $datosComprobante->proveedor_id=$datosOperacion[0]->proveedor_id;
        $datosComprobante->nrocomprobante="1111";
        $datosComprobante->fechaemision=$date;
        $datosComprobante->debe=($request->precioconvenido*$request->kilosprovistos)/1000;
        $datosComprobante->haber=0;
        $datosComprobante->acumulado=$acumulado[0]->saldo + (($request->precioconvenido*$request->kilosprovistos)/1000);
        $datosComprobante->observacion="RECIBO X KILOS DE MAIZ";
        $datosComprobante->estado="EN CURSO";
        $datosComprobante->save();

        $acumulado1=Proveedor::where('id',$datosOperacion[0]->proveedor_id)->orderBy('id','DESC')->limit(1)->get();

        $datosComprobante1=new CtaCteP(request()->except('_token'));
        $datosComprobante1->tipocomprobante="REMITO";
        $datosComprobante1->operacion_id=$operacion[0]->id;
        $datosComprobante1->proveedor_id=$datosOperacion[0]->proveedor_id;
        $datosComprobante1->nrocomprobante="1111";
        $datosComprobante1->fechaemision=$date;
        $datosComprobante1->debe=0;
        $datosComprobante1->haber=$request->importepagadoproveedor;
        $datosComprobante1->acumulado=$datosComprobante->acumulado - $request->importepagadoproveedor;
        $datosComprobante1->observacion="PAGO DE DINERO DEL CHOFER AL PROVEEDOR";
        $datosComprobante1->estado="EN CURSO";
        $datosComprobante1->save();
        /*--------------------------------------------------------*/
                   /*ACTUALIZA saldo del proveedor*/
        $datosproveedor=Proveedor::where('id',$datosOperacion[0]->proveedor_id)->get();
        $actualizarsaldo=Proveedor::where('id',$datosOperacion[0]->proveedor_id)
                ->update([
                    'saldo'=>$datosComprobante1->acumulado,
                     ]);
        /*--------------------------------------------------------*/
        /*--------------------------------------------------------*/
        /*GRABAR EL COMPROBANTE DE PAGO AL PROVEEDOR*/

        $acumuladoT=Transporte::where('id',$datosOperacion[0]->transporte_id)->orderBy('id','DESC')->limit(1)->get();

        $datosComprobante2=new CtaCteTransporte();
        $datosComprobante2->tipocomprobante="PAGO AL PROVEEDOR";
        $datosComprobante2->operacion_id=$operacion[0]->id;
        $datosComprobante2->nrocomprobante="1111";
        $datosComprobante2->fechaemision=$date;
        $datosComprobante2->debe=$request->importepagadoproveedor;
        $datosComprobante2->haber=0;
        $datosComprobante2->acumulado=$acumuladoT[0]->saldo -$request->importepagadoproveedor;
        $datosComprobante2->observacion="PAGO DE DINERO DEL CHOFER AL PROVEEDOR";
        $datosComprobante2->estado="EN CURSO";
        $datosComprobante2->transporte_id=$datosOperacion[0]->transporte_id;
        $datosComprobante2->save();

                   /*ACTUALIZA saldo del transporte*/
        $datostransporte=Transporte::where('id',$datosOperacion[0]->transporte_id)->get();
        $actualizarsaldo=Transporte::where('id',$datosOperacion[0]->transporte_id)
                ->update([
                    'saldo'=>$datostransporte[0]->saldo-$request->importepagadoproveedor,
                     ]);


/////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////// FIN MOVIMIENTO PROVEEDOR ----------------------------------------------
        //////////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////// MOVIMIENTO CLIENTE ---------------------------------------------------
        //////////////////////////////////////////////////////////////////////////////
        $datosOperacion=Operacion::where('id',$operacion[0]->id)->get();

        if($request->importepagadocliente==0)
        {
            $saldoclienteoperacion=($request->precioventa*$request->kiloscliente)/1000;
        }
        else
        {
            $saldoclienteoperacion=(($request->precioventa*$request->kiloscliente)/1000)-$request->importepagadocliente;
        }
        /*ACTUALIZA saldo del proveedor*/
        $datoscliente=Cliente::where('id',$request->cliente_id)->get();

        /*--------------------------------------------------------*/

        /*ACTUALIZA OPERACIONES*/
        $actualizaroperaciones=Operacion::where('id',$operacion[0]->id)
                ->update([
                    'cliente_id'=>$request->cliente_id,
                    'precioventa'=>$request->precioventa,
                    'kiloscliente'=>$request->kiloscliente,
                    'importepagadocliente'=>$request->importepagadocliente,
                    'importeventa'=>($request->precioventa*$request->kiloscliente)/1000,
                    'saldoclienteoperacion'=>$saldoclienteoperacion,
                    'kilosdiferencia'=>$request->kiloscliente-$datosOperacion[0]->kilosprovistos,
                    'saldofleteoperacion'=>$datosOperacion[0]->saldofleteoperacion+$request->importepagadocliente,
                     ]);
        /*--------------------------------------------------------*/
/*GRABAR EL COMPROBANTE DE ENTEGA DE DINERO AL PROVEEDOR*/

        $acumulado=Cliente::where('id',$request->cliente_id)->orderBy('id','DESC')->limit(1)->get();

        $datosComprobante=new CtaCteC();
        $datosComprobante->tipocomprobante="REMITO";
        $datosComprobante->operacion_id=$operacion[0]->id;
        $datosComprobante->cliente_id=$request->cliente_id;
        $datosComprobante->nrocomprobante="1111";
        $datosComprobante->fechaemision=$date;
        $datosComprobante->debe=0;
        $datosComprobante->haber=($request->precioventa*$request->kiloscliente)/1000;
        $datosComprobante->acumulado=$acumulado[0]->saldo + (($request->precioventa*$request->kiloscliente)/1000);
        $datosComprobante->observacion="REMITO X KILOS DE MAIZ ENTREGADOS AL CLIENTE";
        $datosComprobante->estado="EN CURSO";
        $datosComprobante->save();

        $acumulado1=Cliente::where('id',$request->cliente_id)->orderBy('id','DESC')->limit(1)->get();

        $datosComprobante1=new CtaCteC(request()->except('_token'));
        $datosComprobante1->tipocomprobante="RECIBO";

        $datosComprobante1->cliente_id=$request->cliente_id;
        $datosComprobante1->operacion_id=$operacion[0]->id;
        $datosComprobante1->nrocomprobante="1111";
        $datosComprobante1->fechaemision=$date;
        $datosComprobante1->debe=$request->importepagadocliente;
        $datosComprobante1->haber=0;
        $datosComprobante1->acumulado=$datosComprobante->acumulado - $request->importepagadocliente;
        $datosComprobante1->observacion="PAGO DE DINERO DEL CLIENTE AL CHOFER";
        $datosComprobante1->estado="EN CURSO";
        $datosComprobante1->save();
        /*--------------------------------------------------------*/
             /*ACTUALIZA saldo del proveedor*/
        $datoscliente=Cliente::where('id',$request->cliente_id)->get();
        $actualizarsaldo=Cliente::where('id',$request->cliente_id)
                ->update([
                    'saldo'=>$datosComprobante1->acumulado
                     ]);
        /*--------------------------------------------------------*/
        /*GRABAR EL COMPROBANTE DE ENTEGA DE DINERO AL TRANSPORTE*/
        $datosComprobante2=new CtaCteTransporte(request()->except('_token'));
        $acumuladoT=Transporte::where('id',$datosOperacion[0]->transporte_id)->orderBy('id','DESC')->limit(1)->get();

        $datosComprobante2->tipocomprobante="PAGO CLIENTE";
        $datosComprobante2->operacion_id=$operacion[0]->id;
        $datosComprobante2->transporte_id=$datosOperacion[0]->transporte_id;
        $datosComprobante2->nrocomprobante="1111";
        $datosComprobante2->fechaemision=$date;
        $datosComprobante2->debe=0;
        $datosComprobante2->haber=$request->importepagadocliente;
        $datosComprobante2->acumulado=$acumuladoT[0]->saldo + $request->importepagadocliente;
        $datosComprobante2->observacion="PAGO DEL CLIENTE AL CHOFER";
        $datosComprobante2->estado="EN CURSO";

        $datosComprobante2->save();
        /*--------------------------------------------------------*/


                     /*ACTUALIZA saldo del transporte*/
        $datostransporte=Transporte::where('id',$datosOperacion[0]->transporte_id)->get();
        $actualizarsaldo=Transporte::where('id',$datosOperacion[0]->transporte_id)
                ->update([
                    'saldo'=>$datostransporte[0]->saldo+$request->importepagadocliente,
                     ]);

/////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////// FIN MOVIMIENTO CLIENTE ----------------------------------------------
        //////////////////////////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////// MOVIMIENTO FLETE ---------------------------------------------------
        //////////////////////////////////////////////////////////////////////////////

 /*--------------------------------------------------------*/
        $datosOperacion=Operacion::where('id',$operacion[0]->id)->get();
        /*--------------------------------------------------------*/

        /*ACTUALIZA OPERACIONES*/
        $actualizaroperaciones=Operacion::where('id',$operacion[0]->id)
                ->update([
                    'importefletextonelada'=>$request->importefletextonelada,
                    'totalimporteflete'=>($request->importefletextonelada*$datosOperacion[0]->kilosprovistos)/1000,
                    'ganancia'=>$datosOperacion[0]->importeventa-(($request->importefletextonelada*$datosOperacion[0]->kilosprovistos)/1000)-($datosOperacion[0]->importeconvenido),
                    'acumulado'=>($datosOperacion[0]->importeventa-(($request->importefletextonelada*$datosOperacion[0]->kilosprovistos)/1000)-($datosOperacion[0]->importeconvenido))+$datosOperacion[0]->acumulado,
                      ]);
        /*--------------------------------------------------------*/

         /*--------------------------------------------------------*/
        /*GRABAR EL COMPROBANTE DE ENTEGA DE DINERO AL TRANSPORTE*/
        $datosComprobante2=new CtaCteTransporte();
        $acumuladoT=Transporte::where('id',$datosOperacion[0]->transporte_id)->orderBy('id','DESC')->limit(1)->get();

        $datosComprobante2->tipocomprobante="VALOR FLETE PAGO";
        $datosComprobante2->operacion_id=$operacion[0]->id;
        $datosComprobante2->transporte_id=$datosOperacion[0]->transporte_id;
        $datosComprobante2->nrocomprobante="1111";
        $datosComprobante2->fechaemision=$date;
        $datosComprobante2->debe=($request->importefletextonelada*$datosOperacion[0]->kilosprovistos)/1000;
        $datosComprobante2->haber=0;
        $datosComprobante2->acumulado=$acumuladoT[0]->saldo - ($request->importefletextonelada*$datosOperacion[0]->kilosprovistos)/1000;
        $datosComprobante2->observacion="TOTAL DEL FLETE";
        $datosComprobante2->estado="EN CURSO";

        $datosComprobante2->save();
        /*--------------------------------------------------------*/
               /*ACTUALIZA saldo del transporte*/
        $datostransporte=Transporte::where('id',$datosOperacion[0]->transporte_id)->get();
        $actualizarsaldo=Transporte::where('id',$datosOperacion[0]->transporte_id)
                ->update([
                    'saldo'=>$datostransporte[0]->saldo-(($request->importefletextonelada*$datosOperacion[0]->kilosprovistos)/1000)
                     ]);

        /*--------------------------------------------------------*/
/////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////// FIN MOVIMIENTO FLETE ----------------------------------------------
        //////////////////////////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////// RENDICION ---------------------------------------------------
        //////////////////////////////////////////////////////////////////////////////
 /*--------------------------------------------------------*/
        $datosOperacion=Operacion::where('id',$operacion[0]->id)->get();
        /*--------------------------------------------------------*/

        /*ACTUALIZA OPERACIONES*/
        $actualizaroperaciones=Operacion::where('id',$operacion[0]->id)
                ->update([
                    'rendicionflete'=>$request->rendicionflete,
                    'saldofleteoperacion'=>$datosOperacion[0]->saldofleteoperacion-$request->rendicionflete
                       ]);
        /*--------------------------------------------------------*/
        /*GRABAR EL COMPROBANTE DE ENTEGA DE DINERO AL TRANSPORTE*/
        $datosComprobante2=new CtaCteTransporte();
        $acumuladoT=Transporte::where('id',$datosOperacion[0]->transporte_id)->orderBy('id','DESC')->limit(1)->get();

        $datosComprobante2->tipocomprobante="RENDICION FLETE";
        $datosComprobante2->operacion_id=$operacion[0]->id;
        $datosComprobante2->transporte_id=$datosOperacion[0]->transporte_id;
        $datosComprobante2->nrocomprobante="1111";
        $datosComprobante2->fechaemision=$date;
        $datosComprobante2->debe=$request->rendicionflete;
        $datosComprobante2->haber=0;
        $datosComprobante2->acumulado=$acumuladoT[0]->saldo - $request->rendicionflete;
        $datosComprobante2->observacion="RENDICION FLETE";
        $datosComprobante2->estado="EN CURSO";

        $datosComprobante2->save();
        /*--------------------------------------------------------*/
        /*--------------------------------------------------------*/
        /*ACTUALIZA saldo del transporte*/
        $datostransporte=Transporte::where('id',$datosOperacion[0]->transporte_id)->get();
        $actualizarsaldo=Transporte::where('id',$datosOperacion[0]->transporte_id)
                ->update([
                    'saldo'=>$datostransporte[0]->saldo-$request->rendicionflete
                     ]);

/////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////// FIN RENDICION ----------------------------------------------
        //////////////////////////////////////////////////////////////////////////////

        $operaciones->each(function($operaciones){
            $operaciones->transporte;
            $operaciones->proveedor;
            $operaciones->cliente;
            
        });
        return view('operaciones.index')
            ->with('operaciones',$operaciones);
  
    
        // FIN DE GUARDAR MOVIMIENTO DE CAJA----------------
    }


    //ANULACION DE OPERACION
    public function anular($id)
    { 
        $date = new \DateTime();
        $operacion=Operacion::where('id',$id)->get();
        $transporte_id=$operacion[0]->transporte_id;
        $proveedor_id=$operacion[0]->proveedor_id;
        $cliente_id=$operacion[0]->cliente_id;

        $datostransporte=Transporte::where('id',$transporte_id)->get();
        $datosproveedor=Proveedor::where('id',$proveedor_id)->get();
        $datoscliente=Cliente::where('id',$cliente_id)->get();
       

        /*ACTUALIZAR LA OPERACION*/
        $actualizaoperacion=Operacion::where('id',$id)
                ->update([
                    'descripcion'=>$operacion[0]->descripcion.'/ANULACION POR CARGA ERRONEA',
                    'estado'=>'ANULADO'
                          ]);
        /*--------------------------------------------------------*/
        
/* TRANSPORTES *///*///////
        /*ACTUALIZAR ANULACION LOS COMPROBATES DE CTASCTES TRANSPORTES*/
        $actualizactactetra=CtaCteTransporte::where('operacion_id',$id)
                ->update([
                            'estado'=>'ANULADO',
                            'observacion'=>'ANULACION DE COMPROBANTES'
                          ]);
        /*--------------------------------------------------------*/

        /*COMPROBANTES NUEVOS PARA ANULAR Y QUEDE EL VALOR ACUMULADO CORRESPONDIENTE*/
        $debectatransporte=CtaCteTransporte::where('operacion_id',$id)->sum('debe');
        $haberctatransporte=CtaCteTransporte::where('operacion_id',$id)->sum('haber');
        /*ACTUALIZAR SALDO DE TRANSPORTE PARA LA ANULACION*/
        $saldotransporte=($datostransporte[0]->saldo)+$debectatransporte-$haberctatransporte;
        $actualizasaldo=Transporte::where('id',$transporte_id)
                ->update([
                            'saldo'=>$saldotransporte
                          ]);
        /*--------------------------------------------------------*/

/*--------------------------------------------------------------------------------------------------*/

/* CLIENTE *///*///////
        /*ACTUALIZAR ANULACION LOS COMPROBATES DE CTASCTES CLIENTES*/
        $actualizactactecli=CtaCteC::where('operacion_id',$id)
                ->update([
                            'estado'=>'ANULADO',
                            'observacion'=>'ANULACION DE COMPROBANTES'
                          ]);
        /*--------------------------------------------------------*/

        /*COMPROBANTES NUEVOS PARA ANULAR Y QUEDE EL VALOR ACUMULADO CORRESPONDIENTE*/
        $debectacliente=CtaCteC::where('operacion_id',$id)->sum('debe');

        $haberctacliente=CtaCteC::where('operacion_id',$id)->sum('haber');

        /*ACTUALIZAR SALDO DE TRANSPORTE PARA LA ANULACION*/
        $saldocliente=($datoscliente[0]->saldo)+$debectacliente-$haberctacliente;
        $actualizasaldo=Cliente::where('id',$cliente_id)
                ->update([
                            'saldo'=>$saldocliente
                          ]);
        /*--------------------------------------------------------*/

/*--------------------------------------------------------------------------------------------------*/

/* PROVEEDORES *///*///////
        /*ACTUALIZAR ANULACION LOS COMPROBATES DE CTASCTES PROVEEDOR*/
        $actualizactactepro=CtaCteP::where('operacion_id',$id)
                ->update([
                            'estado'=>'ANULADO',
                            'observacion'=>'ANULACION DE COMPROBANTES'
                          ]);
        /*--------------------------------------------------------*/

        /*COMPROBANTES NUEVOS PARA ANULAR Y QUEDE EL VALOR ACUMULADO CORRESPONDIENTE*/
        $debectaproveedor=CtaCteP::where('operacion_id',$id)->sum('debe');
        $haberctaproveedor=CtaCteP::where('operacion_id',$id)->sum('haber');

        /*ACTUALIZAR SALDO DE TRANSPORTE PARA LA ANULACION*/
        $saldoproveedor=($datosproveedor[0]->saldo)+$debectaproveedor-$haberctaproveedor;
        $actualizasaldo=Cliente::where('id',$proveedor_id)
                ->update([
                            'saldo'=>$saldoproveedor
                          ]);
        /*--------------------------------------------------------*/

/*--------------------------------------------------------------------------------------------------*/

            
    }


public function detalles($id)
    { 
        dd($id);
    }


}
