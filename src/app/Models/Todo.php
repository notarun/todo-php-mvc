<?php

namespace App\Models;

use App\Models\Model;

class Todo extends Model
{
    public $tableName = 'todo_list';

    public function all(int $createdBy): array
    {
        return $this->db->fetchQuery('SELECT id, item, done FROM ' . $this->tableName . ' WHERE created_by = :created_by ORDER BY done', [
            ':created_by' => $createdBy
        ]);
    }

    public function itemExists(int $id): bool
    {
        $item = $this->db->fetchQuery('SELECT item FROM ' . $this->tableName . ' WHERE id = :id', [
            ':id' => $id
        ]);

        return empty($item) ? false : true;
    }

    public function itemBelongsToUser(int $itemId, int $userId): bool
    {
        if ($userId !== $this->createdBy($itemId)) {
            return false;
        }

        return true;
    }

    public function createdBy(int $itemId): ?int
    {
        if (!$this->itemExists($itemId)) {
            return null;
        }

        return $this->db->fetchQuery('SELECT created_by FROM ' . $this->tableName . ' WHERE id = :id', [
            ':id' => $itemId,
        ])[0]['created_by'];
    }

    public function item(int $id): array
    {
        if (!$this->itemExists($id)) {
            return [];
        }

        return $this->db->fetchQuery('SELECT item, done FROM ' . $this->tableName . ' WHERE id = :id', [
            ':id' => $id
        ])[0];
    }

    public function add(int $createdBy, string $item)
    {
        $this->db->query('INSERT INTO ' . $this->tableName . ' (item, created_by) VALUES (:item, :created_by)', [
            ':item' => $item,
            ':created_by' => $createdBy
        ]);
    }

    public function update(int $id, string $item)
    {
        $this->db->query('UPDATE ' . $this->tableName . ' SET item = :item WHERE id = :id', [
            ':id' => $id,
            ':item' => $item
        ]);
    }

    public function done(int $id)
    {
        $this->db->query('UPDATE ' . $this->tableName . ' SET done = 1 WHERE id = :id', [
            ':id' => $id,
        ]);
    }

    public function undone(int $id)
    {
        $this->db->query('UPDATE ' . $this->tableName . ' SET done = 0 WHERE id = :id', [
            ':id' => $id,
        ]);
    }

    public function delete(int $id)
    {
        $this->db->query('DELETE FROM ' . $this->tableName . ' WHERE id = :id', [
            ':id' => $id,
        ]);
    }
}
