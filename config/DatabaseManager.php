<?php
class DatabaseManager
{
    private $DBH;
    private $effected;
    private static $instance;

    private function __construct($host, $dbName, $dbUser, $dbPassword)
    {
        try {
            $dsn = "mysql:host={$host};dbname={$dbName}";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            $this->DBH = new PDO($dsn, $dbUser, $dbPassword, $options);
        } catch (PDOException $e) {
            $this->handleError("Error connecting to the database: " . $e->getMessage());
        }
    }

    public static function getInstance($host, $dbName, $dbUser, $dbPassword)
    {
        if (!self::$instance) {
            self::$instance = new self($host, $dbName, $dbUser, $dbPassword);
        }
        return self::$instance;
    }


    public function getConnection()
    {
        return $this->DBH;
    }

// select

public function select($tableName, $queryFields = ['fieldNames' => ['*']])
{
    $columns = implode(', ', $queryFields['fieldNames']);
    $joins = $queryFields['joins'] ?? [];
    $where = $queryFields['where'] ?? [];
    $orderBy = $queryFields['orderBy'] ?? null;
    $limit = $queryFields['limit'] ?? null;

    $sql = "SELECT $columns FROM $tableName";

    foreach ($joins as $join) {
        $sql .= " INNER JOIN {$join['table']} ON {$join['condition']}";
    }

    if (!empty($where)) {
        $whereClause = $this->buildWhereClause($where);
        $sql .= " WHERE $whereClause";
    }

    if ($orderBy !== null) {
        $sql .= " ORDER BY $orderBy";
    }

    if ($limit !== null) {
        $sql .= " LIMIT $limit";
    }

    return $this->executeQuery($sql, $where);
}

// ...

    public function find($tableName, $queryFields)
    {
        $queryFields['limit'] = 1;
        $records = $this->select($tableName, $queryFields);
        return $records ? $records[0] : false;
    }

    public function count($tableName, $where = [], $logicalOperator = 'AND')
    {
        $sql = "SELECT COUNT(*) as total FROM $tableName";
    
        if (!empty($where)) {
            $whereClause = $this->buildWhereClause($where, $logicalOperator);
            $sql .= " WHERE $whereClause";
        }
    
        $result = $this->executeQuery($sql, $where);
        return isset($result[0]['total']) ? (int) $result[0]['total'] : 0;
    }


    public function insert($tableName, $data){
    $columns = implode(', ', array_keys($data));
    $placeholders = implode(', ', array_fill(0, count($data), '?'));

    $sql = "INSERT INTO $tableName ($columns) VALUES ($placeholders)";

    $insertData = array_values($data);
    $this->executeQuery($sql, $insertData);
}


    public function update($tableName, $data, $where = [], $logicalOperator = 'AND'){
        $setClause = implode(' = ?, ', array_keys($data)) . ' = ?';
        $sql = "UPDATE $tableName SET $setClause";
    
        if (!empty($where)) {
            $whereClause = $this->buildWhereClause($where, $logicalOperator);
            $sql .= " WHERE $whereClause";
        }
    
        $updateData = array_merge(array_values($data), array_values($where));
        //print_r($sql, $updateData);
        $this->executeQuery($sql, $updateData);
    }

   public function delete($tableName, $where = [], $logicalOperator = 'AND'){
    $sql = "DELETE FROM $tableName";

    if (!empty($where)) {
        $whereClause = $this->buildWhereClause($where, $logicalOperator);
        $sql .= " WHERE $whereClause";
    }

    $this->executeQuery($sql, $where);
}

private function executeQuery($sql, $data = [])
{
 
    try {
        $stmt = $this->DBH->prepare($sql);

        // Use positional placeholders
        $i = 1;
        foreach ($data as $value) {
            if (is_array($value)) {
                // If it's an array, use bindValue with PDO::PARAM_STR to convert it to a string
                $stmt->bindValue($i++, implode(',', $value), PDO::PARAM_STR);
            } else {
                // Otherwise, bind as usual
                $stmt->bindValue($i++, $value); // Use bindValue instead of bindParam
            }
        }

        $stmt->execute();

        $this->effected = $stmt->rowCount();

        // Use fetch instead of fetchAll if you expect only one row
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $errorInfo = $stmt->errorInfo();
        if ($errorInfo) {
            $this->handleError("Database query error: " . implode("; ", $errorInfo));
        }
       
    }
}




    private function handleError($errorMessage)
    {
        // Handle errors here, e.g., log the error, display a user-friendly message, etc.
        die("Error: " . $errorMessage);
    }

    private function buildWhereClause($conditions, $logicalOperator = 'AND')
    {
        $clauses = [];
        foreach ($conditions as $field => $condition) {
            if (is_array($condition)) {
                if (isset($condition['logic'])) {
                    $logicalOperator = $condition['logic'];
                }
                $clauses[] = $this->buildWhereClause($condition, $logicalOperator);
            } else {
                $operator = isset($condition['operator']) ? $condition['operator'] : '=';
                $clauses[] = "$field $operator ?";
            }
        }
        $whereClause = implode(" $logicalOperator ", $clauses);
        return $whereClause;
    }
    

    public function success()
    {
       return  $this->effected ;
    }
    public function beginTransaction()
    {
        $this->DBH->beginTransaction();
    }

    public function commitTransaction()
    {
        $this->DBH->commit();
    }

    public function rollbackTransaction()
    {
        $this->DBH->rollback();
    }
}

?>