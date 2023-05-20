<div id="preload">
    <div></div>
</div>
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
                <span></span>
            </div>
        </div>
    </header>
</div>
