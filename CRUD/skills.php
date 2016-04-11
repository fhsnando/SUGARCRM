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
        <a href="skills.php?create=true" class="btn btn-success">Criar</a>
        <a  href="skills.php?search=true" class="btn btn-success">Procurar</a>
        <a  href="index.php" class="btn btn-success">Médicos</a>
        <a  id="export" class="btn btn-success">Exportar para CSV</a>
        <a  id="btnExport" class="btn btn-success">Exportar para Excel</a></p>


        <table id="tblExport" class="table table-striped table-bordered sortable">
            <thead>
            <tr>
                <th>Codigo</th>
                <th>Especialidade</th>
            </tr>
            </thead>
            <tbody>
            <?php include 'database.php';
            $params=false;
            $params_skill=false;
            $search=null;
            $create=null;
            $delete=null;
            $idd=null;


            if ( !empty($_GET['search'])) {
                $search = $_REQUEST['search'];
            }

            if ( !empty($_GET['create'])) {
                $create = $_REQUEST['create'];
            }
            if ( !empty($_GET['delete'])) {
                $delete = $_REQUEST['delete'];
            }

            if ( !empty($_POST)) {
                $id=null;
                $skill=null;

                if ( isset($_POST['id'])) {
                    $id = $_POST['id'];
                    $params=true;
                    echo $id;
                }

                if ( isset($_POST['idd'])) {
                    $idd = $_POST['idd'];
                }

                if ( isset($_POST['skill'])) {
                    $skill = $_POST['skill'];
                    $params_skill=true;
                    $create=null;
                }

                if (empty($skill)) {
                    $skillError = 'Por favor digite um nome';
                    $params_skill = false;
                }

                if (empty($id)) {
                    $idError = 'Por favor insira o código';
                    $params = false;
                }elseif(!is_numeric($id)){
                    $idError = 'Por favor digite apenas números';
                    $params = false;
                }
            }

            if($params_skill){
                $pdo = Database::connect();
                $sql = 'INSERT INTO skills (skill_name) VALUE ("'.$skill.'");';
                $pdo->query($sql);
                Database::disconnect();
            }

            if($idd){
                $pdo = Database::connect();
                $sql = 'delete from skills WHERE id='.$idd.';';
                $pdo->query($sql);
                Database::disconnect();
            }



            if(!$params){
                $pdo = Database::connect();
                $sql = 'SELECT * from skills ORDER BY id ASC;';
                foreach ($pdo->query($sql) as $row) {
                    echo '<tr>';
                    echo '<td>'. $row['id'] . '</td>';
                    echo '<td>'. $row['skill_name'] . '</td>';
                    echo '<td width=150>';
                    echo '<a class="btn btn-danger" href="skills.php?delete='.$row['id'].'">Deletar</a>';
                    echo '</td>';
                    echo '</tr>';
                }
                Database::disconnect();
            }else{
                $pdo = Database::connect();
                $sql = 'SELECT * from skills where id="'.$id.'" ORDER BY id ASC;';
                foreach ($pdo->query($sql) as $row) {
                    echo '<tr>';
                    echo '<td>'. $row['id'] . '</td>';
                    echo '<td>'. $row['skill_name'] . '</td>';
                    echo '<td width=150>';
                    echo '<a class="btn btn-danger" href="skills.php?delete='.$row['id'].'">Deletar</a>';
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
        <form class="form-horizontal" action="skills.php?search=true" method="post">
            <div class="control-group">
                <label class="control-label">Digite o código:</label>
                <div class="controls">
                    <p><input name="id" type="text"  placeholder="Código" value="<?php echo !empty($id)?$id:'';?>"></p>
                    <?php if (!empty($idError)): ?>
                        <span class="help-inline"><?php echo $idError;?></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-success">Procurar</button>
                <a class="btn default" href="skills.php">Cancelar</a>
            </div>
        </form>
    </div>

<?php endif; ?>

<?php if (isset($create)): ?>

    <div class="search">
        <form class="form-horizontal" action="skills.php?create=true" method="post">
            <div class="control-group">
                <label class="control-label">Criar Especialidade:</label>
                <div class="controls">
                    <p><input name="skill" type="text"  placeholder="Especialidade" value="<?php echo !empty($skill)?$skill:'';?>"></p>
                    <?php if (!empty($skillError)): ?>
                        <span class="help-inline"><?php echo $skillError;?></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-success">Criar</button>
                <a class="btn default" href="skills.php">Cancelar</a>
            </div>
        </form>
    </div>

<?php endif; ?>

<?php if (isset($delete)): ?>
    <div class="search">
        <div class="row">
            <h3>Deletar um registro</h3>
        </div>
        <form class="form-horizontal" action="skills.php" method="post">
            <input type="hidden" name="idd" value="<?php echo $delete;?>"/>
            <p class="alert alert-error">Tem certeza que deseja apagar?</p>
            <div class="form-actions">
                <button type="submit" class="btn btn-danger">Sim</button>
                <a class="btn" href="skills.php">Não</a>
            </div>
        </form>
    </div>
<?php endif; ?>

</body>
</html>