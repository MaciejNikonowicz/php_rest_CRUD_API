<?php

class Post
{
    // DB Stuff
    private $conn;
    private $table = "posts";

    // Post Properties
    public $id;
    public $category_id;
    public $category_name;
    public $title;
    public $body;
    public $author;
    public $created_at;

    // Constructor with DB
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get Posts
    public function read()
    {
        // Create Query
        $query = "SELECT 
        c.name as category_name, 
        p.id as post_id,
        p.category_id as category_id,
        p.title as post_title,
        p.body as post_body,
        p.author as post_author,
        p.created_at as post_created_at
        FROM
        " . $this->table . " p
        LEFT JOIN
        categories c
        ON
        p.category_id = c.id
        ORDER BY
        p.created_at DESC";

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Execute statemente(query)
        $stmt->execute();

        return $stmt;
    }

    // Get single Post
    public function read_single()
    {
        // Create Query
        $query = "SELECT 
        c.name as category_name, 
        p.id as post_id,
        p.category_id as category_id,
        p.title as post_title,
        p.body as post_body,
        p.author as post_author,
        p.created_at as post_created_at
        FROM
        " . $this->table . " p
        LEFT JOIN
        categories c
        ON
        p.category_id = c.id
        WHERE
        p.id = ?
        LIMIT 0,1";

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind ID
        $stmt->bindParam(1, $this->id);

        // Execute statemente(query)
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Set properties
        $this->title = $row["post_title"];
        $this->body = $row["post_body"];
        $this->author = $row["post_author"];
        $this->category_id = $row["category_id"];
        $this->category_name = $row["category_name"];
    }

    // Create Post
    public function create()
    {
        // Create query
        $query = "INSERT INTO " . $this->table . "
            SET 
                title = :title,
                body = :body,
                author = :author,
                category_id = :category_id
        ";

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // clean data
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->body = htmlspecialchars(strip_tags($this->body));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));

        // Bind Data
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":body", $this->body);
        $stmt->bindParam(":author", $this->author);
        $stmt->bindParam(":category_id", $this->category_id);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }

        // Print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);

        return false;
    }


    // Update Post
    public function update()
    {
        // Create query
        $query = "UPDATE " . $this->table . "
            SET 
                title = :title,
                body = :body,
                author = :author,
                category_id = :category_id
            WHERE
                id = :id
        ";

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // clean data
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->body = htmlspecialchars(strip_tags($this->body));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));

        // Bind Data
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":body", $this->body);
        $stmt->bindParam(":author", $this->author);
        $stmt->bindParam(":category_id", $this->category_id);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }

        // Print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);

        return false;
    }

    // Delete Post
    public function delete()
    {
        // Create query
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // clean the data
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind Data
        $stmt->bindParam(":id", $this->id);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }

        // Print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);

        return false;
    }
}
