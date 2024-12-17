<?php
// Content-Type: application/json: Informa al cliente que el contenido devuelto por el servidor estará en formato JSON.
header("Content-Type: application/json"); 
// Access-Control-Allow-Origin: *: Permite que cualquier dominio pueda acceder al servidor. Esto es importante para evitar problemas de CORS .
header("Access-Control-Allow-Origin: *");
// Access-Control-Allow-Methods: GET, POST, PUT, DELETE: Especifica los métodos HTTP que acepta este servidor.
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
// Access-Control-Allow-Headers: Content-Type: Permite que el cliente envíe datos en formato JSON en las solicitudes.
header("Access-Control-Allow-Headers: Content-Type");

// Se define la configuración de conexión a la base de datos ( localhost, usuario root, sin contraseña, base de datos crud_app).
$servername = "sql5.freesqldatabase.com";
$username = "sql5751912";
$password = "PtGRplv1Fs";
$dbname = "sql5751912";
//new mysqli: Crea la conexión con la base de datos.
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    // Si la conexión falla, se termina el script con un mensaje de error.
    die("Conexión fallida: " . $conn->connect_error);
}


// Leer el método HTTP.

// $_SERVER['REQUEST_METHOD']: Detecta el método 
//HTTP de la solicitud (GET, POST, PUT o DELETE).
$method = $_SERVER['REQUEST_METHOD']; //Según el método, el servidor ejecutará una operación distinta.

// Manejar solicitudes
switch ($method) {
    // Llama al procedimient almacenadosp_getUsuarios() que retorna todos los registros de la tabla usuarios.
    case "GET":
        $sql = "CALL sp_read_tack()";
        $result = $conn->query($sql);
        $data = [];

        if ($result->num_rows > 0) {
            // $result->fetch_assoc(): Convierte cada fila del resultado en un arreglo asociativo.
            while ($row = $result->fetch_assoc()) {
                $data[] = $row; //$data[] = $row;: Agrega cada fila al arreglo $data.
            }
        }
        // echo json_encode($data);: Devuelve los datos en formato JSON al cliente.
        echo json_encode($data);
        break;

    case "POST":
        // json_decode(..., true): Convierte el JSON en un arreglo asociativo.
        // file_get_contents("php://input"): Obtiene los datos enviados por el cliente (en formato JSON).
        $input = json_decode(file_get_contents(filename: "php://input"), true);
        $name = $input['name_taks'];
        $description = $input['description_taks'];
        // $stmt->prepare(): Prepare una consulta con parámetros para prevenir inyecciones SQL.
        $stmt = $conn->prepare("CALL sp_nuw_tack(?, ?)");
        // bind_param("sss", ...): Enlaza los parámetros ( ssssignifica que son 3 cadenas de texto).
        $stmt->bind_param("ss", $name, $description);
        $stmt->execute();// stmt->execute(): Ejecuta el procedimiento almacenado sp_createUsuario.
        echo json_encode(["message" => "Tarea creado"]);
        break;

    case "PUT":
        // Similar al caso POST, pero además incluye el ID del usuario que se actualizará.
        $input = json_decode(file_get_contents("php://input"), true);
        $id = $input['id_tasks'];
        $name = $input['name_taks'];
        $description = $input['description_taks'];
        // Llama al procedimiento almacenado sp_updateUsuario.
        $stmt = $conn->prepare("CALL sp_update_taks(?, ?, ?)");
        $stmt->bind_param("iss", $id, $name, $description);
        $stmt->execute();
        echo json_encode(["message" => "Tarea actualizado"]);
        break;

    case "DELETE":
        // Obtiene el ID del usuario desde el cliente.
        $input = json_decode(file_get_contents("php://input"), true);
        $id = $input['id_tasks'];
        // Llama al procedimiento almacenado sp_deleteUsuariopara eliminar el registro correspondiente.
        $stmt = $conn->prepare("CALL sp_delete(?)");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        echo json_encode(["message" => "Tarea eliminada"]);
        break;
}
// Cierra la conexión a la base de datos al final del script para liberar recursos.
$conn->close();

?>