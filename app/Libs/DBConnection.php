<?php

namespace App\Libs;

use PDO;

class DBConnection
{
    private $host;
    private $port;
    private $database;
    private $user;
    private $password;

    private $connection;
    private $classToFetch;

    function __construct()
    {
        $config = new Config;
        $this->host = $config->getByKey('DB_HOST');
        $this->port = $config->getByKey('DB_PORT');
        $this->database = $config->getByKey('DB_DATABASE');
        $this->user = $config->getByKey('DB_USER');
        $this->password = $config->getByKey('DB_PASSWORD');

        $this->connection = null;
    }

    private function getConnection()
    {
        if (empty($this->connection)) {
            $this->connection = new PDO("mysql:host={$this->host};port={$this->port};dbname={$this->database};charset=utf8", $this->user, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return $this->connection;
    }

    private function transformToUTF8($array)
    {
        array_walk_recursive($array, function (&$item, $key) {
            if (!mb_detect_encoding($item, 'utf-8', true)) {
                $item = utf8_encode($item);
            }
        });

        return $array;
    }

    public function selectOneQuery(string $query, $typeFetch = PDO::FETCH_ASSOC)
    {
        $stmt = $this->getConnection()->prepare($query);

        if ($typeFetch == PDO::FETCH_CLASS) {
            $stmt->setFetchMode($typeFetch, $this->getClassToFetch());
        } else {
            $stmt->setFetchMode($typeFetch);
        }

        $stmt->execute();
        $rows = $stmt->fetch();

        return ($typeFetch == PDO::FETCH_CLASS) ? $rows : $this->transformToUTF8($rows);
    }

    public function selectQuery(string $query, $typeFetch = PDO::FETCH_ASSOC)
    {
        $stmt = $this->getConnection()->prepare($query);

        if ($typeFetch == PDO::FETCH_CLASS) {
            $stmt->setFetchMode($typeFetch, $this->getClassToFetch());
        } else {
            $stmt->setFetchMode($typeFetch);
        }

        $stmt->execute();
        $rows = $stmt->fetchAll();

        return ($typeFetch == PDO::FETCH_CLASS) ? $rows : $this->transformToUTF8($rows);
    }

    public function updateQuery(string $stmtQuery, array $data)
    {
        $stmt = $this->getConnection()->prepare($stmtQuery);
        foreach ($data as $key => $value) {
            $stmt->bindParam(":{$key}", $value);
        }
        $stmt->execute($data);

        return $stmt->rowCount();
    }

    public function insertQuery(string $stmtQuery, array $data)
    {
        $stmt = $this->getConnection()->prepare($stmtQuery);

        foreach ($data as $key => $value) {
            $stmt->bindParam(":{$key}", $value);
        }

        return $stmt->execute($data);
    }

    public function lastInsertId()
    {
        return $this->getConnection()->lastInsertId();
    }

    public function setClassToFetch($classToFetch)
    {
        $this->classToFetch = $classToFetch;
    }

    protected function getClassToFetch()
    {
        return $this->classToFetch;
    }

    public function beginTransaction()
    {
        $this->getConnection()->beginTransaction();
    }

    public function commit()
    {
        $this->getConnection()->commit();
    }

    public function rollback()
    {
        $this->getConnection()->rollback();
    }
}
