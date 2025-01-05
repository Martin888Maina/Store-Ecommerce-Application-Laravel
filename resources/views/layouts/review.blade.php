<!-- review.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title section -->
    <title>E-commerce - Final Cart Review</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Fontawesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Bootstrap icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            padding: 0;
            margin: 0;
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
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
        footer {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <!-- Navigation  Bar-->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('products.index') }}">Shop</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <!-- checks if user is authenticated. If so the following links are made available -->
                    @auth
                    <!-- navigation link to the index page -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('cart.index') }}">Cart</a>
                        </li>
                        <!-- logout button -->
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                <!-- csrf token that protects the user against cross site request forgery attacks -->
                                @csrf
                                <!-- logout -->
                                <button type="submit" class="btn btn-danger">Logout</button>
                            </form>
                        </li>
                        <!-- if user is not authenticated or is a guest user they are navigated to the login page -->
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- dynamic display of content -->
    <div class="container mt-4">
        @yield('content')
    </div>

    <!-- footer section -->
    <footer class="bg-light text-center py-3 mt-4">
        <p>&copy; 2024 E-commerce Site</p>
    </footer>

    <!-- bootstrap section -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
