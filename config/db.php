<?php
// class Database {
//     private $host = "sql108.infinityfree.com";
//     private $db_name = "if0_39401290_hrms";
//     private $username = "if0_39401290";
//     private $password = "oR1NlfxVH0x";
//     public $conn;

//     public function getConnection() {
//         $this->conn = null;
//         try {
//             $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, 
//                                  $this->username, $this->password);
//             $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//             $this->conn->exec("set names utf8");
//         } catch(PDOException $exception) {
//             echo "Connection error: " . $exception->getMessage();
//         }
//         return $this->conn;
//     }
// }


class Database {
    private $host = 'centerbeam.proxy.rlwy.net';
    private $db_name = 'railway';
    private $username = 'root';
    private $password = 'IyKtBKtuZZHTBZxWrZTAMZYlPdBbZRyQ';
    private $port = '24361';

    public $conn;

    public function getConnection()
    {
        $this->conn = null;

        try {
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db_name};charset=utf8mb4";

            $this->conn = new PDO(
                $dsn,
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_TIMEOUT => 5
                ]
            );

        } catch (PDOException $exception) {
            die("Database Connection Error: " . $exception->getMessage());
        }

        return $this->conn;
    }
}