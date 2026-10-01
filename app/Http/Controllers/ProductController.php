<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // GET /api/products (Menampilkan semua data)
    public function index()
    {
        return response()->json(Product::all(), 200);
    }

    // POST /api/products (Menambah data + Validasi)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
        ]);

        $product = Product::create($request->all());
        return response()->json($product, 201);
    }

    // GET /api/products/{id} (Detail data)
    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }
        return response()->json($product, 200);
    }

    // PUT /api/products/{id} (Update data)
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $product->update($request->all());
        return response()->json($product, 200);
    }

    // DELETE /api/products/{id} (Hapus data)
    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $product->delete();
        return response()->json(['message' => 'Data berhasil dihapus'], 200);
    }
}
