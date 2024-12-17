<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Solicitud;

class SolicitudController extends Controller
{
    public static function index(){
        $registros = Solicitud::all();
        return view('websocket.tabla',['registros' => $registros]);
    }

    public function aceptar($id){
        $reg = Solicitud::Find($id);
        $reg->estado = 1;
        $reg->save();
        return response()->JSON(['res' => 'ACK']);
    }

    public function rechazar($id){
        $reg = Solicitud::Find($id);
        $reg->estado = 0;
        $reg->save();
        return response()->JSON(['res' => 'ACK']);
    }

    public function actualizar($id){
        $reg = Solicitud::Find($id);
        //$reg->estado = 0;
        //$reg->save();
        return response()->JSON(['r' => $reg]);
    }


    public static function ver($id){
        $reg = Solicitud::Find($id);
        return view('websocket.ver',['r' => $reg]);
    }


    public function solicitud($dni){
        $solicitud = Solicitud::where('dni',$dni)->Get();
        if(!$solicitud){
            $solicitud = false;
        }
        return view('websocket.index',["solicitud" => $solicitud]);
    }
}
