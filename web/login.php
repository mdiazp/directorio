<?php
include("header.php");
?>
        <div class="row" id="login">
            <div class="container">
                <div class="col-md-12"> <br/><br/><br/>
                    
                    <div class="container">
                        <div class="card card-container">
                            <!-- <img class="profile-img-card" src="//lh3.googleusercontent.com/-6V8xOA6M7BA/AAAAAAAAAAI/AAAAAAAAAAA/rzlHcD0KYwo/photo.jpg?sz=120" alt="" /> -->
                            <img id="profile-img" class="profile-img-card" src="/img/avatar_2x.png" />
                            <!--img id="profile-img" class="profile-img-card" src="/img/directorio.svg" /-->
                            <p id="profile-name" class="profile-name-card"></p>
                            <form class="form-signin">
                                <span id="reauth-email" class="reauth-email"></span>
                                <input type="text" id="inputUsuario" class="form-control" placeholder="Usuario" required autofocus>
                                <input type="password" id="inputPassword" class="form-control" placeholder="Contraseña" required>
                                <button class="btn btn-lg btn-success btn-block btn-signin" type="submit">Enviar</button>
                            </form><!-- /form -->
                        </div><!-- /card-container -->
                    </div>

                </div>
            </div>
        </div>

<?php
include("footer.php");
?>