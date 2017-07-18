<?php
    include_once("fabrica/empleado.php");
    $empleadoFlag = false;
    if(strlen($_POST['nombre']) != 0 && strlen($_POST['apellido']) != 0 && strlen($_POST['dni']) != 0 && isset($_POST['sexo']) != 0 && strlen($_POST['legajo']) != 0 && strlen($_POST['sueldo']) && $_FILES['foto']['name'] )
    {
        
        $tipoFoto = exif_imagetype($_FILES['foto']['tmp_name']);
        $tiposValidos = array(IMAGETYPE_JPEG, IMAGETYPE_BMP, IMAGETYPE_PNG, IMAGETYPE_GIF);
        $fotoNueva = $_POST['dni']."-".$_POST['apellido'].".".pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $pathUsados = scandir("fotos");
        
        
        if(in_array($tipoFoto, $tiposValidos) && $_FILES['foto']['size'] < 1024000 && !in_array($fotoNueva, $pathUsados))
        {
            
            $empleado = new empleado($_POST['nombre'], $_POST['apellido'], $_POST['dni'], $_POST['sexo'], $_POST['legajo'], $_POST['sueldo']);
            $archivo = fopen("empleados.txt","a");
            /*
            COPIAR FOTO NUEVA Y MOVER VIEJA
            if(in_array($fotoNueva, $pathUsados))
            {
                copy( "fotos/".$fotoNueva ,"fotos/fotosViejas/".date("d_m_y_").$fotoNueva); 
            }
            move_uploaded_file($_FILES['foto']['tmp_name'], "fotos/".$fotoNueva);
            */
            move_uploaded_file($_FILES['foto']['tmp_name'], "fotos/".$fotoNueva);
            $empleado->setPathFoto("fotos/".$fotoNueva);
            
            if(fwrite($archivo, $empleado->toString()."\r\n") === FALSE)
            {
                echo "error al escribir el archivo";
            }
            else
            {
                $empleadoFlag = true;
            }
        }
        else
            echo "error en la foto";
        
    }
    else
        echo "Error al cargar los datos";

        
    if($empleadoFlag)
        echo "<a href='mostrar.php'>Mostrar Empleados</a>";
    else
        echo "<a href='html/index.html'>Pagina Principal</a>";
    
        
    
?>