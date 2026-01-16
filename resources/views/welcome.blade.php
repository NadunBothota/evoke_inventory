<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Evoke Inventory</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">

    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">
                Evoke Inventory
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item">
                                <a href="{{ url('/dashboard') }}" class="nav-link">Dashboard</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="{{ route('login') }}" class="nav-link">Log in</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a href="{{ route('register') }}" class="btn btn-primary ms-2">Register</a>
                                </li>
                            @endif
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <main>
        <section class="py-5 text-center container">
            <div class="row py-lg-5">
                <div class="col-lg-6 col-md-8 mx-auto">
                    <h1 class="fw-light">Modern Inventory Management</h1>
                    <p class="lead text-muted">
                        Evoke Inventory provides a powerful and intuitive platform to track your assets, manage stock, and optimize your workflow with ease.
                    </p>
                    <p>
                        <a href="{{ route('register') }}" class="btn btn-primary my-2">Get Started</a>
                        <a href="#features" class="btn btn-secondary my-2">Learn More</a>
                    </p>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <div id="features" class="album py-5 bg-white">
            <div class="container">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                    <div class="col">
                        <div class="card shadow-sm">
                            <div class="card-body text-center">
                                <h3 class="card-title">Real-Time Tracking</h3>
                                <p class="card-text">Monitor your inventory levels live, preventing stockouts and overstocking before they happen.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card shadow-sm">
                            <div class="card-body text-center">
                                <h3 class="card-title">Insightful Reporting</h3>
                                <p class="card-text">Generate data-driven reports to make smart decisions and optimize your business operations.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card shadow-sm">
                            <div class="card-body text-center">
                                <h3 class="card-title">Intuitive Interface</h3>
                                <p class="card-text">A clean, user-friendly interface that requires minimal training for your entire team.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="text-muted py-5">
        <div class="container">
            <p class="float-end mb-1">
                <a href="#">Back to top</a>
            </p>
            <p class="mb-1">&copy; {{ date('Y') }} Evoke International. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>