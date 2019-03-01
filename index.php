<?php
session_start();//iniciando sessão
require 'config.php';

if(isset($_SESSION['banco']) && empty($_SESSION['banco']) == false) {//verificando se existe algum usuario conectado
    $id = $_SESSION['banco'];

    $sql = $pdo->prepare("SELECT * FROM contas WHERE id = :id");
    $sql->bindValue(":id", $id);
    $sql->execute();

    if($sql->rowCount() > 0) {
        $info = $sql->fetch();
    }else{
        header("location: login.php");
        exit; 
    }

} else {
    header("location: login.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Caixa Eletrônico</title>
</head>

<body>
    <h1>Banco Douradina e Associados</h1> <br>

    Titular: <?php echo $info['titular']; ?> <br>
    Agencia: <?php echo $info['agencia']; ?> <br>
    Conta: <?php echo $info['conta']; ?> <br>
    Saldo: <?php echo $info['saldo']; ?> <br>

    <a href="logout.php">Sair</a>

    <hr> <br>
    <h3>Movimentação/Extratos</h3>
    
    <a href="add_transacao.php">Adicionar Transação</a> <br><br>

    <table border="1" width="400">
        <tr>
            <th>Data</th>
            <th>Valor</th>
        </tr>

        <?php
		$sql = $pdo->prepare("SELECT * FROM historico WHERE id_conta = :id_conta");
		$sql->bindValue(":id_conta", $id);
		$sql->execute();

		if($sql->rowCount() > 0) {
			foreach($sql->fetchAll() as $item) {
				?>
				<tr>
					<td><?php echo date('d/m/Y H:i', strtotime($item['data_operacao'])); ?></td>
					<td>
						<?php if($item['tipo'] == '0'): ?>
						<font color="green">R$ <?php echo $item['valor'] ?></font>
						<?php else: ?>
						<font color="red">- R$ <?php echo $item['valor'] ?></font>
						<?php endif; ?>
					</td>
				</tr>
				<?php
			}
		}
		?>
	</table>

</body>

</html>