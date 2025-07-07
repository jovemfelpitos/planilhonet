<?php

include('conexao.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planilh</title>
</head>
<body>
    <h1>Planilho</h1>
    <form action="">
        <input name="busca" value="<?php if(isset($_GET['busca'])) echo $_GET['busca']; ?>" placeholder="Digite os termos de pesquisa" type="text">
        <button type="submit">Pesquisar</button>
    </form>
    <br>
    <table  border="1">

        <tr>
            <th>CPF</th>
            <th>Nome</th>
            <th>Nascimento</th>
            <th>Beneficio</th>
            <th>Especie</th>
            <th>DIB</th>
            <th>Salario</th>
            <th>Endereço</th>
            <th>Bairro</th>
            <th>cidade</th>
            <th>uf</th>
            <th>cep</th>
            <th>telefone1</th>
            <th>telefone2</th>
            <th>telefone3</th>
            <th>telefone4</th>
            <th>telefone5</th>
            <th>telefone6</th>
            <th>email</th>
        </tr>
        
        <?php
        if (!isset($_GET['busca'])) {
            ?>
        <tr>
            <td colspan="19">Digite algo para pesquisar...</td>
        </tr>
        <?php
        } else {
            $pesquisa = $mysqli->real_escape_string($_GET['busca']);
            $sql_code = "SELECT * 
                FROM bd 
                WHERE CPF LIKE '%$pesquisa%' 
                OR Nome LIKE '%$pesquisa%'
                OR telefone1 LIKE '%$pesquisa%'  
                OR telefone2 LIKE '%$pesquisa%' 
                OR telefone3 LIKE '%$pesquisa%' ";
            $sql_query = $mysqli->query($sql_code) or die("ERRO ao consultar! " . $mysqli->error); 
            
            if ($sql_query->num_rows == 0) {
                ?>
            <tr>
                <td colspan="3">Nenhum resultado encontrado...</td>
            </tr>
            <?php
            } else {
                while($dados = $sql_query->fetch_assoc()) {
                    ?>
                    <tr>
                        <td><?php echo $dados['CPF']; ?></td>
                        <td><?php echo $dados['Nome']; ?></td>
                        <td><?php echo $dados['Nascimento']; ?></td>
                        <td><?php echo $dados['Beneficio']; ?></td>
                        <td><?php echo $dados['Especie']; ?></td>
                        <td><?php echo $dados['DIB']; ?></td>
                        <td><?php echo $dados['Salario']; ?></td>
                        <td><?php echo $dados['endereco']; ?></td>
                        <td><?php echo $dados['Bairro']; ?></td>
                        <td><?php echo $dados['Cidade']; ?></td>
                        <td><?php echo $dados['UF']; ?></td>
                        <td><?php echo $dados['CEP']; ?></td>
                        <td><?php echo $dados['Telefone1']; ?></td>
                        <td><?php echo $dados['Telefone2']; ?></td>
                        <td><?php echo $dados['Telefone3']; ?></td>
                        <td><?php echo $dados['Telefone4']; ?></td>
                        <td><?php echo $dados['Telefone5']; ?></td>
                        <td><?php echo $dados['Telefone6']; ?></td>
                        <td><?php echo $dados['Email']; ?></td>
                    </tr>
                    <?php
                }
            }
            ?>
        <?php
        } ?>
    </table>
</body>
</html>