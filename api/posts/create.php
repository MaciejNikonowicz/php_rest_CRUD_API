<?php

// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
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

$post->title = $data->title;
$post->body = $data->body;
$post->author = $data->author;
$post->category_id = $data->category_id;

// create post
if ($post->create()) {
    echo json_encode(
        array("message" => "post created")
    );
} else {
    echo json_encode(
        array("message" => "post not created")
    );
}
