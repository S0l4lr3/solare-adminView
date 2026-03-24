<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    // Estados válidos — en un solo lugar para no repetirlos
    const ESTADOS_PAGO  = ['pendiente', 'pagado', 'fallido', 'reembolsado'];
    const ESTADOS_ENVIO = ['pendiente', 'preparando', 'enviado', 'entregado', 'cancelado'];

    public function index(Request $request)
    {
        $busqueda     = $request->input('busqueda');
        $estadoPago   = $request->input('estado_pago');
        $estadoEnvio  = $request->input('estado_envio');

        $pedidos = Pedido::with(['cliente.usuario', 'direccionEnvio'])
            ->when($busqueda, function ($q) use ($busqueda) {
                $q->where(function ($q2) use ($busqueda) {
                    if (is_numeric($busqueda)) {
                        $q2->where('id', $busqueda);
                    }
                    $q2->orWhereHas('cliente.usuario', function ($q3) use ($busqueda) {
                        $q3->where('nombre', 'LIKE', "%{$busqueda}%")
                           ->orWhere('apellido_paterno', 'LIKE', "%{$busqueda}%");
                    });
                });
            })
            ->when($estadoPago, fn($q) => $q->where('estado_pago', $estadoPago))
            ->when($estadoEnvio, fn($q) => $q->where('estado_envio', $estadoEnvio))
            ->orderBy('creado_en', 'desc')
            ->get();

        return view('/pedidos/pedidos', compact('pedidos', 'busqueda', 'estadoPago', 'estadoEnvio'));
    }

    public function updateEstado(Request $request, $id)
    {
        $request->validate([
            'estado_pago'  => 'nullable|in:pendiente,pagado,fallido,reembolsado',
            'estado_envio' => 'nullable|in:pendiente,preparando,enviado,entregado,cancelado',
        ]);

        $pedido = Pedido::findOrFail($id);

        if ($request->filled('estado_pago')) {
            $pedido->estado_pago = $request->estado_pago;
        }
        if ($request->filled('estado_envio')) {
            $pedido->estado_envio = $request->estado_envio;
        }

        $pedido->save();

        return redirect()->route('pedidos.index');
    }

    public function destroy($id)
    {
        Pedido::findOrFail($id)->delete();
        return redirect()->route('pedidos.index');
    }
}