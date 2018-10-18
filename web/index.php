<?php
include("header.php");
?>

        <header id="header">
            <div class="container">
                <div class="row">
                    <figure class="text-center">
                        <img width="200px" height="200px" src='/img/directorio.svg'> </img>
                    </figure>
                    
                    <h1 class="text-banner-h1 text-capitalize text-center">Directorio</h1>
                    <h4 class="text-banner-h4 text-center">Universidad de Pinar del R&iacute;o</h4>
                </div>
            </div>
        </header>
    

        <div class="row row-search" id="search">
            <div class="container">
                <div class="col-md-6 hidden-xs">
                    <h2 class="direcctorio-name">Directorio UPR</h2>
                </div>
                <div class="col-md-6 col-xs-12">
                    <input class="col-md-8 form-control" type="text" name="search_nombre" id="search_nombre" placeholder="Nombre, usuario, ubicaci&oacute;n" old-text=""/>
                </div>
            </div>
        </div>

        <div id="containerResult">
            <div class="container container-result" id="dynamic-section-data" op="1" cookiepage="" searchstate='' moreresults='Yes'>
                <div id='items_list' class='row'>

                </div>
            </div>   
                
            <div>
                <figure class="text-center" id="loading">
                    <img src="img/loading.gif" alt="Loading…" />
                    <div style="height: 20px;"></div>
                </figure>
            </div>
        </div>

<?php
include("footer.php");
?>