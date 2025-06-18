<?php
class Database
{
    private $host = 'localhost';
    private $db_name = 'db_kab_kota';
    private $username = 'root';
    private $password = "";
    private $connection = null;

    public function connect()
    {
        try {
            $this->connection = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->db_name,
                $this->username,
                $this->password
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exc) {
            echo "Connection error: " . $exc->getMessage();
        }
        return $this->connection;
    }
}
