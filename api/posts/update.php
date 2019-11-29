<?php

// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Hedaers: Access-Control-Allow-Methods, Content-Type, Access-Control-Allow-Origin, Authorization, X-Requested-With");

include_once("../../config/Database.php");
include_once("../../models/Post.php");

// Instanitate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate Blog post object
$post = new Post($db);

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

// Set ID to update
$post->id = $data->id;

$post->title = $data->title;
$post->body = $data->body;
$post->author = $data->author;
$post->category_id = $data->category_id;

// update post
if ($post->update()) {
    echo json_encode(
        array("message" => "post updated")
    );
} else {
    echo json_encode(
        array("message" => "post not updated")
    );
}
