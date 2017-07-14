<?php
require_once 'empleado.php';
require_once 'vendor/phpoffice/phpexcel/Classes/PHPExcel.php';
require_once 'autentificadorJwt.php';
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
require_once 'vendor/autoload.php';
require_once 'empleadoOperaciones.php';

/**
 * 
 */
class empleadoApi extends empleado
{
    /**
     * Loguea al empleado y le asigna un token
     *
     * @param [type] $request pasa como atributo el usuario y el pass
     * @param [type] $response
     * @param [type] $args
     * @return voi devuelve el token si se pudo loguear, sino devuelve el error y el status code 206 
     */
    public static function loguearEmpleadoApi($request, $response, $args){

        $empleado = empleado::TraerEmpleado($request->getAttribute('usuario'), $request->getAttribute('pass'));
        $retorno['exito'] = false;
        if($empleado){
            if($empleado->activo){
                if($retorno['exito'] = $empleado->registrarLogin()){
                    $retorno['token'] = autentificadorJwt::crearToken(array(
                        'id'=> $empleado->id,
                        'usuario'=> $empleado->usuario,
                        'admin' => $empleado->admin,
                    ));
                    $retorno['usuario'] = $empleado->usuario;
                    $retorno['id'] = $empleado->id;
                    $retorno['admin'] = $empleado->admin;
                }
            }
            else{
                $retorno['mensaje'] = "Empleado suspendido";
            }
        }
        else{
            $retorno['mensaje'] = "Usuario o contraseña invalido";
        }
        if($retorno['exito']){
            return $response->withJson($retorno);    
        }
        else{
            return $response->withJson($retorno, 206 );
        }
        
    }
    /**
     * Registrar el deslogueo del empleado
     *
     * @param [type] $request recibe el id para desloguear al empleado
     * @param [type] $response
     * @param [type] $args
     * @return devuelve true si pudo registrar el deslogueo o false si no pudo
     */
    public static function logoutEmpleadoApi($request, $response, $args){

        $empleado = empleado::buscarEmpleado($request->getAttribute('id'));
        $retorno['exito'] = false;
        if($empleado){
            $retorno['exito'] = $empleado->registrarLogin(false);
        }
        return $response->withJson($retorno);
    }


    public static function listaEmpleadosApi($request, $response, $args){
        $retorno['exito'] = false;
        $empleados = empleado::TraerEmpleados();  
        if(isset($empleados)){
            $retorno['exito'] = true;
            $retorno['empleados'] = $empleados;
        }
        if($retorno['exito']){
            return $response->withJson($retorno);    
        }
        return $response->withJson($retorno, 206 );
    }


    public function alta($request, $response, $args){        
        $nuevoEmpleado = new empleado($request->getAttribute('nombre'), $request->getAttribute('apellido'), $request->getAttribute('usuario'), $request->getAttribute('pass'), $request->getAttribute('activo'), $request->getAttribute('admin'));        
        return $response->withJson($nuevoEmpleado->guardarEmpleado());
    }
    public function modificar($request, $response, $args){
        $empleadoModificado = empleado::buscarEmpleado($request->getAttribute('id'));
        
        if($empleadoModificado){
            $empleadoModificado->nombre = $request->getAttribute('nombre');
            $empleadoModificado->apellido = $request->getAttribute('apellido');
            $empleadoModificado->usuario = $request->getAttribute('usuario');
            $empleadoModificado->pass = $request->getAttribute('pass');
            $empleadoModificado->activo = $request->getAttribute('activo');
            $empleadoModificado->admin = $request->getAttribute('admin');
            return $response->withJson($empleadoModificado->modificarEmpleado());    
        }
        else{
            return $response->withJson('No existe ningun empleado con ese id', 20);
        }
    }
    public function borrar($request, $response, $args){
        $id = filter_var($request->getAttribute('id'), FILTER_SANITIZE_NUMBER_INT);
        if($id){
            return $response->withJson(empleado::borrarEmpleado($id));
        }
        else{
            return $response->withJson("id invalido", 206);
        }
    }
    public function actualizarEstado($request, $response, $args){
        $id = filter_var($request->getAttribute('id'), FILTER_SANITIZE_NUMBER_INT);
        if($id){
            $empleado = empleado::buscarEmpleado($id);
            if($empleado){
                $empleado->actualizar();
                return $response->withJson($empleado->modificarEmpleado());
            }
            return $response->withJson("No existe el empleado", 2);
        }
        return $response->withJson("id invalido", 206);
    }
    public function registrosLogueos($request, $response, $args ){
        $id = $request->getAttribute('id');
        $empleado = empleado::buscarEmpleado($id);
        if($empleado){
            $retorno['empleado'] = $empleado;
            $operaciones = empleado::logueos($empleado->id, $request->getAttribute('desde'), $request->getAttribute('hasta'));
            
            if($operaciones){
                $retorno['operaciones'] = $operaciones;
                return $response->withJson($retorno);
            }
            return $response->withJson("No hay operaciones en esa fecha",206);
        }
        return $response->withJson("No existe ese empleado", 206);
    }

