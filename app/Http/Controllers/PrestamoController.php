<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Http\Requests\Prestamo\Create;
use App\Http\Requests\Prestamo\Read;
use App\Http\Requests\Prestamo\update;
use App\Http\Requests\Prestamo\Delete;

class PrestamoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( $id )
    {
        try {
            
            $prestamos = Prestamo::where('idCliente', '=', $id)
                    ->orderBy('created_at', 'desc')
                    ->get();

            $cliente = Cliente::find( $id );

            return view('prestamos.index',compact('cliente', 'prestamos'));

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
        
            $prestamo = Prestamo::create([

                'monto' => $request->monto,
                'nota' => $request->nota,
                'idCliente' => $request->cliente,

            ]);

            $cliente = Cliente::find( $request->cliente );

            if( $cliente && $cliente->id ){

                $deuda = is_numeric( $cliente->deuda) ? floatval( $cliente->deuda) : round( floatval( $cliente->deuda), 2);
                $monto = is_numeric( $request->monto) ? floatval( $request->monto) : round( floatval( $request->monto), 2);

                $deudaTotal = $deuda + $monto;

                $cliente = Cliente::where('id', '=', $request->cliente )
                        ->update([

                            'deuda' => $deudaTotal,

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
    public function show(Prestamo $prestamo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Prestamo $prestamo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Update $request)
    {
        try {
            
            $prestamoAnt = Prestamo::find( $request->id );

            $prestamo = Prestamo::where('id', '=', $request->id)
                    ->update([

                        'monto' => $request->monto,
                        'nota' => $request->nota,

                    ]);

            $idCliente = $prestamoAnt->idCliente;

            $cliente = Cliente::find( $idCliente );

            if( $cliente && $cliente->id ){

                $deuda = is_numeric( $cliente->deuda) ? floatval( $cliente->deuda) : round( floatval( $cliente->deuda), 2);
                $monto = is_numeric( $request->monto) ? floatval( $request->monto) : round( floatval( $request->monto), 2);

                $deudaTotal = $deuda - $prestamoAnt->monto;

                $deudaTotal = $deudaTotal + $request->monto;

                $cliente = Cliente::where('id', '=', $idCliente)
                        ->update([

                            'deuda' => $deudaTotal,

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

            $prestamo = Prestamo::find( $request->id );

            if( $prestamo->id ){

                $cliente = Cliente::find( $prestamo->idCliente );

                $deuda = is_numeric( $cliente->deuda) ? floatval( $cliente->deuda) : round( floatval( $cliente->deuda), 2);
                $monto = is_numeric( $prestamo->monto) ? floatval( $prestamo->monto) : round( floatval( $prestamo->monto), 2);

                $deudaTotal = $deuda - $monto;
            
                $cliente = Cliente::where('id', '=', $prestamo->idCliente)
                        ->update([

                            'deuda' => $deudaTotal,

                        ]);

                $prestamo->delete();

                $datos['exito'] = true;

            }

        } catch (\Throwable $th) {
            
            $datos['exito'] = false;
            $datos['mensaje'] = $th->getMessage();

        }

        return response()->json( $datos );
    }
}
