<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Evoke Inventory</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;800&display=swap" rel="stylesheet">
</head>
<body class="landing-page">
    <nav class="navbar navbar-expand-lg navbar-light bg-white py-3">
        <div class="container-fluid px-5">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="{{ asset('images/evoke_logo.jpg') }}" alt="Evoke Inventory" class="me-2">
            </a>
            <div class="collapse navbar-collapse justify-content-end">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Log in</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary" href="{{ route('login') }}">Access System</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <section class="hero-section">
            <div class="container">
                <span class="badge">EVOKE INTERNATIONAL</span>
                <h1>Inventory Management System</h1>
                <p>A comprehensive inventory management platform built exclusively for Evoke International. Track, manage, and optimize all company assets with real-time monitoring and reporting.</p>
                <div>
                    <a href="#" class="btn btn-primary btn-lg">Get Started</a>
                </div>
            </div>
        </section>

        <section class="stats-section text-center py-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card p-4">
                            <h2>500+</h2>
                            <p>Assets Tracked</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card p-4">
                            <h2>7</h2>
                            <p>Departments</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card p-4">
                            <h2>50+</h2>
                            <p>Active Users</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card p-4">
                            <h2>99.9%</h2>
                            <p>Uptime</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="features-section text-center">
            <div class="container">
                <h2>System Features</h2>
                <p class="lead text-muted">Built specifically for Evoke International's operational needs</p>
                <div class="row mt-5">
                    <div class="col-md-4">
                        <div class="card border-orange">
                            <div class="icon-container bg-orange">
                                <img src="{{ asset('images/Real-Time Asset Tracking.png') }}" alt="Real-Time Asset Tracking" class="card-icon">
                            </div>
                            <h5>Real-Time Asset Tracking</h5>
                            <p>Monitor all company assets in real-time. Track equipment location, status, and usage across all departments with instant updates.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-dark">
                            <div class="icon-container bg-gray">
                                <img src="{{ asset('images/Department Management.png') }}" alt="Department Management" class="card-icon">
                            </div>
                            <h5>Department Management</h5>
                            <p>Organize assets by department with custom workflows. Assign equipment, track usage, and maintain accountability across the organization.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-orange">
                            <div class="icon-container bg-orange">
                                <img src="{{ asset('images/User Access Control.png') }}" alt="User Access Control" class="card-icon">
                            </div>
                            <h5>User Access Control</h5>
                            <p>Role-based permissions ensure secure access. Administrators can manage users, assign roles, and control system access levels.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="access-section text-center my-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <img src="https://i.imgur.com/gW8cM5a.png" alt="" height="50">
                        <h2>Authorized Access Only</h2>
                        <p>This system is exclusively for Evoke International employees. Please use your company credentials to access the platform.</p>
                        <a href="{{ route('login') }}" class="btn btn-light btn-lg">Login with Company Account</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="py-4">
        <div class="container d-flex justify-content-between">
            <div>&copy; 2026 Evoke International. All rights reserved. <br> Internal Use Only - Confidential System</div>
            <div>
                <a href="#" class="me-3">Support</a>
                <a href="#" class="me-3">Documentation</a>
                <a href="#">Contact IT</a>
            </div>
        </div>
    </footer>
</body>
</html>
