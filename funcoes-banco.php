<?php
$conexao = pg_connect("host=localhost port= 5432 user=postgres password=postgres dbname=moodle");

//insere um novo usuário
function cadastraUser($conexao, $user, $firstName, $lastName, $pass, $email){
    $query = "INSERT INTO mdl_user(firstname, lastname,email,username,password,confirmed,mnethostid) VALUES
              ('{$firstName}', '{$lastName}', '{$email}', '{$user}','{$pass}', 1, 1)";
    return pg_query($conexao,$query);
}

//verifica se o usuário já está cadastrado no banco
function buscaUser($conexao, $user, $email){
    $query = "select * from mdl_user where username = '{$user}' or email = '{$email}'";
    $resultado = pg_query($conexao, $query);
    return pg_fetch_assoc($resultado);
}

function listaCursos ($conexao){
    $query = "select * from mdl_course_categories order by depth;";
    $result = pg_query($conexao,$query);
    return pg_fetch_all($result);
}

