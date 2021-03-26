<?php
class Model_mantenimiento extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }
    //lista de organismo financiador
    function lista_of()
    {
        $this->db->WHERE('of_estado',1);
        $this->db->from('organismofinanciador');
        $this->db->ORDER_BY("of_codigo,of_gestion", "DESC");
        $query = $this->db->get();
        return $query->result_array();
    }
    //lista de organismo financiador por id
    function dato_of($id)
    {
        $this->db->WHERE('of_id',$id);
        $this->db->FROM('organismofinanciador');
        $query = $this->db->get();
        return $query->result_array();
    }
    //verificar si existe el codigo of
    function verificar_ofcod($cod,$gestion){
        //empezamos una transacción
        $this->db->trans_begin();
        $this->db->WHERE('of_codigo',$cod);
        $this->db->WHERE('of_estado',1);
        $this->db->WHERE('of_gestion',$gestion);
        $this->db->FROM('organismofinanciador');
        $query = $this->db->get();
        //comprobamos si se han llevado a cabo correctamente todas
        //las consultas
        if ($this->db->trans_status() === FALSE)
        {
            //si ha habido algún error lo debemos mostrar aquí
            $this->db->trans_rollback();
        }else{
            //correcto
            $this->db->trans_commit();
            return $query->result_array();
        }

    }
    //agregar organizacion financiado
    function add_of($ofdescripcion,$ofsigla,$ofcodigo,$ofgestion)
    {
        $this->db->trans_begin();
        $id_antes = $this->generar_id('organismofinanciador','of_id');
        $nuevo_id = $id_antes[0]['id_antes'];
        $nuevo_id++;
        $data = array(
            'of_id' => $nuevo_id,
            'of_descripcion' => strtoupper( $ofdescripcion),
            'of_sigla' => strtoupper($ofsigla),
            'of_estado' => 1,
            'of_gestion' => $ofgestion,
            'of_codigo' => $ofcodigo,
        );
        $this->db->insert('organismofinanciador',$data);
        if ($this->db->trans_status() === FALSE)
        {
            return "false";
        }else{
            //correcto
            $this->db->trans_commit();
            return "true";
        }
    }
    function generar_id($tabla,$id){
        $query =$this->db->query('SELECT MAX('.$id.') AS id_antes FROM '.$tabla);
        return $query->result_array();
       // return $query->row_array();
    }
    function mod_of($ofid,$ofdescripcion,$ofsigla,$ofgestion,$ofcodigo){
        $ofdescripcion = strtoupper($ofdescripcion);
        $ofsigla = strtoupper($ofsigla);
       $sql = "UPDATE organismofinanciador SET of_descripcion='".$ofdescripcion."',of_sigla='".$ofsigla."',of_gestion=".$ofgestion.",of_codigo=".$ofcodigo." WHERE of_id=".$ofid;
        $this->db->query($sql);
    }
    //===================================lista de fuente financiamiento
    function lista_ff()
    {
        $this->db->WHERE('ff_estado',1);
        $this->db->from('fuentefinanciamiento');
        $this->db->order_by("ff_codigo,ff_gestion", "DESC");
        $query = $this->db->get();
        return $query->result_array();
    }
    //lista de fuente financiamiento por id
    function dato_ff($id)
    {
        $this->db->WHERE('ff_id',$id);
        $this->db->from('fuentefinanciamiento');
        $query = $this->db->get();
        return $query->result_array();
    }
    function mod_ff($ffid,$ffdescripcion,$ffsigla,$ffgestion){
        $ffdescripcion = strtoupper($ffdescripcion);
        $ffsigla = strtoupper($ffsigla);
        $sql = "UPDATE fuentefinanciamiento SET ff_descripcion='".$ffdescripcion."',ff_sigla='".$ffsigla."',ff_gestion=".$ffgestion." WHERE ff_id=".$ffid;
        $this->db->query($sql);
    }
    function add_ff($ffdescripcion,$ffsigla,$ffcodigo,$ffgestion){
        $id_antes = $this->generar_id('fuentefinanciamiento','ff_id');
        $nuevo_id = $id_antes[0]['id_antes'];
        $nuevo_id++;
        $data = array(
            'ff_id' => $nuevo_id,
            'ff_descripcion' => strtoupper( $ffdescripcion),
            'ff_sigla' => strtoupper($ffsigla),
            'ff_estado' => 1,
            'ff_codigo' => $ffcodigo,
            'ff_gestion' => $ffgestion,
        );
        $this->db->insert('fuentefinanciamiento',$data);
    }


    function verificar_ffcod($cod,$gestion){
        //empezamos una transacción
        $this->db->trans_begin();
        $this->db->WHERE('ff_codigo',$cod);
        $this->db->WHERE('ff_estado',1);
        $this->db->WHERE('ff_gestion',$gestion);
        $this->db->FROM('fuentefinanciamiento');
        $query = $this->db->get();
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
        }else{
            //correcto
            $this->db->trans_commit();
            return $query->result_array();
        }
    }
    //=============================================lista entidad de transferencia
    function lista_et()
    {
        $this->db->WHERE('et_estado',1);
        $this->db->from('entidadtransferencia');
        $this->db->order_by("et_codigo,et_gestion", "DESC");
        $query = $this->db->get();
        return $query->result_array();
    }
    function verificar_etcod($cod,$fecha){
        $this->db->trans_begin();
        $this->db->WHERE('et_codigo',$cod);
        $this->db->WHERE('et_estado',1);
        $this->db->WHERE('et_gestion',$fecha);
        $this->db->FROM('entidadtransferencia');
        $query = $this->db->get();
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            return $query->result_array();
        }
    }
    function add_et($etdescripcion,$etsigla,$etcodigo,$etgestion){
        $id_antes = $this->generar_id('entidadtransferencia','et_id');
        $nuevo_id = $id_antes[0]['id_antes'];
        $nuevo_id++;
        $data = array(
            'et_id' => $nuevo_id,
            'et_descripcion' => strtoupper( $etdescripcion),
            'et_sigla' => strtoupper($etsigla),
            'et_estado' => 1,
            'et_codigo' => $etcodigo,
            'et_gestion' => $etgestion,
        );
        $this->db->insert('entidadtransferencia',$data);
    }
    //lista de fuente financiamiento por id
    function dato_et($id)
    {
        $this->db->WHERE('et_id',$id);
        $this->db->from('entidadtransferencia');
        $query = $this->db->get();
        return $query->result_array();
    }
    function mod_et($etid,$etdescripcion,$etsigla,$etgestion,$etcodigo){
        $etdescripcion = strtoupper($etdescripcion);
        $etsigla = strtoupper($etsigla);
        $sql = "UPDATE entidadtransferencia SET et_descripcion='".$etdescripcion."',et_sigla='".$etsigla."',et_gestion=".$etgestion." ,et_codigo=".$etcodigo." WHERE et_id=".$etid;
        $this->db->query($sql);
    }
    //============================================lista partidas
    function lista_p()
    {
        $this->db->from('partidas');
        $this->db->WHERE("par_id != ",0);
        $this->db->order_by("par_codigo,par_gestion", "DESC");
        $query = $this->db->get();
        return $query->result_array();
    }
    function verificar_parcod($cod,$fecha){
        $this->db->trans_begin();
        $this->db->WHERE('par_codigo',$cod);
        $this->db->WHERE('par_gestion',$fecha);
        $this->db->FROM('partidas');
        $query = $this->db->get();
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            return $query->result_array();
        }
    }
    function es_padre($id)
    {
        $this->db->where('par_depende',0);
        $this->db->where('par_id',$id);
        $this->db->from('partidas');
        $this->db->order_by("par_id", "ASC");
        $query = $this->db->get();
        return $query->result_array();
    }
    function lista_padres()
    {
        $this->db->where('par_depende',0);
        $this->db->from('partidas');
        $this->db->order_by("par_id", "ASC");
        $query = $this->db->get();
        return $query->result_array();
    }
    function add_par_independiente($par_nombre,$par_codigo,$par_gestion){
        $id_antes = $this->generar_id('partidas','par_id');
        $nuevo_id = $id_antes[0]['id_antes'];
        $nuevo_id++;
        $data = array(
            'par_id' => $nuevo_id,
            'par_nombre' => strtoupper( $par_nombre),
            'par_depende' => 0,
            'par_codigo' => $par_codigo,
            'par_gestion' => $par_gestion,
        );
        $this->db->insert('partidas',$data);
    }
    function add_par_dependiente($par_nombre,$par_padre,$par_codigo,$par_gestion){
        $id_antes = $this->generar_id('partidas','par_id');
        $nuevo_id = $id_antes[0]['id_antes'];
        $nuevo_id++;
        $data = array(
            'par_id' => $nuevo_id,
            'par_nombre' => strtoupper( $par_nombre),
            'par_depende' => $par_padre,
            'par_codigo' => $par_codigo,
            'par_gestion' => $par_gestion,
        );
        $this->db->insert('partidas',$data);
    }
    function dato_par($id)
    {
        $this->db->WHERE('par_id',$id);
        $this->db->from('partidas');
        $query = $this->db->get();
        return $query->result_array();
    }
    function dato_par_codigo($cod){
        $this->db->WHERE('par_codigo',$cod);
        $this->db->from('partidas');
        $query = $this->db->get();
        return $query->result_array();
    }
    function mod_par($par_id,$par_nombre,$par_gestion,$par_codigo){
        $par_nombre = strtoupper($par_nombre);
        $sql = "UPDATE partidas SET par_nombre='".$par_nombre."' ,par_gestion=".$par_gestion." ,par_codigo=".$par_codigo." WHERE par_id=".$par_id;
        $this->db->query($sql);
    }
    //==========================  APERTURA PROGRAMATICA  =================================================
    //programacion
    function lista_aper(){
        //filtrar solo los padres
        $this->db->WHERE('a.uni_id = u.uni_id');
        $this->db->WHERE('a.aper_proyecto','0000');
        $this->db->WHERE('a.aper_actividad','000');
        $this->db->WHERE('a.aper_estado',1);
        $this->db->FROM('aperturaprogramatica a, unidadorganizacional u');
        $this->db->ORDER_BY('a.aper_programa,a.aper_gestion','ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    function verificar_aper($cod,$gestion){
        $this->db->WHERE('aper_programa',$cod);
        $this->db->WHERE('aper_gestion',$gestion);
        $this->db->WHERE('aper_estado',1);
        $this->db->FROM('aperturaprogramatica');
        $query = $this->db->get();
        return $query->result_array();
    }
    function add_aper($programa,$descripcion,$gestion,$unidad_o){
        $data = array(
            'aper_gestion' => $gestion,
            'aper_entidad' => '0',
            'aper_programa' => $programa,
            'aper_proyecto' => '0000',
            'aper_actividad' => '000',
            'aper_estado' => 1,
            'uni_id' => $unidad_o,
            'aper_descripcion' => strtoupper(trim($descripcion)),
        );
        $this->db->insert('aperturaprogramatica',$data);
    }
    function dato_aper($id)
    {
        $this->db->WHERE('aper_id',$id);
        $this->db->FROM('aperturaprogramatica');
        $query = $this->db->get();
        return $query->result_array();
    }
    function mod_aper($id,$descripcion,$gestion,$unidad){
        $des = strtoupper(trim($descripcion));
        $sql = "UPDATE aperturaprogramatica SET aper_descripcion='".$des."',uni_id=".$unidad." WHERE aper_id=".$id;
        $this->db->query($sql);
    }
    //=========================== POA ============================
    function lista_poa(){
        $gestion = $this->session->userData('gestion');
        $this->db->WHERE('poa_gestion',$gestion);
        $this->db->FROM('vista_poa');
        $this->db->ORDER_BY("aper_programa,aper_proyecto,aper_actividad", "ASC");
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_aper_noasignados(){
        $this->db->WHERE('aper_estado',1);
        $this->db->WHERE('aper_asignado',0);
        $this->db->WHERE('aper_proyecto','0000');
        $this->db->WHERE('aper_actividad','000');
        $this->db->WHERE('aper_gestion',$this->session->userData('gestion'));
        $this->db->FROM('aperturaprogramatica');
        $this->db->ORDER_BY ('aper_programa','ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_unidadorganizacional(){
        $this->db->WHERE('uni_estado',1);
        $this->db->FROM('unidadorganizacional');
        $query = $this->db->get();
        return $query->result_array();
    }
    //verificar si existe el codigo de poa POR GESTION
    function dato_poa($poa_cod)
    {
        $this->db->WHERE('poa_codigo',$poa_cod);
        $this->db->WHERE('poa_gestion',$this->session->userData('gestion'));
        $this->db->FROM('poa');
        $query = $this->db->get();
        return $query->result_array();
    }
    function add_poa($aper_programatica,$poa_fecha){
        //-----------crear codigo ------------
        $cont =  $this->get_cont();
        $cont = $cont[0]['conf_poa'];
        $cont++;
        $codigo = 'POA/SIIP/'.$this->session->userData('gestion').'/00'.$cont;
        //-------------------
        //id unidad
        $id = $this->dato_aper_unidad($aper_programatica);
        $uni_id = $id[0]['uni_id'];
        //------------
        $poa_fecha = str_replace("/", "-", $poa_fecha);
        $vec = explode("-", $poa_fecha);
        $fecha = $vec[2]."-".$vec[1]."-".$vec[0];
        $data = array(
            'poa_codigo' => $codigo,
            'aper_id' => $aper_programatica,
            'poa_fecha_creacion' => $fecha,
            'uni_id' => $uni_id,
            'poa_gestion' => $this->session->userData('gestion'),
            'fun_id' => $this->session->userData('id_usuario')
        );
        $this->db->insert('poa',$data);
        //-----------actualizar mi conf poa mi contador
        $this->update_confpoa($cont);
    }
    function asignar_aper($id){
        $sql = "UPDATE aperturaprogramatica SET aper_asignado= 1 WHERE aper_id=".$id;
        $this->db->query($sql);
    }
    function get_cont(){
        $this->db->WHERE('conf_estado',1);
        $this->db->FROM('configuracion');
        $query = $this->db->get();
        return $query->result_array();
    }
    function update_confpoa($cont){
        $sql = "UPDATE configuracion SET conf_poa=".$cont." WHERE ide=".$this->session->userData('gestion');
        $this->db->query($sql);
    }
    function mod_poa($id,$fecha){
        $poa_fecha = str_replace("/", "-", $fecha);
        $sql = "UPDATE poa SET poa_fecha_creacion ='".$poa_fecha."'  WHERE poa_id=".$id;
        $this->db->query($sql);
    }
    function dato_poa_id($id)
    {
        $this->db->WHERE('poa_id',$id);
        $this->db->FROM('vista_poa');
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_list_objetivos_estrategicos($poa){
        //$sql = "select p.obje_id AS asignar,o.* FROM  vista_objest o LEFT JOIN poaobjetivosestrategicos p ON o.obje_id = p.obje_id AND p.poa_id = ".$poa." ORDER BY o.obje_id DESC";
        $fecha = $this->session->userData('gestion');
        $sql = "select tmp.*
               from (select p.obje_id AS asignar,o.* FROM  vista_objest o LEFT JOIN poaobjetivosestrategicos p ON o.obje_id = p.obje_id AND p.poa_id = ".$poa.")tmp
               where ".$fecha." between tmp.obje_gestion_curso and (tmp.obje_gestion_curso+5) ORDER BY tmp.obje_id DESC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    function asignar_obj($poa_id,$obj_id){
        $data = array(
            'poa_id' => $poa_id,
            'obje_id' => $obj_id,
        );
        $this->db->insert('poaobjetivosestrategicos',$data);
    }
    function quitar_obj($poa_id,$obj_id){
        $this->db->where('poa_id', $poa_id);
        $this->db->where('obje_id', $obj_id);
        $this->db->delete('poaobjetivosestrategicos');
    }
    function lista_uni(){
        $this->db->WHERE('uni_estado',1);
        $this->db->FROM('unidadorganizacional');
        $query = $this->db->get();
        return $query->result_array();
    }
    function dato_uni($id){
        $this->db->WHERE('uni_estado',1);
        $this->db->WHERE('uni_id',$id);
        $this->db->FROM('unidadorganizacional');
        $query = $this->db->get();
        return $query->result_array();
    }
    function dato_aper_unidad($aper_id){
        $this->db->WHERE('a.aper_id',$aper_id);
        $this->db->WHERE('a.uni_id = u.uni_id');
        $this->db->FROM('aperturaprogramatica a, unidadorganizacional u');
        $query = $this->db->get();
        return $query->result_array();
    }
    /// aperturas hijos
    function lista_aper_hijos(){
        $sql = "SELECT distinct a.*,u.uni_unidad FROM aperturaprogramatica a,unidadorganizacional u
          WHERE a.uni_id = u.uni_id AND a.aper_estado = 1 AND (a.aper_proyecto != '0000' OR a.aper_actividad != '000')
          ORDER BY a.aper_programa,a.aper_proyecto,a.aper_actividad ASC";
        $query = $this->db->query($sql);
        return $query->result_array();

    }
    //verificar si esixte em mismo codigo de proyecto apertura programatacica
    function ver_cod_proy($aper_programa,$aper_proyecto,$aper_gestion){
       // $this->db->WHERE("aper_programa ='".$aper_programa."' and aper_proyecto = '".$aper_proyecto."'");
        $this->db->FROM('aperturaprogramatica');
        $this->db->WHERE("aper_programa",$aper_programa);
        $this->db->WHERE('aper_proyecto',$aper_proyecto);
        $this->db->WHERE('aper_gestion',$aper_gestion);
        $query = $this->db->get();
        return $query->result_array();
    }
    //verificar si existe el codigo de actividad de apertura programatica
    function ver_cod_act($aper_programa,$aper_proyecto,$aper_actividad,$aper_gestion){
        $this->db->FROM('aperturaprogramatica');
        $this->db->WHERE("aper_programa",$aper_programa);
        //$this->db->WHERE('aper_proyecto',$aper_proyecto);
        $this->db->WHERE('aper_actividad',$aper_actividad);
        $this->db->WHERE('aper_gestion',$aper_gestion);
        $query = $this->db->get();
        return $query->result_array();
    }

    function verificar_cod_proy_act($aper_programa,$aper_proyecto,$aper_actividad){
        $this->db->WHERE('aper_programa',$aper_programa);
        $this->db->WHERE("(aper_proyecto = '".$aper_proyecto."' OR aper_actividad = '".$aper_actividad."')");
        $this->db->FROM('aperturaprogramatica');
        $query = $this->db->get();
        return $query->result_array();
    }
    //lista de aperturas padres asignados
    function lista_aper_todo(){
        $this->db->FROM('aperturaprogramatica a, unidadorganizacional u');
        $this->db->WHERE('a.uni_id = u.uni_id');
        $this->db->WHERE('a.aper_estado',1);
        $this->db->ORDER_BY(' a.aper_gestion, a.aper_programa,a.aper_proyecto,a.aper_actividad ',' ASC ');
        $query = $this->db->get();
        return $query->result_array();
    }
    function dato_tipo_proy($id){
        $this->db->SELECT('p.*');
        $this->db->FROM('aperturaproyectos a, _proyectos p');
        $this->db->WHERE('a.aper_id',$id);
        $this->db->WHERE('a.proy_id = p.proy_id');
        $query = $this->db->get();
        return $query->result_array();
    }
    //modificar apertura prog. proyectos
    function mod_aper_proy($aper_id,$descripcion,$unidad,$aper_proyecto){
        $data = array(
            'aper_descripcion' => strtoupper(trim($descripcion)),
            'uni_id' => $unidad,
            'aper_proyecto' => $aper_proyecto
        );
        $this->db->where('aper_id', $aper_id);
        $this->db->update('aperturaprogramatica', $data);
    }
    //modificar apertura prog. actividad
    function mod_aper_act($aper_id,$descripcion,$unidad,$aper_actividad){
        $data = array(
            'aper_descripcion' => strtoupper(trim($descripcion)),
            'uni_id' => $unidad,
            'aper_actividad' => $aper_actividad
        );
        $this->db->where('aper_id', $aper_id);
        $this->db->update('aperturaprogramatica', $data);
    }

}