<?php
$senha_digitada = '123';  // Senha que você digitou durante o cadastro
$senha_hash = '$2y$10$MK7aLNKJ61r1pd/8Rn1sQ.xrz4zZbCdtbkoUnj.xWmA8ZZZhVt8Le';  // Hash da senha copiado diretamente do banco de dados

// Debug: Verifique se há espaços em branco ou caracteres adicionais
echo "Senha hashada (sem espaços): '" . trim($senha_hash) . "'<br>";
echo "Senha digitada (sem espaços): '" . trim($senha_digitada) . "'<br>";

if (password_verify(trim($senha_digitada), trim($senha_hash))) {
    echo "Senha verificada com sucesso.";
} else {
    echo "Falha na verificação da senha.";
}

