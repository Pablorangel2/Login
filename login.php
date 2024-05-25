<?php
require 'vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST["usuario"];
    $senha = $_POST["password"];

    $host = $_ENV['DB_HOST'];
    $banco = $_ENV['DB_DATABASE'];
    $user = $_ENV['DB_USERNAME'];
    $senha_user = $_ENV['DB_PASSWORD'];

    $con = mysqli_connect($host, $user, $senha_user, $banco);

    if (!$con) {
        die("Falha na conexão: " . mysqli_connect_error());
    }

    $sql = $con->prepare("SELECT senha FROM usuario WHERE usuario = ?");
    $sql->bind_param("s", $usuario);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Obtendo o hash da senha armazenada no banco de dados
        $senha_hash = $user['senha'];

        // Depuração: Mostrar a senha hashada e a senha digitada
        echo "Senha hashada no banco de dados: " . $senha_hash . "<br>";
        echo "Senha digitada: " . $senha . "<br>";

        // Verificando a senha
        if (password_verify($senha, $senha_hash)) {
            echo "Login realizado com sucesso";
            // Iniciar sessão e redirecionar usuário
            session_start();
            $_SESSION['usuario'] = $usuario;
            header("Location: teste.php");
            exit;
        } else {
            echo "Senha incorreta.";
        }
    } else {
        echo "Nenhum usuário encontrado com esse nome de usuário.";
    }

    $sql->close();
    $con->close();
} else {
    echo "Método de requisição inválido.";
}

