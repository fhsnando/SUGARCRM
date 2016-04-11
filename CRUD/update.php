<?php
   require 'database.php';

  $id = null;
  if ( !empty($_GET['id'])) {
      $id = $_REQUEST['id'];
  }

  if ( null==$id ) {
      header("Location: index.php");
  }

  if ( !empty($_POST)) {
// Criando as validações de erros ao digitar
      $nameError = null;
      $crmlError = null;
      $phoneError = null;
      $skillError = null;

// Mantem os valores do metodo POST
      $name = $_POST['name'];
      $phone = $_POST['phone'];
      $crm = $_POST['crm'];
      $skill = $_POST['skill'];

// valida os campos ao inserir
      $valid = true;
      if (empty($name)) {
          $nameError = 'Por favor insira o Nome';
          $valid = false;
      }

      if (empty($crm)) {
          $crmError = 'Por favor insira o código de crm';
          $valid = false;
      }elseif(!is_numeric($crm)){
          $crmError = 'Por favor digite apenas números crm';
          $valid = false;
      }

      if (empty($phone)) {
          $phoneError = 'Por favor insira o Telefone';
          $valid = false;
      }elseif(!is_numeric($phone)){
          $phoneError = 'Por favor digite apenas números crm';
          $valid = false;
      }

      if (empty($skill)) {
          $skillError = 'Por favor selecione a Especialidade';
          $valid = false;
      }

// Atualiza os Dados
      if ($valid) {
          $pdo = Database::connect();
          $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          $sql = "UPDATE medicos  set name = '".$name."', crm = '".$crm."', phone = '".$phone."', skill = '".$skill."' WHERE id =  $id";
          $q = $pdo->prepare($sql);
          $q->execute(array($name,$phone,$crm,$skill,$id));
          Database::disconnect();
          header("Location: index.php");
      }
  } else {
      $pdo = Database::connect();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT * FROM medicos where id = $id";
      $q = $pdo->prepare($sql);
      $q->execute(array($id));
      $data = $q->fetch(PDO::FETCH_ASSOC);
      $name = $data['name'];
      $phone = $data['phone'];
      $crm = $data['crm'];
      $skill = $data['skill'];

      Database::disconnect();
  }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="ISO-8859-1">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.mask.js"></script>
</head>
<body>
<div class="container">
    <div class="span10 offset1">
        <div class="row">
            <h3>Atualizar o registro</h3>
        </div>
        <form class="form-horizontal" action="update.php?id=<?php echo $id?>" method="post">
            <div class="control-group <?php echo !empty($nameError)?'error':'';?>">
                <label class="control-label">Nome</label>
                <div class="controls">
                    <input name="name" type="text"  placeholder="Nome" value="<?php echo !empty($name)?$name:'';?>">
                    <?php if (!empty($nameError)): ?>
                        <span class="help-inline"><?php echo $nameError;?></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="control-group <?php echo !empty($phoneError)?'error':'';?>">
                <label class="control-label">Telefone</label>
                <div class="controls">
                    <input class="mask_phone" name="phone" type="text" placeholder="Telefone" value="<?php echo !empty($phone)?$phone:'';?>">
                    <?php if (!empty($phoneError)): ?>
                        <span class="help-inline"><?php echo $phoneError;?></span>
                    <?php endif;?>
                </div>
            </div>
            <div class="control-group <?php echo !empty($crmError)?'error':'';?>">
                <label class="control-label">CRM</label>
                <div class="controls">
                    <input name="crm" type="text"  placeholder="CRM" value="<?php echo !empty($crm)?$crm:'';?>">
                    <?php if (!empty($crmError)): ?>
                        <span class="help-inline"><?php echo $crmError;?></span>
                    <?php endif;?>
                </div>
            </div>
            <div class="control-group <?php echo !empty($skillError)?'error':'';?>">
                <label class="control-label">Especialidade</label>
                <div class="controls">
                    <select name="skill" placeholder="Especialidade">
                        <?php
                        $pdo2 = Database::connect();
                        $sql = 'SELECT * FROM  skills ORDER BY id ASC;;';
                        $selected='';
                        foreach ($pdo2->query($sql) as $row) {
                            if($skill==$row['id']){
                                $selected="selected";
                            }
                            echo '<option value='.$row['id'].' '.$selected.'>'.$row['skill_name'].'</option>';
                            $selected='';
                        }
                        Database::disconnect();
                        ?>
                    </select><br /><br />
                    <?php if (!empty($skillError)): ?>
                        <span class="help-inline"><?php echo $skillError;?></span>
                    <?php endif;?>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-success">Atualizar</button>
                <a class="btn default" href="index.php">Voltar</a>
            </div>
        </form>
    </div>
</div> <!-- /container -->
</body>
</html>