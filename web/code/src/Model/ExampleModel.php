<?php

declare(strict_types = 1);

namespace Example\Model;

use Mini\Model\Model;

/**
 * Example data.
 */
class ExampleModel extends Model
{
    protected $data;

    /**
     * Get example data by ID.
     *
     * @param int $id example id
     *  
     * @return array example data
     */
    public function get(int $id): array
    {
        if(!empty($this->data))   {
            return $this->data;
        } else {
            return $this->getQuery($id);
        }
    }

    public function set($data): array
    {
        return $this->data = $data;
    }

    public function getQuery(int $id): array
    {
        $sql = '
            SELECT
                example_id AS "id",
                created,
                code,
                description
            FROM
                ' . getenv('DB_SCHEMA') . '.master_example
            WHERE
                example_id = ?';

        return $this->set($this->db->select([
            'title'  => 'Get example data',
            'sql'    => $sql,
            'inputs' => [$id]
        ]));
    }

    /**
     * Create an example.
     *
     * @param string $created     example created on
     * @param string $code        example code
     * @param string $description example description
     *  
     * @return int example id
     */
    public function create(string $created, string $code, string $description): int
    {
        $sql = '
            INSERT INTO
                ' . getenv('DB_SCHEMA') . '.master_example
            (
                created,
                code,
                description
            )
            VALUES
            (?,?,?)';

        $id = $this->db->statement([
            'title'  => 'Create example',
            'sql'    => $sql,
            'inputs' => [
                $created,
                $code,
                $description
            ]
        ]);

        $this->db->validateAffected();

        return $id;
    }

    public function save()
    {
        return $this->create(now(), $this->data['code'], $this->data['description']);
    }
}
