<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/frontend.css">
    <title>Laravel</title>
</head>
<body class="{{ isset($_COOKIE['skin']) ? $_COOKIE['skin'] : '' }} antialiased">
<div id="header">
    <header class="container">
        <div class="row">
            <div class="d-inline-block">
                <a href="/">
                    <h1 class="logo">
                        <span>π</span>
                        <span>MATH<br>MPR</span>
                    </h1>
                </a>
            </div>
            <div class="col">
                <nav>
                    <ul>
                        <li><a href="#">Home</a></li>
                        <li><a href="#">Home</a></li>
                        <li><a href="#">Home</a></li>
                        <li>
                            <i id="readable" class="fa-regular {{ isset($_COOKIE['skin']) && $_COOKIE['skin'] == 'dark' ? 'fa-sun' : 'fa-moon' }}"></i>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
</div>
<main>
    <div class="container">
        <div class="row">
            <div class="col-lg-6">k</div>
            <div class="col-lg-6">
                <div class="row">
                    <div class="col-lg-6">a</div>
                    <div class="col-lg-6">b</div>
                    <div class="col-lg-6">c</div>
                    <div class="col-lg-6">d</div>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <a href="/">
            <h2 class="logo">
                <span>π</span>
                <span>MATH<br>MPR</span>
            </h2>
        </a>
        <p>© 2022 Mathmpr. None of the rights reserved.</p>
        <ul>
            <li>
                <a href="https://www.linkedin.com/in/mathmpr/" target="_blank">
                    <i class="fa-brands fa-linkedin-in"></i>
                </a>
            </li>
            <li>
                <a href="https://github.com/mathmpr" target="_blank">
                    <i class="fa-brands fa-github-alt"></i>
                </a>
            </li>
            <li>
                <a href="https://fb.com/mathmpr" target="_blank">
                    <i class="fa-brands fa-facebook-f"></i>
                </a>
            </li>
            <li>
                <a href="https://instagram.com/mathmpr/" target="_blank">
                    <i class="fa-brands fa-instagram"></i>
                </a>
            </li>
        </ul>
    </footer>
</main>
<script src="js/app.js"></script>
</body>
</html>
