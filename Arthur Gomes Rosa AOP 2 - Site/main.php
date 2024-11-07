<?php
include("database.php");


// Verificar se os dados foram enviados
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitizar e validar os dados
    $nome = mysqli_real_escape_string($conn, $_POST["nome"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $cel = mysqli_real_escape_string($conn, $_POST["cel"]);

    // Preparar a query para verificar se o email já está registrado
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo '<script>';
        echo 'alert("Esse e-mail já está registrado!");';
        echo 'window.location.href = "index.html";';
        echo '</script>';
    } else {
        // Preparar a query para inserir os dados
        $stmt = $conn->prepare("INSERT INTO users (nome, email, cel) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nome, $email, $cel);

        // Executar a query
        if ($stmt->execute()) {
            echo "Dados inseridos com sucesso!";
        } else {
            echo "Erro ao inserir dados: " . $stmt->error;
        }

    }

    // Fechar a conexão
    $stmt->close();
    mysqli_close($conn);
    exit(); // Evitar que o script continue executando após o redirecionamento
}
?>

