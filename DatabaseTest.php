<?php

require 'vendor/autoload.php';

class DatabaseTest extends PHPUnit\Framework\TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        $dsn = 'mysql:host=localhost;dbname=mydatabase;charset=utf8';
        $username = 'username';
        $password = 'password';

        $this->pdo = new PDO($dsn, $username, $password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function testInsertData()
    {
        $stmt = $this->pdo->prepare('INSERT INTO users (name, email) VALUES (?, ?)');
        $stmt->execute(['John Doe', 'john@example.com']);
        $this->assertEquals(1, $stmt->rowCount());
    }

    public function testGetData()
    {
        $stmt = $this->pdo->query('SELECT * FROM users WHERE id = 1');
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertEquals('John Doe', $data['name']);
        $this->assertEquals('john@example.com', $data['email']);
    }
}
