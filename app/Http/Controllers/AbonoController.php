<?php

namespace App\Http\Controllers;

use App\Models\Abono;
use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Http\Requests\Abono\Create;
use App\Http\Requests\Abono\Read;
use App\Http\Requests\Abono\Update;
use App\Http\Requests\Abono\Delete;
use App\Models\Pedido;
use NumberFormatter;

class AbonoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( $id )
    {
        try {
            
            $abonos = Abono::where('idCliente', '=', $id)
                    ->orderBy('created_at', 'desc')
                    ->get();
            $cliente = Cliente::find( $id );

            return view('abonos.index',compact('cliente', 'abonos'));

        } catch (\Throwable $th) {
            
            echo $th->getMessage();

        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Create $request)
    {
        try {
        
            $abono = Abono::create([

                'monto' => $request->monto,
                'nota' => $request->nota,
                'idCliente' => $request->cliente,

            ]);

            $cliente = Cliente::find( $request->cliente );

            if( $cliente && $cliente->id ){

                $deuda = floatval($cliente->deuda) - floatval($request->monto);

                $deuda = number_format( $deuda, 2 );

                $cliente = Cliente::where('id', '=', $request->cliente )
                        ->update([

                            'deuda' => $deuda,

                        ]);

            }

            $datos['exito'] = true;

        } catch (\Throwable $th) {
            
            $datos['exito'] = false;
            $datos['mensaje'] = $th->getMessage();

        }

        return response()->json( $datos );
    }

    /**
     * Display the specified resource.
     */
    public function show(Abono $abono)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Abono $request)
    {
        try {
            

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Update $request)
    {
        try {
            
            $abonoAnt = Abono::find( $request->id );

            $abono = Abono::where('id', '=', $request->id)
                    ->update([

                        'monto' => $request->monto,
                        'nota' => $request->nota,

                    ]);

            $idCliente = $abonoAnt->idCliente;

            $cliente = Cliente::find( $idCliente );

            if( $cliente && $cliente->id ){

                $deuda = floatval($cliente->deuda) + floatval($abonoAnt->monto);

                $deuda = floatval($deuda) - floatval($request->monto);

                $deuda = number_format( $deuda, 2 );

                $cliente = Cliente::where('id', '=', $idCliente)
                        ->update([

                            'deuda' => $deuda,

                        ]);

            }

            $datos['exito'] = true;

        } catch (\Throwable $th) {
            
            $datos['exito'] = false;
            $datos['mensaje'] = $th->getMessage();

        }

        return response()->json( $datos );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Delete $request)
    {
        try {

            $abono = Abono::find( $request->id );

            if( $abono->id ){

                $cliente = Cliente::find( $abono->idCliente );

                $deuda = floatval($cliente->deuda) + floatval($abono->monto);

                $deuda = number_format( $deuda, 2 );
            
                $cliente = Cliente::where('id', '=', $abono->idCliente)
                        ->update([

                            'deuda' => $deuda,

                        ]);

                $abono->delete();

                $datos['exito'] = true;

            }

        } catch (\Throwable $th) {
            
            $datos['exito'] = false;
            $datos['mensaje'] = $th->getMessage();

        }

        return response()->json( $datos );
    }
}
