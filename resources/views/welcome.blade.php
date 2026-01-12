
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inventory Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f8f9fa;
        }
        .navbar {
            padding: 1rem 2rem;
            background-color: #fff;
            border-bottom: 1px solid #dee2e6;
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }
        .hero-section {
            padding: 5rem 2rem;
            text-align: center;
            background-color: #fff;
        }
        .hero-section h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        .hero-section p {
            font-size: 1.25rem;
            color: #6c757d;
            margin-bottom: 2rem;
        }
        .features-section {
            padding: 5rem 2rem;
        }
        .feature-card {
            border: none;
            border-radius: 0.5rem;
            padding: 2rem;
            text-align: center;
            background-color: #fff;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            transition: transform 0.3s;
        }
        .feature-card:hover {
            transform: translateY(-0.5rem);
        }
        .footer {
            padding: 2rem;
            text-align: center;
            background-color: #343a40;
            color: #fff;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">InventoryPro</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                @if (Route::has('login'))
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/dashboard') }}">Dashboard</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Log in</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">Register</a>
                            </li>
                        @endif
                    @endauth
                @endif
            </ul>
        </div>
    </div>
</nav>

<section class="hero-section">
    <div class="container">
        <h1>Streamline Your Inventory Management</h1>
        <p>A powerful and intuitive system to manage your products, track stock levels, and boost your business efficiency.</p>
        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Get Started</a>
    </div>
section>

<section class="features-section">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="feature-card">
                    <h3>Real-Time Tracking</h3>
                    <p>Monitor your inventory levels in real-time to prevent stockouts and overstocking.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-card">
                    <h3>Automated Reporting</h3>
                    <p>Generate insightful reports to make data-driven decisions and optimize your operations.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-card">
                    <h3>User-Friendly Interface</h3>
                    <p>An intuitive and easy-to-use interface that requires minimal training for your team.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="footer">
    <div class="container">
        <p>&copy; 2024 InventoryPro. All rights reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
