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
                        <li><a href="#">Sobre</a></li>
                        <li><a href="#">Currículo</a></li>
                        <li><a href="#">Contato</a></li>
                        <li>
                            <i id="readable"
                               class="fa-solid {{ isset($_COOKIE['skin']) && $_COOKIE['skin'] == 'dark' ? 'fa-sun' : 'fa-moon' }}"></i>
                            <div class="search">
                                <input type="text" name="search" placeholder="Procurar...">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </div>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
</div>
<main>
    <div class="home container">
        <div class="row">
            <div class="col-lg-6 prefer">
                <img src="https://picsum.photos/800/800?e" alt="picsum">
                <a href="#">
                    <h3>We help brands connect with people</h3>
                    <p>Let's see why you need to go digital. We will study how to improve your perception and rebuild
                        your image.</p>
                    <em></em>
                </a>
            </div>
            <div class="col-lg-6">
                <div class="row">
                    <div class="col-lg-6">
                        <img src="https://picsum.photos/800/800?a" alt="picsum">
                        <a href="#">
                            <h3>We help brands connect with people</h3>
                            <p>Let's see why you need to go digital. We will study how to improve your perception and
                                rebuild your image.</p>
                            <em></em>
                        </a>
                    </div>
                    <div class="col-lg-6">
                        <img src="https://picsum.photos/800/800?b" alt="picsum">
                        <a href="#">
                            <h3>We help brands connect with people</h3>
                            <p>Let's see why you need to go digital. We will study how to improve your perception and
                                rebuild your image.</p>
                            <em></em>
                        </a>
                    </div>
                    <div class="col-lg-6">
                        <img src="https://picsum.photos/800/800?c" alt="picsum">
                        <a href="#">
                            <h3>We help brands connect with people</h3>
                            <p>Let's see why you need to go digital. We will study how to improve your perception and
                                rebuild your image.</p>
                            <em></em>
                        </a>
                    </div>
                    <div class="col-lg-6">
                        <img src="https://picsum.photos/800/800?d" alt="picsum">
                        <a href="#">
                            <h3>We help brands connect with people</h3>
                            <p>Let's see why you need to go digital. We will study how to improve your perception and
                                rebuild your image.</p>
                            <em></em>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <img src="https://picsum.photos/800/800?f" alt="picsum">
                <a href="#">
                    <h3>We help brands connect with people</h3>
                    <p>Let's see why you need to go digital. We will study how to improve your perception and rebuild
                        your image.</p>
                    <em></em>
                </a>
            </div>
            <div class="col-lg-3">
                <img src="https://picsum.photos/800/800?g" alt="picsum">
                <a href="#">
                    <h3>We help brands connect with people</h3>
                    <p>Let's see why you need to go digital. We will study how to improve your perception and rebuild
                        your image.</p>
                    <em></em>
                </a>
            </div>
            <div class="col-lg-3">
                <img src="https://picsum.photos/800/800?j" alt="picsum">
                <a href="#">
                    <h3>We help brands connect with people</h3>
                    <p>Let's see why you need to go digital. We will study how to improve your perception and rebuild
                        your image.</p>
                    <em></em>
                </a>
            </div>
            <div class="col-lg-3">
                <img src="https://picsum.photos/800/800?k" alt="picsum">
                <a href="#">
                    <h3>We help brands connect with people</h3>
                    <p>Let's see why you need to go digital. We will study how to improve your perception and rebuild
                        your image.</p>
                    <em></em>
                </a>
            </div>


            <div class="col-lg-6 squares">
                <div class="row">
                    <div class="col-lg-6">
                        <img src="https://picsum.photos/800/800?ad" alt="picsum">
                        <a href="#">
                            <h3>We help brands connect with people</h3>
                            <p>Let's see why you need to go digital. We will study how to improve your perception and
                                rebuild your image.</p>
                            <em></em>
                        </a>
                    </div>
                    <div class="col-lg-6">
                        <img src="https://picsum.photos/800/800?bf" alt="picsum">
                        <a href="#">
                            <h3>We help brands connect with people</h3>
                            <p>Let's see why you need to go digital. We will study how to improve your perception and
                                rebuild your image.</p>
                            <em></em>
                        </a>
                    </div>
                    <div class="col-lg-6">
                        <img src="https://picsum.photos/800/800?cg" alt="picsum">
                        <a href="#">
                            <h3>We help brands connect with people</h3>
                            <p>Let's see why you need to go digital. We will study how to improve your perception and
                                rebuild your image.</p>
                            <em></em>
                        </a>
                    </div>
                    <div class="col-lg-6">
                        <img src="https://picsum.photos/800/800?dh" alt="picsum">
                        <a href="#">
                            <h3>We help brands connect with people</h3>
                            <p>Let's see why you need to go digital. We will study how to improve your perception and
                                rebuild your image.</p>
                            <em></em>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 prefer">
                <img src="https://picsum.photos/800/800?ej" alt="picsum">
                <a href="#">
                    <h3>We help brands connect with people</h3>
                    <p>Let's see why you need to go digital. We will study how to improve your perception and rebuild
                        your image.</p>
                    <em></em>
                </a>
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
