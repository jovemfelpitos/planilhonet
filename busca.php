<?php
include('conexao.php');
include('protect.php');  // protege a página para só usuários logados
?>

<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="style.css" rel="stylesheet" />
    <link rel="shortcut icon" href="./img/logo.png" type="image/x-icon" />
    <title>Planilho</title>
    <link rel="stylesheet" href="/inicio.css" />
</head>
<body class="d-flex flex-column min-vh-100 bg-body-tertiary">

<header id="header" class="d-flex flex-column align-items-center py-4">
    <div class="mb-5">
        <img src="./img/logocompleta.png" alt="logo" id="logo" class="img-fluid" style="width: 450px;"/>
    </div>
    <form action="" class="d-flex align-items-center">
        <input
            name="busca"
            value="<?php if (isset($_GET['busca'])) echo htmlspecialchars($_GET['busca']); ?>"
            placeholder="Nome, CPF ou Telefone."
            type="text"
            class="form-control me-2"
            required
        />
        <input
            name="coef"
            value="<?php if (isset($_GET['coef'])) echo str_replace('.', ',', htmlspecialchars($_GET['coef'])); ?>"
            placeholder="Coeficiente do dia"
            type="text"
            inputmode="decimal"
            pattern="[0-9]+([,][0-9]+)?"
            class="form-control me-2"
            required
        />
        <button class="btn btn-dark me-2" type="submit">Pesquisar</button>
        <button class="btn btn-dark me-2" type="button" onclick="window.location.href='busca.php'">Limpar</button>
        <button class="btn btn-danger" type="button" onclick="window.location.href='logout.php'">Sair</button>
    </form>
</header>
<hr>

<?php
if (!isset($_GET['busca'])) {
    // Nenhuma busca foi feita
} else {
    $pesquisa = $mysqli->real_escape_string($_GET['busca']);
    $coefRaw = isset($_GET['coef']) ? $_GET['coef'] : '0';
    $coef = floatval(str_replace(',', '.', $coefRaw));

    $sql_code = "SELECT * 
        FROM bd 
        WHERE cpf LIKE '%$pesquisa%' 
        OR nome LIKE '%$pesquisa%' 
        OR beneficio LIKE '%$pesquisa%' 
        OR fone1 LIKE '%$pesquisa%'  
        OR fone2 LIKE '%$pesquisa%'  
        OR fone3 LIKE '%$pesquisa%' 
        OR fone4 LIKE '%$pesquisa%' 
        OR fone5 LIKE '%$pesquisa%' 
        OR fone6 LIKE '%$pesquisa%' 
        LIMIT 3";

    $sql_query = $mysqli->query($sql_code) or die("ERRO ao consultar! " . $mysqli->error);

    if ($sql_query->num_rows == 0) {
        echo '<div class="alert alert-warning" role="alert">Nenhum resultado encontrado...</div>';
    } else {
        while ($dados = $sql_query->fetch_assoc()) {
            $salario = floatval($dados['salario']);
            $especie = $dados['especie'];

            $usa30 = in_array($especie, ['87', '88']);
            $percentualMargem = $usa30 ? 0.30 : 0.35;
            $parcelaMargem = $salario * $percentualMargem;
            $valorLiberado = $coef > 0 ? $parcelaMargem / $coef : 0;

            $parcelaCartao = $salario * 0.05;
            $valorSaque = $parcelaCartao * 32 * 0.70;
            $totalEmprestimo = $valorLiberado + $valorSaque;
?>
            <div class="container my-4 p-4 border rounded bg-dark">
                <h3 class="mb-4">Detalhes do Resultado</h3>

                <table class="table table-bordered mb-4">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>CPF</th>
                            <th>Nascimento</th>
                            <th>Idade</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo htmlspecialchars($dados['nome']); ?></td>
                            <td><?php echo htmlspecialchars($dados['cpf']); ?></td>
                            <td><?php echo date("d/m/Y", strtotime($dados['nascimento'])); ?></td>
                            <td><?php echo 2025 - date("Y", strtotime($dados['nascimento'])); ?></td>
                        </tr>
                    </tbody>
                </table>

                <table class="table table-bordered mb-4">
                    <thead>
                        <tr>
                            <th>Telefone 1</th>
                            <th>Telefone 2</th>
                            <th>Telefone 3</th>
                            <th>Telefone 4</th>
                            <th>Telefone 5</th>
                            <th>Telefone 6</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo htmlspecialchars($dados['fone1']); ?></td>
                            <td><?php echo htmlspecialchars($dados['fone2']); ?></td>
                            <td><?php echo htmlspecialchars($dados['fone3']); ?></td>
                            <td><?php echo htmlspecialchars($dados['fone4']); ?></td>
                            <td><?php echo htmlspecialchars($dados['fone5']); ?></td>
                            <td><?php echo htmlspecialchars($dados['fone6']); ?></td>
                        </tr>
                    </tbody>
                </table>

                <table class="table table-bordered mb-4">
                    <thead>
                        <tr>
                            <th>Benefício</th>
                            <th>Espécie</th>
                            <th>DIB</th>
                            <th>Desbloqueio</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo htmlspecialchars($dados['beneficio']); ?></td>
                            <td><?php echo htmlspecialchars($dados['especie']); ?></td>
                            <td><?php echo date("d/m/Y", strtotime($dados['dib'])); ?></td>
                            <td><?php echo date("d/m/Y", strtotime($dados['ddb'])); ?></td>
                        </tr>
                    </tbody>
                </table>

                <table class="table table-bordered mb-4">
                    <thead>
                        <tr>
                            <th>UF</th>
                            <th>Cidade</th>
                            <th>Bairro</th>
                            <th>CEP</th>
                            <th>Endereço</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo htmlspecialchars($dados['uf']); ?></td>
                            <td><?php echo htmlspecialchars($dados['cidade']); ?></td>
                            <td><?php echo htmlspecialchars($dados['bairro']); ?></td>
                            <td><?php echo htmlspecialchars($dados['cep']); ?></td>
                            <td><?php echo htmlspecialchars($dados['endereco']); ?></td>
                        </tr>
                    </tbody>
                </table>

                <h4 class="mt-4 mb-3">Cálculos de Empréstimo</h4>
                <table class="table table-bordered mb-4">
                    <thead>
                        <tr>
                            <th>Salário</th>
                            <th>Parcela Margem (<?php echo $usa30 ? '30%' : '35%'; ?>)</th>
                            <th>Valor Liberado</th>
                            <th>Parcela Cartão (5%)</th>
                            <th>Valor do Saque</th>
                            <th>Total Empréstimo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>R$ <?php echo number_format($salario, 2, ',', '.'); ?></td>
                            <td>R$ <?php echo number_format($parcelaMargem, 2, ',', '.'); ?></td>
                            <td>R$ <?php echo number_format($valorLiberado, 2, ',', '.'); ?></td>
                            <td>R$ <?php echo number_format($parcelaCartao, 2, ',', '.'); ?></td>
                            <td>R$ <?php echo number_format($valorSaque, 2, ',', '.'); ?></td>
                            <td>R$ <?php echo number_format($totalEmprestimo, 2, ',', '.'); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
<?php
        }
    }
}
?>

</body>
</html>
