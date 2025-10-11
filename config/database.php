<?php
/**
 * Clase Database
 * Maneja la conexión a la base de datos MySQL usando PDO
 */
class Database {
    // Configuración de la base de datos
    private $host = 'localhost';
    private $db_name = 'barberiaonline';
    private $username = 'root';
    private $password = '';
    private $conn;

    /**
     * Obtener la conexión a la base de datos
     * @return PDO|null Objeto de conexión PDO
     */
    public function getConnection() {
        $this->conn = null;

        try {
            // Crear la conexión PDO
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password
            );
            
            // Configurar PDO para lanzar excepciones en caso de error
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Establecer el conjunto de caracteres UTF-8
            $this->conn->exec("set names utf8mb4");
            
        } catch(PDOException $e) {
            // Mostrar error de conexión
            echo "Error de conexión: " . $e->getMessage();
            die();
        }

        return $this->conn;
    }
}
?>