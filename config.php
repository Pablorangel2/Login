<?php
require 'vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["confirmar"])) {
    $nome = $_POST["nome"];
    $usuario = $_POST["usuario"];
    $senha = $_POST["senha"];
    $checkSenha = $_POST["check-senha"];
    $email = $_POST["email"];
    $telefone = $_POST["telefone"];
    $idade = $_POST["idade"];

    if ($senha != $checkSenha) {
        echo "<script>alert('As senhas não correspondem'); window.history.back();</script>";
        exit();
    }

    $host = $_ENV['DB_HOST'];
    $banco = $_ENV['DB_DATABASE'];
    $user = $_ENV['DB_USERNAME'];
    $senha_user = $_ENV['DB_PASSWORD'];

    $con = new mysqli($host, $user, $senha_user, $banco);

    if ($con->connect_error) {
        die("Falha na conexão: " . $con->connect_error);
    }

    // Verificação de Usuário Existente
    $stmt = $con->prepare("SELECT COUNT(*) FROM usuario WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->bind_result($userCount);
    $stmt->fetch();
    $stmt->close();

    if ($userCount > 0) {
        echo "<script>
                alert('Usuário já existe cadastrado!');
                setTimeout(function() {
                    window.location.href = 'cadastro.html';
                }, 1000);
              </script>";
        exit();
    }
    // Hash da senha
    $senha_hashed = password_hash($senha, PASSWORD_DEFAULT);

    try {
        $sql = $con->prepare("INSERT INTO usuario(nome, usuario, senha, email, telefone, idade) VALUES (?, ?, ?, ?, ?, ?)");
        $sql->bind_param("ssssss", $nome, $usuario, $senha_hashed, $email, $telefone, $idade);

        if ($sql->execute()) {
            echo "<script>alert('Cadastro realizado com sucesso'); window.location.href = 'index.html';</script>";
        } else {
            throw new Exception("Erro ao realizar o cadastro: " . $sql->error);
        }
        $sql->close();
    } catch (Exception $e) {
        echo "<script>alert('Erro ao cadastrar usuário. Por favor, tente novamente.'); window.history.back();</script>";
    }

    $con->close();
} else {
    echo "Erro ao confirmar os dados.";
}

