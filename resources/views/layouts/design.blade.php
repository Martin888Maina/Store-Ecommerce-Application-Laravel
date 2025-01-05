<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Page</title>
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 0;
            margin: 0;
        }
        .checkout-container {
            max-width: 100%;
            margin: 0;
            padding: 20px;
        }
        .checkout-header {
            margin-bottom: 30px;
        }
        .cart-details img {
            width: 100px;
            height: auto;
            margin-right: 15px;
        }
        .cart-details .list-group-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .cart-total {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Bootstrap styling -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('products.index') }}">Shop</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <!-- ensure user is authenticated/logged in to access these links -->
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('cart.index') }}">Cart</a>
                        </li>
                        <li class="nav-item">
                            <!-- form method for submitting data -->
                            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                <!-- protects the user from cross site request forgery attacks -->
                                @csrf
                                <!-- logout button -->
                                <button type="submit" class="btn btn-danger">Logout</button>
                            </form>
                        </li>
                        <!-- login navigation -->
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- dynamic display of data -->
    <div class="checkout-container">
        @yield('content')
    </div>

    <!-- footer section -->
    <footer class="bg-light text-center py-3 mt-4">
        <p>&copy; 2024 E-commerce Site</p>
    </footer>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
