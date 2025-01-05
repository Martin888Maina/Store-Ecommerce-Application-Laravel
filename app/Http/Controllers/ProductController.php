<?php
//namespace
namespace App\Http\Controllers;
// importing the product model
use App\Models\Product;
//Handles http requests in laravel(forms) adding products to the database
use Illuminate\Http\Request;
//import the storage facade
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index()
    {
        //eloquent method
        //handles pagination in the landing page
        // 4 products displayed horizontally and 5 products displayed vertically
        $products = Product::paginate(20);
        //returns the landing page
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {

        //validates inputs
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle the image upload
        // images are stored in the storage/products folder in the public folder
        $imagePath = $request->file('image')->store('products', 'public');
        // dd($imagePath);


        //associative array
        //creates a new product OBJECT in the database
        Product::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'price' => $validatedData['price'],
            'image' => $imagePath,
        ]);

        //returns the index page
        return redirect()->route('products.index')->with('success', 'Product added successfully!');
    }

    /**
     * Display the specified product.
     */
    public function show($id)
    {
        //eloquent method to find a product record in the database
        $product = Product::findOrFail($id);
        //returns the show page
        return view('products.show', compact('product'));
    }
}
