<?php
require 'database.php';
$id = 0;

if ( !empty($_GET['id'])) {
    $id = $_REQUEST['id'];
}

if ( !empty($_POST)) {
// keep track post values
    $id = $_POST['id'];

// delete data  
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "DELETE FROM medicos  WHERE id = $id";
    $q = $pdo->prepare($sql);
    $q->execute(array($id));
    Database::disconnect();

    header("Location: index.php");
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
            <h3>Deletar um registro</h3>
        </div>
        <form class="form-horizontal" action="delete.php" method="post">
            <input type="hidden" name="id" value="<?php echo $id;?>"/>
            <p class="alert alert-error">Tem certeza que deseja apagar?</p>
            <div class="form-actions">
                <button type="submit" class="btn btn-danger">Sim</button>
                <a class="btn" href="index.php">Não</a>
            </div>
        </form>
    </div>
</div> <!-- /container -->
</body>
</html>