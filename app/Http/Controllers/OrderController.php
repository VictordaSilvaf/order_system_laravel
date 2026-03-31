<?php

namespace App\Http\Controllers;

use App\Application\Order\UseCases\CreateOrderUseCase;
use App\Application\Order\UseCases\ListOrdersUseCase;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ListOrdersUseCase $useCase): JsonResponse
    {
        return response()->json(
            $useCase->execute()
        );
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
    public function store(Request $request, CreateOrderUseCase $useCase)
    {
        $orderId = $useCase->execute(
            $request->input('items', [])
        );

        return response()->json([
            'order_id' => $orderId
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $order = Order::query()
            ->with('items')
            ->where('id', $id)
            ->firstOrFail();

        return response()->json([
            'id' => $order->id,
            'status' => $order->status,
            'items' => $order->items,
            'created_at' => $order->created_at,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
