<?php
require 'database.php';
$id = null;
if ( !empty($_GET['id'])) {
    $id = $_REQUEST['id'];
}

if ( null==$id ) {
    header("Location: index.php");
} else {
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM  medicos AS m INNER JOIN skills AS s ON m.skill = s.id where m.id = $id";
    $q = $pdo->prepare($sql);
    $q->execute(array($id));
    $data = $q->fetch(PDO::FETCH_ASSOC);
    Database::disconnect();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="ISO-8859-1">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
</head>

<body>
<div class="container">
    <div class="span10 offset1">
        <div class="row">
            <h3 align="center">Detalhes do registro</h3>
        </div>

        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>Nome</th>
                <th>CRM</th>
                <th>Telefone</th>
                <th>Especialidade</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><?php echo $data['name'];?></td>
                <td><?php echo $data['crm'];?></td>
                <td><?php echo $data['phone'];?></td>
                <td><?php echo $data['skill_name'];?></td>
            </tr>
            </tbody>
        </table>
        <div class="form-actions">
            <a class="btn default" href="index.php">Back</a>
        </div>
        </div>
    </div>
</div> <!-- /container -->
</body>
</html>