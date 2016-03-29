<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class CustomChartEndPoint extends SugarApi{

    public function registerApiRest()
    {
        return array(
            //GET
            'GetChartData' => array(
                //request type
                'reqType' => 'GET',

                //endpoint path
                'path' => array('ChartData','?'),

                //endpoint variables
                'pathVars' => array('','groupData'),

                //method to call
                'method' => 'getData',

                //short help string to be displayed in the help documentation
                'shortHelp' => 'An example of a GET endpoint',

                //long help to be displayed in the help documentation
                'longHelp' => 'EndPoint customizado pela InvestFlex para consultas',
            ),
            'GetChartFilter' => array(
                //request type
                'reqType' => 'GET',

                //endpoint path
//                'path' => array('ChartFilter', '?' , '?' ,'?'),
                'path' => array('ChartFilter', '?','?','?'),

                //endpoint variables
//                'pathVars' => array('','region','unit','agent'),
                'pathVars' => array('','groupData','region','period'),

                //method to call
                'method' => 'getFilter',

                //short help string to be displayed in the help documentation
                'shortHelp' => 'An example of a GET endpoint',

                //long help to be displayed in the help documentation
                'longHelp' => 'EndPoint customizado pela InvestFlex para consultas',
            ),
        );
    }

    public function getRegionais($args){
        $result=array();
        $result['Atendimentos']=array();

        $c=0;

            $queryResult = $GLOBALS['db'] -> query("SELECT DISTINCT krt_regional_c as R
                                from accounts_cstm INNER JOIN accounts on id=id_c
                                where deleted!=1");
            while($rows = $GLOBALS['db'] -> fetchByAssoc($queryResult)){
                $result['R'][$c]=$rows['R'];
                $this->getAtendimentos($result,$c,$args);
                $c=$c+1;
            }

        return $result;
    }

    public function getAtendimentos(&$result,$c,$args){
        $total=0;
        $evadidos=0;
        $retidos=0;

        if(isset($result['R'][$c])){
            $regional=$result['R'][$c];

        }

        if(isset($result['A'][$c])){
            $agentes=$result['A'][$c];
        }

        if(isset($result['U'][$c])){
            $unidades=$result['U'][$c];
        }

//        $filter=$args['period']!=""?"and date_entered between '".$args['period']."' and '".$args['period_end']."'":'';


        if(isset($regional)&& $args['period']==""){
        $sql="select krt_status_solicitacao_p_c as Status, count(*) as Atendimentos from krp_retencao_presencial
                                          INNER JOIN krp_retencao_presencial_cstm on id=id_c
                                          where deleted!=1 and sng_regional_rela_c='$regional' GROUP BY krt_status_solicitacao_p_c ";
        $queryResult = $GLOBALS['db'] -> query($sql);
        }else{
            if(isset($regional)&& $args['period']!="") {
                $sql="select krt_status_solicitacao_p_c as Status, count(*) as Atendimentos from krp_retencao_presencial
                                          INNER JOIN krp_retencao_presencial_cstm on id=id_c
                                          where deleted!=1 and sng_regional_rela_c='$regional' and date_entered between '".$args['period']."' and '".$args['period_end']."' GROUP BY krt_status_solicitacao_p_c ";
                $queryResult = $GLOBALS['db']->query($sql);

            }
        }
        if(isset($agentes)&& $args['period']==""){
            $queryResult = $GLOBALS['db'] -> query("select krt_status_solicitacao_p_c as Status, count(*) as Atendimentos from krp_retencao_presencial
                                          INNER JOIN krp_retencao_presencial_cstm on id=id_c
                                          where deleted!=1 and created_by='$agentes' GROUP BY krt_status_solicitacao_p_c ");
        }else{
            if(isset($agentes)&& $args['period']!="") {
                $queryResult = $GLOBALS['db'] -> query("select krt_status_solicitacao_p_c as Status, count(*) as Atendimentos from krp_retencao_presencial
                                          INNER JOIN krp_retencao_presencial_cstm on id=id_c
                                          where deleted!=1 and created_by='$agentes' and date_entered between '".$args['period']."' and '".$args['period_end']."' GROUP BY krt_status_solicitacao_p_c ");
            }
        }
        if(isset($unidades)&& $args['period']==""){
            $queryResult = $GLOBALS['db'] -> query("select krt_status_solicitacao_p_c as Status, count(*) as Atendimentos from krp_retencao_presencial
                                          INNER JOIN krp_retencao_presencial_cstm on id=id_c
                                          where deleted!=1 and sng_unidade_rela_c='$unidades' GROUP BY krt_status_solicitacao_p_c ");
        }else{
            if(isset($unidades)&& $args['period']!="") {
                $queryResult = $GLOBALS['db'] -> query("select krt_status_solicitacao_p_c as Status, count(*) as Atendimentos from krp_retencao_presencial
                                          INNER JOIN krp_retencao_presencial_cstm on id=id_c
                                          where deleted!=1 and sng_unidade_rela_c='$unidades' and date_entered between '".$args['period']."' and '".$args['period_end']."' GROUP BY krt_status_solicitacao_p_c ");
            }
        }
        if(isset($queryResult)){
//            $GLOBALS['log']->fatal(print_r($sql,true));

//            $GLOBALS['log']->fatal(print_r($queryResult,true));

            while($rows = $GLOBALS['db'] -> fetchByAssoc($queryResult)){
                /* 1=Evadido 2=Retido 3=Em Aberto */


                if($rows['Status']== 1){
                    $evadidos=$evadidos+$rows['Atendimentos'];

                }
                if($rows['Status']== 2){
                    $retidos=$retidos+$rows['Atendimentos'];

                }
                $total=$total+$rows['Atendimentos'];
            }

//            $GLOBALS['log']->fatal($evadidos,$retidos,$total);

            $percentual=$retidos!=0 ? $retidos*100/$total : 0;
            $result['Atendimentos']['Total'][$c]=$total;
            $result['Atendimentos']['Retidos'][$c]=$retidos;
            $result['Atendimentos']['Evadidos'][$c]=$evadidos;
            $result['Atendimentos']['Percentual'][$c]=$percentual;

        }


    }

    public function getAgentes($args){
        $result=array();
        $result['Atendimentos']=array();

        $c=0;
        $role_id="db0c40f4-9f5c-2f18-8969-561676af6ecc";
        $queryResult = $GLOBALS['db']-> query(
            "select user_id A from acl_roles_users
             where role_id = '$role_id'
             and deleted!=1;"
        );
        while($rows = $GLOBALS['db'] -> fetchByAssoc($queryResult)){
            $result['A'][$c]=$rows['A'];
            $this->getAtendimentos($result,$c,$args);
            $bean=BeanFactory::retrieveBean('Users',$rows['A']);
            $result['A'][$c] = $bean->full_name;
            $c=$c+1;

        }

        return $result;
    }

    public function getUnidades($args){
        $result=array();
        $result['Atendimentos']=array();

        $c=0;

        if($args['region']!='not'){
            $queryResult = $GLOBALS['db'] -> query("select id, name as U from accounts
                                              INNER JOIN accounts_cstm on id=id_c
                                              where krt_regional_c='".$args['region']."'");

        }else{
            $queryResult = $GLOBALS['db'] -> query("select id, name as U from accounts");

        }
        while($rows = $GLOBALS['db'] -> fetchByAssoc($queryResult)){
            $result['U'][$c]=$rows['U'];
            $this->getAtendimentos($result,$c,$args);
            $c=$c+1;
        }

        return $result;

    }

    public function getData($api,$args){

        $result=$this->geraGrafico($args);

        $json=json_encode($result);
        return $json;
    }

    public function geraGrafico($args){


        if(!isset($args['region'])){
            $args['region']='not';
        }
        $month=date('m');
        $year=date('Y');

        if(isset($args['period'])){
            switch($args['period']){
                case "month":
                    $args['period']="$year-$month-01";
                    $args['period_end']="$year-$month-31";
                    break;
                case "semester":
                    if($month>6){
                        $args['period']="$year-07-01";
                        $args['period_end']="$year-12-31";
                    }else{
                        $args['period']="$year-01-01";
                        $args['period_end']="$year-06-31";
                    }
                    break;
                case "previous":
                    if($month>6){
                        $args['period']="$year-01-01";
                        $args['period_end']="$year-06-31";
                    }else{
                        $year=$year-1;
                        $args['period']="$year-07-01";
                        $args['period_end']="$year-12-31";
                    }
                    break;
                default:
                    $args['period']="";
            }
        }else{
            $args['period']="";
        }




        switch($args['groupData']){
            case 'U':
                $title ="Atendimento x Unidades";
                $container = "#containerU";
                $xAxis=$this->getUnidades($args);
                $eixoX=$xAxis['U'];
//                $GLOBALS['log']->fatal(print_r($eixoX,true));

                break;
            case 'A':
                $title ="Atendimento x Agentes";
                $container = "#containerA";
                $xAxis=$this->getAgentes($args);
                $eixoX=$xAxis['A'];
                break;
            default:
                $title ="Atendimento x Regionais";
                $container = "#container";
                $xAxis=$this->getRegionais($args);
                $eixoX=$xAxis['R'];
        }


        $spiline=array(
            'type'=> 'spline',
            'name'=> 'Retidos',
            'data'=> $xAxis['Atendimentos']['Percentual'],
            'yAxis' => 1,
            'tooltip'=>array(
                'valueSuffix'=> ' %'
            )
        );
        if(!isset($xAxis['R'])){
            $xAxis['R']='';
        }
        $result=array(
            'container'=>$container,
            'title'=>$title,
            'xAxis'=>$eixoX,
            'series'=>array(
                array('type'=>'column','name'=>'Retidos','data'=>$xAxis['Atendimentos']['Retidos']),
                array('type'=>'column','name'=>'Evadidos','data'=>$xAxis['Atendimentos']['Evadidos']),
                array('type'=>'column','name'=>'Total','data'=>$xAxis['Atendimentos']['Total']),
                $spiline
            ),'Regionais'=>$xAxis['R']
        );

        return $result;
    }


    public  function getFilter($api,$args){
        $result=$this->geraGrafico($args);

        $json=json_encode($result);
        return $json;

    }
}
?>