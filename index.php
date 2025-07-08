<?php
session_start();

// Se já está logado, redireciona para busca.php (a página principal após login)
if (isset($_SESSION['id'])) {
    header('Location: busca.php');
    exit();
}

include('conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (empty($_POST['email'])) {
        echo "Preencha seu e-mail";
    } else if (empty($_POST['senha'])) {
        echo "Preencha sua senha";
    } else {

        $email = $mysqli->real_escape_string($_POST['email']);
        $senha = $mysqli->real_escape_string($_POST['senha']);

        // **Importante: para maior segurança, a senha deve ser armazenada hashada e aqui usar password_verify**
        // Aqui deixei do jeito que tinha, mas recomendo futuramente atualizar o sistema de senha.

        $sql_code = "SELECT * FROM usuarios WHERE email = '$email' AND senha = '$senha'";
        $sql_query = $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);

        if ($sql_query->num_rows === 1) {

            $usuario = $sql_query->fetch_assoc();

            $_SESSION['id'] = $usuario['id'];
            $_SESSION['nome'] = $usuario['nome'];

            header("Location: busca.php");
            exit();

        } else {
            echo "Falha ao logar! E-mail ou senha incorretos";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="shortcut icon" href="./img/logo.png" type="image/x-icon" />
    <title>Planilho.Net</title>
    <link rel="stylesheet" href="login.css" />
</head>
<body class="d-flex justify-content-center align-items-center min-vh-100 bg-dark-subtle text-center">

    <div class="container">
        <div id="login" class="border rounded p-3 bg-body-tertiary shadow-sm">
            <img src="./img/logo.png" alt="logo" class="img-fluid" />
            <h2 class="mb-3">Bem-vindo ao <strong>Planilho.Net</strong>!</h2>
            <h3 class="log1 mb-4">Acesse a sua conta</h3>

            <form action="" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Usuário</label>
                    <input type="text" name="email" id="email" class="form-control" placeholder="Usuário" required />
                </div>

                <div class="mb-3">
                    <label for="senha" class="form-label">Senha</label>
                    <input type="password" name="senha" id="senha" class="form-control" placeholder="Senha" required />
                </div>

                <button type="submit" class="btn btn-dark w-100">Entrar</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
