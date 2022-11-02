<?php

namespace App\core\database;

use PDOStatement;

class QueryBuilder
{
    private \PDO $pdo;
    private ?string $query;
    private ?PDOStatement $statement;
    private array $params = [];

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param string $table
     * @param array|null $condition
     * @return int
     */
    public function count(string $table, ?array $condition = null): int
    {
        $query = "SELECT COUNT(*) FROM $table";

        if ($condition) {
            $query .= " WHERE $condition[0] = " . ":$condition[0]";
        }

        $statement = $this->pdo->prepare($query);

        if ($condition) {
            $statement->bindParam(":$condition[0]", $condition[1]);
        }

        $statement->execute();

        return $statement->fetchColumn();
    }

    /**
     * @param string $table
     * @param array|null $fields
     * @return QueryBuilder
     */
    public function select(string $table, ?array $fields = []): QueryBuilder
    {
        $fields = $fields ? implode(', ', $fields) : '*';

        $this->query = "SELECT $fields FROM $table";

        return $this;
    }

    /**
     * @param string $field
     * @param string $value
     * @param string $condition
     * @param string|null $tablePrefix
     * @return QueryBuilder
     */
    public function where(string $field, string $value, ?string $condition = '=', ?string $tablePrefix = null): QueryBuilder
    {
        $this->query .= " WHERE $tablePrefix$field $condition :$field";

        $this->statement = $this->pdo->prepare($this->query);

        $this->params[] = [":$field" => $value];

        return $this;
    }

    /**
     * @param string $field
     * @param string $value
     * @param string $condition
     * @return QueryBuilder
     */
    public function andWhere(string $field, string $value, ?string $condition = '='): QueryBuilder
    {
        $this->query .= " AND $field $condition :$field";

        $this->statement = $this->pdo->prepare($this->query);

        $this->params[] = [":$field" => $value];

        return $this;
    }

    /**
     * @param string $field
     * @param string $subQuery
     * @param string $value
     * @param string $condition
     * @return QueryBuilder
     */
    public function whereSubquery(string $field, string $subQuery, string $value, ?string $condition = '='): QueryBuilder
    {
        $subQueryField = trim(explode(':', $subQuery)[1], ')');

        $statement = $this->pdo->prepare($subQuery);
        $statement->bindParam(":$subQueryField", $value);
        $statement->execute();
        $subQueryResult = $statement->fetchColumn();

        $this->query .= " WHERE $field $condition $subQueryResult";

        $this->statement = $this->pdo->prepare($this->query);

        return $this;
    }

    /**
     * @param string $field
     * @param string $value
     * @param string $condition
     * @param string|null $tablePrefix
     * @return QueryBuilder
     */
    public function having(string $field, string $value, ?string $condition = '=', ?string $tablePrefix = null): QueryBuilder
    {
        $this->query .= " HAVING $tablePrefix$field $condition :$field";

        $this->statement = $this->pdo->prepare($this->query);

        $this->params[] = [":$field" => $value];

        return $this;
    }

    public function bindParams(): void
    {
        foreach ($this->params as $param) {
            foreach ($param as $key => &$value) {
                $this->statement->bindParam($key, $value);
            }
        }

        $this->params = [];
    }

    /**
     * @param string $id
     * @return QueryBuilder
     */
    public function groupBy(string $id): QueryBuilder
    {
        $this->query .= " GROUP BY $id";

        return $this;
    }

    /**
     * @param int $offset
     * @param int $rowCount
     * @return QueryBuilder
     */
    public function limit(int $offset, int $rowCount): QueryBuilder
    {
        $this->query .= " LIMIT $offset, $rowCount";

        return $this;
    }

    /**
     * @param string $joinTable
     * @param string $joinTableId
     * @param string $table
     * @param string $tableId
     * @return QueryBuilder
     */
    public function join(string $joinTable, string $joinTableId, string $table, string $tableId): QueryBuilder
    {
        $this->query .= " INNER JOIN $joinTable ON $joinTable.$joinTableId = $table.$tableId";

        return $this;
    }

