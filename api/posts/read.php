<?php

// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include_once("../../config/Database.php");
include_once("../../models/Post.php");

// Instanitate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate Blog post object
$post = new Post($db);

// blog post query
$result = $post->read();

// get row count
$num = $result->rowCount();

// check if any posts 
//echo $result;
if ($num > 0) {
    // Post array
    $posts_arr = array();
    $posts_arr["data"] = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $post_item = array(
            "id" => $post_id,
            "title" => $post_title,
            "body" => html_entity_decode($post_body),
            "author" => $post_author,
            "category_id" => $category_id,
            "category_name" => $category_name
        );

        // Push to "data"
        array_push($posts_arr["data"], $post_item);
    }

    // Turn to JSON & output
    echo json_encode($posts_arr);
} else {
    // NO posts
    echo json_encode(
        array("message" => "No posts found.")
    );
}
