<?php

namespace Core;

use PDO;

class Database
{
    public $pdo;
    public $statement;

    public function __construct(string $host, string $dbname, string $username, string $password, array $options = [])
    {
        try {
            $this->pdo = new PDO("mysql:host=${host};dbname=${dbname}", $username, $password, $options);
        } catch (PDOException $error) {
            throw $error->getMessage();
        }
    }

    /**
     * Returns array of data from db.
     *
     * @param string $query
     * @param array $params
     * @return array
     */
    public function fetchQuery(string $query, array $params = []): array
    {
        $this->query($query, $params);
        return $this->statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Executes the SQL query
     *
     * @param string $query
     * @param array $params
     * @return void
     */
    public function query(string $query, array $params = [])
    {
        if ($params === []) {
            $this->statement = $this->pdo->query($query);
        }

        $this->statement = $this->pdo->prepare($query);
        $this->statement->execute($params);
    }
}
