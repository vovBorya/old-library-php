<?php


namespace App\DB;

use Cassandra\Date;
use App\Exceptions\DBException;

use mysqli;

class DBConnector {
    private $connection = null;

    /**
     * DBConnector constructor.
     */
    public function __construct($serverName, $username, $password, $dbname)
    {
        try {
            $this->connection = new \PDO(
                "mysql:host=$serverName;port=3000;charset=utf8mb4;dbname=$dbname",
                $username,
                $password
            );
        } catch (\PDOException $e) {
            $error = new DBException($e->getMessage());
            exit($error->getFailedConnectionMessage());
        }
    }

    /**
     * @return \PDO
     */
    public function getConnection()
    {
        return $this->connection;
    }
}
