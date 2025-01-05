<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 0;
            margin: 0;
            font-family: 'Arial', sans-serif;
            background-color: #f0f8ff;
        }

        .auth-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
            width: 100%;
            max-width: 400px;
        }

        .card-body {
            padding: 2rem;
        }

        h3 {
            text-align: center;
            color: #2c3e50;
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-size: 1rem;
            color: #34495e;
        }

        .form-control {
            border-radius: 25px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
            padding: 0.75rem;
        }

        .btn-primary {
            background-color: #3498db;
            border-color: #3498db;
            padding: 0.75rem 1.25rem;
            border-radius: 25px;
            font-size: 1.1rem;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }

        .alert {
            margin-top: 1rem;
            font-size: 1rem;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            padding: 1rem;
            background-color: #ecf0f1;
            text-align: center;
            color: #7f8c8d;
            font-size: 0.9rem;
        }

    </style>
</head>
<body>

    <div class="auth-container">
        <!-- bootstrap styling -->
        <div class="card">
            <div class="card-body">
                <h3>Forgot Your Password?</h3>
                <!-- form method to submit data -->
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <!-- email input section -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Enter Your Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control" required>
                    </div>

                    <!-- button section -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-lg">Send Password Reset Link</button>
                    </div>
                </form>

                <!-- displays status message -->
                @if (session('status'))
                    <div class="alert alert-success mt-3">
                        {{ session('status') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- footer section -->
    <footer class="footer">
        <p>&copy; 2024 Ecommerce</p>
    </footer>

    <!-- bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
