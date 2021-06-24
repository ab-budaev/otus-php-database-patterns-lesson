<?php

declare(strict_types=1);

namespace Budaev\ActiveRecord;

use PDO;
use PDOStatement;

class User
{
    private ?int         $id        = null;

    private ?string      $firstName = null;

    private ?string      $lastName  = null;

    private ?string      $email     = null;

    private PDO          $pdo;

    private PDOStatement $selectStatement;

    private PDOStatement $insertStatement;

    private PDOStatement $updateStatement;

    private PDOStatement $deleteStatement;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->selectStatement = $pdo->prepare(
            'SELECT * FROM users WHERE id = ?'
        );
        $this->insertStatement = $pdo->prepare(
            'INSERT INTO users (first_name, last_name, email) VALUES (?, ?, ?)'
        );
        $this->updateStatement = $pdo->prepare(
            'UPDATE users SET first_name = ?, last_name = ?, email = ? WHERE id = ?'
        );
        $this->deleteStatement = $pdo->prepare(
            'DELETE FROM users WHERE id = ?'
        );
    }

    public function findOneById(int $id): self
    {
        $this->selectStatement->setFetchMode(PDO::FETCH_ASSOC);
        $this->selectStatement->execute([$id]);

        $result = $this->selectStatement->fetch(PDO::FETCH_ASSOC);

        return (new self($this->pdo))
            ->setId((int)$this->pdo->lastInsertId())
            ->setFirstName($result['first_name'])
            ->setLastName($result['last_name'])
            ->setEmail($result['email']);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function insert(
        string $firstName,
        string $lastName,
        string $email
    ): int
    {
        $this->insertStatement->execute([
            $firstName,
            $lastName,
            $email,
        ]);

        $this->id = (int)$this->pdo->lastInsertId();

        return $this->id;
    }

    public function update(
        int $id,
        string $firstName,
        string $lastName,
        string $email,
    ): bool
    {
        $this->updateStatement->setFetchMode(PDO::FETCH_ASSOC);

        return $this->updateStatement->execute([
            $firstName,
            $lastName,
            $email,
            $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $result = $this->deleteStatement->execute([$id]);

        $this->id = null;

        return $result;
    }
}
