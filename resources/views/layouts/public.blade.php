<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUBEB Yobe State | State Universal Basic Education Board</title>
    <meta name="description" content="Official website of the Yobe State Universal Basic Education Board (SUBEB). Promoting quality basic education in Yobe State.">
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('themes/public-site/css/style.css') }}">
</head>
<body>

    <!-- A) UTILITY BAR -->
    <div class="utility-bar">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-none d-md-block">
                <i class="bi bi-quote me-1"></i> Promoting Quality Basic Education in Yobe State
            </div>
            <div class="ms-auto">
                <span class="me-3"><i class="bi bi-telephone-fill me-1"></i> +234 800 SUBEB YOBE</span>
                <span class="me-3 d-none d-sm-inline"><i class="bi bi-envelope-fill me-1"></i> info@subeb.yb.gov.ng</span>
                <a href="#"><i class="bi bi-facebook"></i></a>
                <a href="#"><i class="bi bi-twitter-x"></i></a>
                <a href="#"><i class="bi bi-instagram"></i></a>
            </div>
        </div>
    </div>

    <!-- B) HEADER + NAVBAR -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <!-- Placeholder for Logo -->
                 <img src="{{ asset('assets/Yobe-SUBEB-logo.jpg') }}" alt="" height="40">
                <div class="d-flex flex-column">
                    <span style="line-height: 1.1;">SUBEB<br><small style="font-size: 0.7em; font-weight: 400;">YOBE STATE</small></span>
                </div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" href="#home">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#mandate">Mandate</a></li>
                    <li class="nav-item"><a class="nav-link" href="#leadership">Leadership</a></li>
                    <li class="nav-item"><a class="nav-link" href="#departments">Depts</a></li>
                    <li class="nav-item"><a class="nav-link" href="#needs">Needs Assessment</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                </ul>
                <a href="{{ route('login') }}" class="btn btn-portal btn-sm ms-2">
                    <i class="bi bi-lock-fill me-1"></i> Portal Login
                </a>
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- P) FOOTER -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5>SUBEB Yobe State</h5>
                    <p class="small">The State Universal Basic Education Board is committed to providing quality, accessible, and functional basic education to all children in Yobe State.</p>
                    <div class="mt-3">
                        <a href="#" class="me-2"><i class="bi bi-facebook fs-5"></i></a>
                        <a href="#" class="me-2"><i class="bi bi-twitter-x fs-5"></i></a>
                        <a href="#" class="me-2"><i class="bi bi-instagram fs-5"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-6 mb-4">
                    <h5>Quick Links</h5>
                    <ul>
                        <li><a href="#home">Home</a></li>
                        <li><a href="#about">About Us</a></li>
                        <li><a href="#mandate">Mandate</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-6 mb-4">
                    <h5>Portals</h5>
                    <ul>
                        <li><a href="{{ route('login') }}">School Login</a></li>
                        <li><a href="{{ route('login') }}">Staff Login</a></li>
                        <li><a href="#">Needs Assessment</a></li>
                        <li><a href="#">Check Result</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5>Newsletter</h5>
                    <p class="small">Subscribe to get the latest updates.</p>
                    <form class="input-group">
                        <input type="email" class="form-control" placeholder="Email Address">
                        <button class="btn btn-success" type="submit">Subscribe</button>
                    </form>
                </div>
            </div>
            <hr class="border-secondary">
            <div class="text-center small">
                &copy; 2025 Yobe State Universal Basic Education Board. All Rights Reserved.
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="{{ asset('themes/public-site/js/main.js') }}"></script>
</body>
</html>
