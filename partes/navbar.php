

<?php

if(isset($_SESSION['usuario'])){ ?>
    
        <h4 class="navbar-text"> Bienvenido <?php echo $_SESSION['usuario']->usuario ?> </h4>
        <button class="btn btn-default navbar-btn" type="button" id="desLogin"> Salir </button>
    
<?php }else { ?>
        <form class="form-signin navbar-form" >
            <input type="text" class="form-control" placeholder="usuario" name="usuario" id="usuario">
            <input type="password" class="form-control" placeholder="password" name="pass" id="pass">
            <button class="btn btn-default" type="button" id="login"> Login </button>
        </form>
<?php } ?>