    public function excelLogueos($request, $response, $args){
        $empleado = empleado::buscarEmpleado($request->getAttribute('id'));
        $logueos = empleado::logueos($request->getAttribute('id'), $request->getAttribute('desde'), $request->getAttribute('hasta'));
        $objetoPHPExcel = new PHPExcel();
        $objetoPHPExcel->getProperties()->setCreator("Gustavo Petruzzi")
                                        ->setTitle("Logueo Empleados");

        $objetoPHPExcel->setActiveSheetIndex(0)
                       ->setCellValue('A1', 'Dia')
                       ->setCellValue('B1', 'Entrada')
                       ->setCellValue('C1', 'Salida')
                       ->setCellValue('E1', 'Id')
                       ->setCellValue('E2', 'Nombre')
                       ->setCellValue('E3', 'Apellido')
                       ->setCellValue('E4', 'Usuario')
                       ->setCellValue('F1', $empleado->id)
                       ->setCellValue('F2', $empleado->nombre)
                       ->setCellValue('F3', $empleado->apellido)
                       ->setCellValue('F4', $empleado->usuario);

        $i = 2;
        $objetoPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A3', $logueos);
        foreach ($logueos as $key => $value ) {
            $objetoPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$i, $value['dia'])
                        ->setCellValue('B'.$i, $value['entrada'])
                        ->setCellValue('C'.$i, $value['salida']);
            $i++;                           
        }
        $letra = 'A';
        for ($i=0; $i < 6; $i++) { 
            $objetoPHPExcel->getActiveSheet()
                           ->getColumnDimension($letra++)
                           ->setAutoSize(true);
        }
        

        $objetoPHPExcel->getActiveSheet()->setTitle('Logueos');

        $objetoPHPExcel->setActiveSheetIndex(0);
        $nombre = "registros-".$empleado->usuario."-".$request->getAttribute('desde').".xlsx";
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$nombre.'"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objetoPHPExcel, 'Excel2007');
        ob_end_clean();
        $objWriter->save('php://output');
    }
    

    public function registrosOperaciones($request, $response, $args){
        $id = $request->getAttribute('id');
        try{
            $empleado = new empleadoOperaciones($id);
        }
        catch(Exception $e){
            return $response->withJson($e->getMessage(),206);
        }
        
        if($empleado->traerOperaciones($request->getAttribute('desde'), $request->getAttribute('hasta'))){
            return $response->withJson($empleado);
        }
        else{
            return $response->withJson("No hay operaciones en esas fechas", 206);
        }
    }
    public function pdfOperaciones($request, $response, $args){
        $id = $request->getAttribute('id');
        try{
            $empleado = new empleadoOperaciones($id);
        }
        catch(Exception $e){
            return $response->withJson($e->getMessage(), 206);
        }
        
        $rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
        $rendererLibrary = 'mPDF5.4';
        $rendererLibraryPath = dirname(__FILE__).'/../../../libraries/PDF/' . $rendererLibrary;
        $objetoPHPExcel = new PHPExcel();

        $objetoPHPExcel->getProperties()->setCreator("Gustavo Petruzzi")
                                      ->setTitle("PdfOperacioes");

        $objetoPHPExcel->setActiveSheetIndex(0)
                       ->setCellValue('A6', 'Entrada')
                       ->setCellValue('B6', 'Salida')
                       ->setCellValue('C6', 'Precio');
        $empleado->traerOperaciones($request->getAttribute('desde'), $request->getAttribute('hasta'));

        $i = 7;
        foreach ($empleado->operaciones as $key => $value ) {
            $objetoPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$i, $value['entrada'])
                        ->setCellValue('B'.$i, $value['salida'])
                        ->setCellValue('C'.$i, $value['precio']);
            $i++;                           
        }
        $objetoPHPExcel->getActiveSheet()->setTitle('Operaciones');
        $objetoPHPExcel->getActiveSheet()->setShowGridLines(false);

        $objetoPHPExcel->setActiveSheetIndex(0);

        if (!PHPExcel_Settings::setPdfRenderer(
                $rendererName,
                $rendererLibraryPath
            )) {
            die(
                'NOTICE: Please set the $rendererName and $rendererLibraryPath values' .
                '<br />' .
                'at the top of this script as appropriate for your directory structure'
            );
        }


        // Redirect output to a client’s web browser (PDF)
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment;filename="01simple.pdf"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
        ob_end_clean();
        $objWriter->save('php://output');
                
    }
}
?>