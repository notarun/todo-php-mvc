<?php

namespace App\Models;

use DateTime;
use Exception;
use Core\Session;
use App\Models\Model;

class User extends Model
{
    public $tableName = 'users';

    /**
     * Returns if user is loggedin or not.
     *
     * @return boolean
     */
    public static function isLoggedin(): bool
    {
        return Session::has('user');
    }

    /**
     * Returns id of the user.
     *
     * @param string $email
     * @return int
     */
    public function id(string $email = null): ?int
    {
        if ($this->isLoggedin()) {
            return Session::get('user.id');
        }

        $userId = $this->db->fetchQuery('SELECT id FROM ' . $this->tableName . ' WHERE email = :email', [
            ':email' => $email
        ]);

        return empty($userId) ? null : $userId[0]['id'];
    }

    /**
     * Check if user exists inside database or not.
     *
     * @param int $id
     * @return boolean
     */
    public function exists(int $id): bool
    {
        $user = $this->db->fetchQuery('SELECT id FROM ' . $this->tableName . ' WHERE id = :id', [
            ':id' => $id
        ]);

        return empty($user) ? false : true;
    }

    /**
     * Check if given email exists or not.
     *
     * @param string $email
     * @return boolean
     */
    public function emailExists(string $email): bool
    {
        $userId = $this->id($email);
        return is_null($userId) ? false : true;
    }

    /**
     * Adds new user to the database
     *
     * @param string $name
     * @param string $email
     * @param string $password
     * @return void
     */
    public function add(string $name, string $email, string $password)
    {
        $this->db->query('INSERT INTO ' . $this->tableName . ' (name, email, password) VALUE (:name, :email, :password)', [
            ':name' => $name,
            ':email' => $email,
            ':password' => password_hash($password, PASSWORD_ARGON2ID)
        ]);
    }

    /**
     * Returns currently loggedin user's email.
     *
     * @return string
     */
    public function email(): string
    {
        return $this->db->fetchQuery('SELECT email FROM ' . $this->tableName . ' WHERE id = :id', [
            ':id' => $this->id()
        ])[0]['email'];
    }

    /**
     * Returns currently loggedin user's name.
     *
     * @param int $id
     * @return string
     */
    public function name(int $id = null): string
    {
        $userId = $id ?? $this->id();

        return $this->db->fetchQuery('SELECT name FROM ' . $this->tableName . ' WHERE id = :id', [
            ':id' => $userId
        ])[0]['name'];
    }

    /**
     * Returns the hashed password associated with the email
     * from the database.
     *
     * @param string $email
     * @return ?string
     */
    private function password(string $email): ?string
    {
        return $this->db->fetchQuery('SELECT password FROM '. $this->tableName . ' WHERE email = :email', [
            ':email' => $email
        ])[0]['password'];
    }

    /**
     * Returns the currently loggedin users created_at date
     *
     * @return DateTime
     */
    private function createdAt(): DateTime
    {
        $date = $this->db->fetchQuery('SELECT created_at FROM '. $this->tableName . ' WHERE id = :id', [
            ':id' => $this->id()
        ])[0]['created_at'];

        return new DateTime($date);
    }

    /**
     * Checks whether email and password are valid.
     *
     * @param string $email
     * @param string $password
     * @return boolean
     */
    public function validateCredentials(string $email, string $password): bool
    {
        if (!$this->exists($this->id($email))) {
            return false;
        }

        return password_verify($password, $this->password($email));
    }

    /**
     * Returns the currently loggedin users verified_at date
     *
     * @param int $id
     * @return ?DateTime
     */
    private function verifiedAt(int $id = null): ?DateTime
    {
        if (is_null($id)) {
            $id = $this->id();
        }

        $date = $this->db->fetchQuery('SELECT verified_at FROM '. $this->tableName . ' WHERE id = :id', [
            ':id' => $id
        ])[0]['verified_at'];

        if (is_null($date)) {
            return null;
        }

        return new DateTime($date);
    }

    /**
     * Returns if a user has verified email or not.
     *
     * @param int $id
     * @return boolean
     */
    public function isVerified(int $id): bool
    {
        return is_null($this->verifiedAt($id)) ? false : true;
    }

    /**
     * Sets verified_at value for the given user
     *
     * @param int $id
     * @return void
     */
    public function markVerified(int $id): void
    {
        $now = new DateTime();
        $this->db->query('UPDATE ' . $this->tableName . ' SET verified_at = :verified_at WHERE id = :id', [
            ':verified_at' => $now->format('Y-m-d H:i:s'),
            ':id' => $id
        ]);
    }
}
