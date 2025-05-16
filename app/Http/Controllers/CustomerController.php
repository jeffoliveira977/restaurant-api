<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::all();
        return response()->json($customers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cpf' => 'required|string|unique:customers,cpf|max:14',
        ]);

        $customer = Customer::create($validated);

        return response()->json($customer, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        return response()->json($customer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'cpf' => 'sometimes|required|string|unique:customers,cpf,' . $customer->id . '|max:14',
        ]);

        $customer->update($validated);

        return response()->json($customer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return response()->json(null, 204);
    }

    /**
     * Get the customer's largest order.
     */
    public function largestOrder(Customer $customer)
    {
        $order = $customer->largestOrder();
        
        if (!$order) {
            return response()->json(['message' => 'No orders found'], 404);
        }

        return response()->json($order->load('items.menuItem'));
    }

    /**
     * Get the customer's first order.
     */
    public function firstOrder(Customer $customer)
    {
        $order = $customer->firstOrder();
        
        if (!$order) {
            return response()->json(['message' => 'No orders found'], 404);
        }

        return response()->json($order->load('items.menuItem'));
    }

    /**
     * Get the customer's latest order.
     */
    public function latestOrder(Customer $customer)
    {
        $order = $customer->latestOrder();
        
        if (!$order) {
            return response()->json(['message' => 'No orders found'], 404);
        }

        return response()->json($order->load('items.menuItem'));
    }
}