    /**
     * @param string $joinTable
     * @param string $joinTableId
     * @param string $table
     * @param string $tableId
     * @return QueryBuilder
     */
    public function leftJoin(string $joinTable, string $joinTableId, string $table, string $tableId): QueryBuilder
    {
        $this->query .= " LEFT JOIN $joinTable ON $joinTable.$joinTableId = $table.$tableId";

        return $this;
    }

    /**
     * @param string|null $table
     * @return QueryBuilder
     
     */
    public function latest(?string $table = null): QueryBuilder
    {
        $table = $table ? $table . '.' : '';

        $this->query .= " ORDER BY $table created_at DESC";

        return $this;
    }

    /**
     * @param string $field
     * @param string $dir
     * @return QueryBuilder
     
     */
    public function orderBy(string $field, string $dir): QueryBuilder
    {
        $this->query .= " ORDER BY $field $dir";

        return $this;
    }


    /**
     * @return array 
     */
    public function get(): array
    {
        if (isset($this->statement)) {

            $this->bindParams();

            $this->statement->execute();
        } else {

            $this->statement = $this->pdo->prepare($this->query);
            $this->statement->execute();
        }

        $results = $this->statement->fetchAll();

        $this->statement = null;
        $this->query = null;

        return $results;
    }

    /**
     * first() viene sempre dopo where(), quindi devo solo eseguire lo statement
     * che viene preparato nel where()
     * 
     * @return array|null
     */
    public function first(): array |null
    {
        if (!empty($this->params)) {
            $this->bindParams();
        }

        $this->statement->execute();

        $result = $this->statement->fetch();

        $this->statement = null;
        $this->query = null;

        return $result ? $result : null;
    }

    /**
     * @param string $table
     * @param array $columns
     * @param string $search
     * @return array
     */
    public function search(string $table, array $columns, string $search): array
    {
        $placeholders = array_map(function ($column) {
            return ":$column";
        }, $columns);

        $query = "SELECT * FROM $table WHERE $columns[0] LIKE $placeholders[0]";

        if (count($columns) > 1) {
            $query .= " OR $columns[1] LIKE $placeholders[1]";
        }

        $statement = $this->pdo->prepare($query);

        $search = "%$search%";

        foreach ($placeholders as $placeholder) {
            $statement->bindParam($placeholder, $search);
        }

        $statement->execute();

        return $statement->fetchAll();
    }

    /**
     * @param string $table
     * @param array  $data
     * @return bool
     */
    public function insert(string $table, array $data): bool
    {
        $keys = implode(', ', array_keys($data));

        $placeholders = array_map(function ($key) {
            return ':' . $key;
        }, array_keys($data));

        $placeholders = implode(', ', $placeholders);

        $query = "INSERT INTO $table($keys) VALUES($placeholders)";

        $statement = $this->pdo->prepare($query);

        foreach ($data as $key => &$value) {
            $statement->bindParam($key, $value);
        }

        return $statement->execute($data);
    }

    /**
     * @param string $table
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function update(string $table, array $data, int $id): bool
    {
        unset($data['id']);
        $values = '';

        $query = "UPDATE $table SET ";

        foreach ($data as $key => $value) {
            $values .= "$key = :$key, ";
        }

        $values = rtrim($values, ', ');

        $query .= $values;

        $query .= " WHERE id = :id";

        $statement = $this->pdo->prepare($query);

        $statement->bindParam('id', $id);

        foreach ($data as $key => &$value) {
            $statement->bindParam($key, $value);
        }

        return $statement->execute();
    }

    /**
     * @param string $table
     * @param string $field
     * @param mixed $value
     * @return array|bool
     */
    public function delete(string $table, string $field, mixed $value): array |bool
    {
        $query = "DELETE FROM $table WHERE $field = :$field";

        $statement = $this->pdo->prepare($query);
        $statement->bindParam(":$field", $value);

        return $statement->execute();
    }

    /**
     * @param string $query
     * @param array $params
     * @return array
     */
    public function raw(string $query, array $params): array
    {
        $statement = $this->pdo->prepare($query);

        foreach ($params as $key => &$value) {
            $statement->bindParam($key, $value);
        }

        $statement->execute();

        return $statement->fetchAll();
    }
}
