<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- allows for custom titles -->
    <title>@yield('title', 'E-commerce App')</title>
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- fontawesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f8fa;
        }
        .card {
            border: none;
            background-color: #ffffff;
        }
        .btn-lg {
            font-size: 18px;
            padding: 12px 20px;
        }
        .form-control-lg {
            font-size: 16px;
            padding: 12px;
        }
        .form-label {
            font-weight: 500;
        }
        .container {
            max-width: 800px;
        }
        footer {
            background-color: #f8f9fa;
        }
    </style>
    <!-- allows for addition of custom styles without modifying the file -->
    @stack('styles')
</head>
<body>
    <!-- navigation bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('products.index') }}">Shop</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <!-- auth checks if user is authenticated. If so the following links are visible to them -->
                    @auth
                    <!-- cart page navigation link -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('cart.index') }}">Cart</a>
                        </li>
                        <!-- form method to submit data -->
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                <!-- csrf token to protect users against Cross Site Request Forgery Attacks -->
                                @csrf
                                <button type="submit" class="btn btn-danger">Logout</button>
                            </form>
                        </li>
                        <!-- login page navigation link for guest users -->
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

    <!-- Bootstrap  -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <!-- allows for custom javascript -->
    @stack('scripts') 
</body>
</html>
