<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="ISO-8859-1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery-1.6.min.js"></script>
    <script src="js/table2CSV.js"></script>
    <script src="js/sorttable.js"></script>
    <script src="js/jquery.battatech.excelexport.js"></script>


    <style>
        .search{
            display: block; width: 350px; left: 380px; top: 5px; z-index: 7005; position: absolute;
            background-color: lightgray;
            text-align: center;

        }
    </style>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#btnExport").click(function () {
                $("#tblExport").battatech_excelexport({
                    containerid: "tblExport"
                    , datatype: 'table'
                });
            });
        });
        $(document).ready(function() {

            $('.table').each(function() {
                var $table = $(this);

                var $button = $("#export");

                $button.click(function() {
                    var csv = $table.table2CSV({delivery:'value'});
                    window.location.href = 'data:text/csv;charset=ISO-8859-1,'
                        + encodeURIComponent(csv);
                });
            });
        })
    </script>


</head>
<body>
<div class="container">
    <div class="row">
        <h3>Sistema ItFuture</h3>
    </div>
    <div class="row">
        <a href="create.php" class="btn btn-success">Criar</a>
        <a  href="index.php?search=true" class="btn btn-success">Procurar</a>
        <a  href="index.php" class="btn btn-success">Mostrar Todos</a>
        <a  href="skills.php" class="btn btn-success">Especialidades</a>
        <a  id="export" class="btn btn-success">Exportar para CSV</a>
        <a  id="btnExport" class="btn btn-success">Exportar para Excel</a></p>


        <table id="tblExport" class="table table-striped table-bordered sortable">
            <thead>
            <tr>
                <th>Nome</th>
                <th>CRM</th>
                <th>Telefone</th>
                <th>Especialidade</th>
            </tr>
            </thead>
            <tbody>
            <?php include 'database.php';
            $params=false;
            $search=null;

            if ( !empty($_GET['search'])) {
                $search = $_REQUEST['search'];
            }

            if ( !empty($_POST)) {
                if ( !empty($_POST)) {
                    $crm = $_POST['crm'];
                    $params=true;
                }

                if (empty($crm)) {
                    $crmError = 'Por favor insira o código de crm';
                    $params = false;
                }elseif(!is_numeric($crm)){
                    $crmError = 'Por favor digite apenas números crm';
                    $params = false;
                }
            }



            if(!$params){
                $pdo = Database::connect();
                $sql = 'SELECT *, m.id AS IDM FROM  medicos AS m INNER JOIN skills AS s ON m.skill = s.id ORDER BY m.id DESC;';
                foreach ($pdo->query($sql) as $row) {
                    echo '<tr>';
                    echo '<td>'. $row['name'] . '</td>';
                    echo '<td>'. $row['crm'] . '</td>';
                    echo '<td>'. $row['phone'] . '</td>';
                    echo '<td>'. $row['skill_name'] . '</td>';
                    echo '<td width=250>';
                    echo '<a class="btn default" href="read.php?id='.$row['IDM'].'">Ver</a>';
                    echo '&nbsp;';
                    echo '<a class="btn btn-success" href="update.php?id='.$row['IDM'].'">Atualizar</a>';
                    echo '&nbsp;';
                    echo '<a class="btn btn-danger" href="delete.php?id='.$row['IDM'].'">Deletar</a>';
                    echo '</td>';
                    echo '</tr>';
                }
                Database::disconnect();
            }else{
                $pdo = Database::connect();
                $sql = 'SELECT *, m.id AS IDM FROM  medicos AS m INNER JOIN skills AS s ON m.skill = s.id where crm="'.$crm.'" ORDER BY m.id DESC;';
                foreach ($pdo->query($sql) as $row) {
                    echo '<tr>';
                    echo '<td>'. $row['name'] . '</td>';
                    echo '<td>'. $row['crm'] . '</td>';
                    echo '<td>'. $row['phone'] . '</td>';
                    echo '<td>'. $row['skill_name'] . '</td>';
                    echo '<td width=250>';
                    echo '<a class="btn default" href="read.php?id='.$row['IDM'].'">Ver</a>';
                    echo '&nbsp;';
                    echo '<a class="btn btn-success" href="update.php?id='.$row['IDM'].'">Atualizar</a>';
                    echo '&nbsp;';
                    echo '<a class="btn btn-danger" href="delete.php?id='.$row['IDM'].'">Deletar</a>';
                    echo '</td>';
                    echo '</tr>';
                }
                Database::disconnect();
            }

            ?>
            </tbody>
        </table>
    </div>
</div> <!-- /container -->
<?php if (isset($search)): ?>

<div class="search">
    <form class="form-horizontal" action="index.php?search=true" method="post">
        <div class="control-group">
            <label class="control-label">digite o CRM:</label>
            <div class="controls">
                <p><input name="crm" type="text"  placeholder="CRM" value="<?php echo !empty($crm)?$crm:'';?>"></p>
                <?php if (!empty($crmError)): ?>
                    <span class="help-inline"><?php echo $crmError;?></span>
                <?php endif; ?>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-success">Procurar</button>
            <a class="btn default" href="index.php">Cancelar</a>
        </div>
    </form>
</div>

<?php endif; ?>
</body>

</html>