<?php
header('Access-Control-Allow-Origin');
header('Access-Control-Allow-Methods:PUT,GET,POST,DELETE,OPTIONS,PATCH');

require '../vendor/autoload.php';

Flight ::register("db", "PDO" ,array("mysql:host=localhost;dbname=school","root","bekir123"));

Flight :: route("GET /students", function(){
    $students= Flight::db()->query("SELECT * FROM students", PDO::FETCH_ASSOC)->fetchAll();
    Flight::json($students);
});
Flight :: route("POST /students", function(){
    $request= Flight::request()->data->getData();
    unset($request["psword"]);
    $insert ="INSERT INTO students (name,surname,phone_number,email) VALUES (:fname,:lname,:phone,:custom_email)";
    $stmt= Flight::db()->prepare($insert);
    $stmt->execute($request);
    
});

Flight::route("DELETE /student/@id",function($id){
    print_r($id);
    $delete = "DELETE FROM students WHERE id =:id";
    $stmt= Flight::db()->prepare($delete);
    $stmt->execute([":id" => $id]);
});

Flight::route("GET /student", function(){
    $id= Flight::request()->query["id"];
    $stmt= Flight::db()->prepare("SELECT * FROM students WHERE id=:id ");
    $stmt->execute([":id" => $id]);
    $student=$stmt->fetch();
    Flight::json($student);
});

Flight::route("POST /student", function(){
    $request= Flight::request()->data->getData();
    unset($request["psword"]);
    $update="UPDATE students SET name=:fname,surname=:lname,phone_number=:phone,email=:custom_email WHERE id=:id";  
    $stmt= Flight::db()->prepare($update);
    $stmt->execute($request);
    Flight::json("Student added");
});


Flight::start();
?>