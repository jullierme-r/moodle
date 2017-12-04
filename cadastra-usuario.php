<?php
include("funcoes-banco.php");

$firstName = $_POST['nome'];
$lastName = $_POST['sobrenome'];
$user = $_POST['cpf'];
$pass = $_POST['senha'];
$email = $_POST['email'];

$senha = MD5($pass);

$busca = buscaUser($conexao,$user,$email);

if($busca['firstname'] == "") {
    cadastraUser($conexao,$user, $firstName, $lastName, $senha, $email);
} else {
    echo "<br>usuario ja possui cadastro.";
}
?>
<!--
<form>
    <select name="categorias">
        #<?php
        #$result = listaCursos($conexao);
        #foreach($result as $r) : ?>
            <option value="<?=$r['id']?>">
              #  <?=$r['name']?>
            </option>
        <?php// endforeach ?>
    </select>
</form>
-->

<br><a href="formulario-cadastro.php">Voltar</a>
