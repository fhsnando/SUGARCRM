<?php

echo 'Entrou Pré execute<br>';

$role_id="('b6ed9d65-1907-06d2-e893-56fa7c27d356')";
$dashboard_id="('2600e192-7adc-f10d-3404-56ec91c4dd0e')";
//$module="Cases";
$module="Home";
$view="";
//record, record, null

echo 'Consulta Usuários<br>';

$sql = "select user_id from acl_roles_users
       where role_id IN $role_id
       and deleted!=1;";
echo $sql."<br>";
$result_user = $GLOBALS['db']-> query($sql);


echo 'Consulta dashboards<br>';

$sql = "select * from dashboards
       where  id IN $dashboard_id
    and deleted!=1 and dashboard_module='$module'
    and dashboard_type='dashboard';";
//$sql = "select * from dashboards
//       where  id IN $dashboard_id
//    and deleted!=1 and dashboard_module='$module' and view_name='$view'
//    and dashboard_type='dashboard';";

$result_metadata = $GLOBALS['db']-> query($sql);
echo $sql."<br>";

$dashboads=array();
while ($row_dashboard = $GLOBALS['db'] -> fetchByAssoc($result_metadata)) {
    $dashboads[$row_dashboard['id']]['id']=$row_dashboard['id'];
    $dashboads[$row_dashboard['id']]['view_name']=$row_dashboard['view_name'];
    $dashboads[$row_dashboard['id']]['metadata']=html_entity_decode($row_dashboard['metadata']);
    $dashboads[$row_dashboard['id']]['name']=$row_dashboard['name'];
    $dashboads[$row_dashboard['id']]['assigned_user_id']=$row_dashboard['assigned_user_id'];
    $dashboads[$row_dashboard['id']]['created_by']=$row_dashboard['created_by'];
    $dashboads[$row_dashboard['id']]['modified_user_id']=$row_dashboard['modified_user_id'];
    $dashboads[$row_dashboard['id']]['dashboard_module']=$row_dashboard['dashboard_module'];
    $dashboads[$row_dashboard['id']]['dashboard_type']=$row_dashboard['dashboard_type'];

}


$GLOBALS['log']-> fatal(print_r($dashboads,true));

    while ($row_user = $GLOBALS['db'] -> fetchByAssoc($result_user)) {
//        echo 'user_id: '.$row_user['user_id'].' <br>';
        foreach($dashboads as $dashboard ){
            $metadata=$dashboard['metadata'];
            $name=$dashboard['name'];

            //verifica se esse metadata existe para o usuário
            $sql="select id from dashboards where assigned_user_id = '".$row_user['user_id']."'
            and deleted != 1  and metadata='$metadata' and NAME ='$name'  ";
            $fetch_metadata = $GLOBALS['db']-> query($sql);
            while ($row_fetch = $GLOBALS['db'] -> fetchByAssoc($fetch_metadata)) {
                $row_fetch=true;
            }

            //se não existe adiciona
            if($row_fetch!=true){
                //deleta dashlet com mesmo nome
                $sql="UPDATE dashboards SET deleted = 1 WHERE assigned_user_id = '".$row_user['user_id']."' AND NAME ='$name'";
                $GLOBALS['db']-> query($sql);

                $sql="INSERT INTO dashboards(id,view_name,metadata,name,assigned_user_id,created_by,
                  modified_user_id,dashboard_module,dashboard_type)
                  values (UUID(),'$view','$metadata','".$dashboard["name"]."','".$row_user["user_id"]."',
                '".$dashboard["created_by"]."','".$dashboard["modified_user_id"]."','".$dashboard["dashboard_module"]."',
                '".$dashboard["dashboard_type"]."');";
                $GLOBALS['db']-> query($sql);
                echo 'user_id: '.$row_user['user_id']." <strong> Adicionado dashboard: $name</strong><br>";
            }else{
                //se existe
                echo 'user_id: '.$row_user['user_id']." <strong> O dashboard: $name, já existe para o usuário </strong><br>";

            }
        }
    }




echo 'Fim<br>';
