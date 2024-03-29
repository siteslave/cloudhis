<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @package		CloudHIS
 * @author		Satit Rianpit
 * @copyright	Copyright (c) 2008 - 2012, EllisLab, Inc. (http://ellislab.com/)
 * @license		http://opensource.org/licenses/AFL-3.0 Academic Free License (AFL 3.0)
 * @website		http://codeigniter.com
 * 
 **/
class Basic_model extends CI_Model {
	// construction method
	public function __construct(){
		parent::__construct();
	}
	// return @clinics array()
	public function _get_clinics_dropdown(){
		$query = $this->db->get('clinics');

		foreach ($query->result_array() as $row) {
			$clinics[$row['id']] = $row['name'];
		}
		return $clinics;
	}
	
	// return @smokings array()
	public function _get_smokings(){
		$result = $this->db->get('ncd_screening_smokes')->result();

		return $result;
	}

  public function _get_drinkings(){
    $result = $this->db->get('ncd_screening_drinks')->result();

    return $result;
  }

  public function getfptype_list(){
    $result = $this->db->get('fp_types')->result();

    return $result;
  }
	// return @allergics array()
	public function _get_allergics(){
		$result = $this->db->get('drug_allergics')->result();

		return $result;
	}
	// @return	array()
	public function _get_pttypes_dropdown(){
		$query = $this->db->get('pttypes');

		foreach ($query->result_array() as $row) {
			$pttypes[$row['id']] = $row['name'];
		}
		return $pttypes;
	}
	// @return	array()
	public function _get_locations_dropdown(){
		$query = $this->db->get('locations');

		foreach ($query->result_array() as $row) {
			$locations[$row['id']] = $row['name'];
		}
		return $locations;
	}
	// @return	array()
	public function _get_places_dropdown(){
		$query = $this->db->get('service_places');

		foreach ($query->result_array() as $row) {
			$places[$row['id']] = $row['name'];
		}
		return $places;
	}
	// @return	array()
	public function _get_appoint_dropdown(){
		$query = $this->db->get('appoints');

		foreach ($query->result_array() as $row) {
			$appoints[$row['id']] = $row['name'];
		}
		return $appoints;
	}
	// @return string
	public function _search_icd($query)
	{
		$result = $this->db->select(array('code', 'name'))
												->like('name', $query)
												->or_like('code', $query)
												->where('valid', 'T')
												->limit(20)
												->get('icd10')->result();
		return $result;	
	}
	/**
	* Search Proced
	*
	**/
	public function _search_proced($query)
	{
		$result = $this->db->select(array('code', 'name'))
												->like('name', $query)
												->or_like('code', $query)
												->where('valid', 'T')
												->limit(20)
												->get('icd9')->result();
		return $result;	
	}
	/**
	* Get Diag type
	*
	**/
	public function _get_diag_types(){
		$query = $this->db->get('diag_types')->result();
    return $query;
	}
	/*
	* Search Hospital
	*
	**/
	public function _search_hospitals($query){
		$result = $this->db->like('code', $query)
			 								->or_like('name', $query)
			 								->limit(20)
											->get('hospitals')
											->result();
		return $result;
		
	}
	/*
	* Search Drug
	*
	**/
	public function _search_drug($query){
		$result = $this->db->select('id, name, strength, units, unitprice')
                      ->like('name', $query)
                      ->where('active', 'Y')
											->limit(20)
											->get('drugitems')
											->result();
		return $result;
		
	}
	/*
	* Search Drug FP
	*
	**/
	public function _search_drug_fp($query){
		$result = $this->db->like('name', $query)
											->where('fp_drug', 'Y')
											->limit(20)
											->get('drugitems')
											->result();
		return $result;
		
	}
	/*
	* Search Usage
	*
	**/
	public function _search_usage($query){
		$result = $this->db->like('name1', $query)
                      ->where('usage_status', 'Y')
											->limit(20)
											->get('drugusages')
											->result();
		return $result;
		
	}
	/*
	* Search Usage
	*
	**/
	public function _search_income($query){
		$result = $this->db->like('name', $query)
											->limit(20)
											->get('incomes')
											->result();
		return $result;
	}
	/*
	* Get Changwat
	*
	**/
	public function _getchw($query){
		$result = $this->db->like('name', $query)
											->where('amp', '00')
											->where('tmb', '00')
											->where('moo', '00')
											->limit(20)
											->get('catms')
											->result();
		return $result;
	}
	/*
	* Get Ampur
	*
	**/
	public function _getamp($query, $chw_code){
		$result = $this->db->like('name', $query)
											->where('chw', $chw_code)
											->where('amp !=' ,'00')
											->where('tmb', '00')
											->where('moo', '00')
											->limit(20)
											->get('catms')
											->result();
		return $result;
	}
	/*
	* Get Tambon
	*
	**/
	public function _gettmb($query, $chw_code, $amp_code){
		$result = $this->db->like('name', $query)
											->where('chw', $chw_code)
											->where('amp' , $amp_code)
											->where('tmb !=', '00')
											->where('moo', '00')
											->limit(20)
											->get('catms')
											->result();
		return $result;
	}
	/*
	* Get Tambon
	*
	**/
	public function _getmooban($chw_code, $amp_code, $tmb_code){
		$result = $this->db->where('chw', $chw_code)
											->where('amp' , $amp_code)
											->where('tmb', $tmb_code)
											->where('moo !=', '00')
											->where_not_in('moo', array('00', '77'))
											->order_by('moo')
											//->limit(20)
											->get('catms')
											->result();
		return $result;
	}
	/*
	* Search Surveil Complication
	*
	**/
	public function _search_surveil_comp($query){
		$result = $this->db->select(array('id', 'name'))
		 									->like('name', $query)
											->limit(20)
											->get('surveil_complicates')
											->result();
		return $result;
		
	}
	/*
	* Search Surveil Organism
	*
	**/
	public function _search_surveil_organ($query){
		$result = $this->db->select(array('id', 'name'))
		 									->like('name', $query)
											->limit(20)
											->get('surveil_organisms')
											->result();
		return $result;
		
	}
	/*
	* Search Surveil Code
	*
	**/
	public function _search_surveil($query){
		$result = $this->db->select(array('id', 'tname'))
		 									->like('tname', $query)
											->limit(20)
											->get('surveils')
											->result();
		return $result;
		
	}
	/*
	* Get surveil patient status
	*
	**/
	public function _get_surveil_patient_status_dropdown(){
		$query = $this->db->get('surveil_patient_status');

		foreach ($query->result_array() as $row) {
			$patient_status[$row['id']] = $row['name'];
		}
		return $patient_status;
		
	}
	/*
	* Get FP type list
	*
	**/
	public function _get_fptype_dropdown(){
		$query = $this->db->get('fp_types');

		foreach ($query->result_array() as $row) {
			$fptypes[$row['id']] = $row['name'];
		}
		return $fptypes;
	}
	/*
	* Get FP type list
	*
	**/
	public function _get_fpplace_dropdown(){
		$query = $this->db->get('fp_places');

		foreach ($query->result_array() as $row) {
			$fpplaces[$row['id']] = $row['name'];
		}
		return $fpplaces;
	}
	/*
	* Get vaccine place
	*
	**/
	public function _get_vccplace_dropdown(){
		$query = $this->db->get('epi_places');

		foreach ($query->result_array() as $row) {
			$vccplaces[$row['id']] = $row['name'];
		}
		return $vccplaces;
	}
	/*
	* Get vaccine type
	*
	**/
	public function _get_vcctype_dropdown(){
		$query = $this->db->get('epi_vaccines');

		foreach ($query->result_array() as $row) {
			$vcctypes[$row['id']] = $row['eng_name'];
		}
		return $vcctypes;
	}
	/*
	* Get ncd screening smoke type
	*
	**/
	public function _get_smoke_dropdown(){
		$query = $this->db->get('ncd_screening_smokes');

		foreach ($query->result_array() as $row) {
			$result[$row['id']] = $row['name'];
		}
		return $result;
	}
	/*
	* Get ncd screening alcohol type
	*
	**/
	public function _get_alcohol_dropdown(){
		$query = $this->db->get('ncd_screening_drinks');

		foreach ($query->result_array() as $row) {
			$result[$row['id']] = $row['name'];
		}
		return $result;
	}
	/*
	* Get ncd screening alcohol type
	*
	**/
	public function _get_blood_screen_dropdown(){
		$query = $this->db->get('ncd_screening_bloods');

		foreach ($query->result_array() as $row) {
			$result[$row['id']] = $row['name'];
		}
		return $result;
	}
	/*
	* Get ncd screening alcohol type
	*
	**/
	public function _getlab_orders_list(){
		$result = $this->db->order_by('name', 'desc')->get('lab_groups')->result();
		return $result;
	}
	/**
	 * Get Refer cause
	 * @return array() Refer cause list
	 **/
	public function _get_refer_cause_dropdown() {
		$query = $this->db->get('refer_causes');

		foreach ($query->result_array() as $row) {
			$result[$row['id']] = $row['name'];
		}
		return $result;
	}
	/**
	 * Get title
	 * @return array() Title list
	 **/
	public function _get_title_dropdown() {
		$query = $this->db->where( 'active', 'Y' )->get( 'titles' );

		foreach ($query->result_array() as $row) {
			$result[$row['id']] = $row['name'];
		}
		return $result;
	}
	/**
	 * Get blood group
	 * @return array() Blood group list
	 **/
	public function _get_blood_group_dropdown() {
		$query = $this->db->get( 'blood_groups' );

		foreach ($query->result_array() as $row) {
			$result[$row['id']] = $row['name'];
		}
		return $result;
	}
	/**
	 * Get married status
	 * @return array()
	 **/
	public function _get_married_dropdown() {
		$query = $this->db->get( 'married_status' );

		foreach ($query->result_array() as $row) {
			$result[$row['id']] = $row['name'];
		}
		return $result;
	}
	/**
	 * Get occupation
	 * @return array()
	 **/
	public function _get_occupation_dropdown() {
		$query = $this->db->get( 'occupations' );

		foreach ($query->result_array() as $row) {
			$result[$row['id']] = $row['name'];
		}
		return $result;
	}
	/**
	 * Get race
	 * @return array()
	 **/
	public function _get_race_dropdown() {
		$query = $this->db->get( 'races' );

		foreach ($query->result_array() as $row) {
			$result[$row['id']] = $row['name'];
		}
		return $result;
	}
	/**
	 * Get nation
	 * @return array()
	 **/
	public function _get_nation_dropdown() {
		$query = $this->db->get( 'nations' );

		foreach ($query->result_array() as $row) {
			$result[$row['id']] = $row['name'];
		}
		return $result;
	}
	/**
	 * Get nation
	 * @return array()
	 **/
	public function _get_education_dropdown() {
		$query = $this->db->get( 'educations' );

		foreach ($query->result_array() as $row) {
			$result[$row['id']] = $row['name'];
		}
		return $result;
	}
	/**
	 * Get nation
	 * @return array()
	 **/
	public function _get_religion_dropdown() {
		$query = $this->db->get( 'religions' );

		foreach ($query->result_array() as $row) {
			$result[$row['id']] = $row['name'];
		}
		return $result;
	}
	/**
	 * Get changwat
	 * @return array()
	 **/
	public function _get_chw_dropdown() {
		$result = $this->db->where( 'amp', '00' )
                      ->where( 'tmb', '00' )
                      ->where( 'moo', '00' )
                      ->get( 'catms' )
                      ->result();

		return $result;
	}
	/**
	 * Get amp
	 * @return array()
	 **/
	public function _get_amp_dropdown( $chw ) {
		$result = $this->db->where( 'chw', $chw )
                      ->where( 'amp <>', '00' )
                      ->where( 'tmb', '00' )
                      ->where( 'moo', '00' )
                      ->order_by( 'name' )
                      ->get( 'catms' )
                      ->result();

    return $result;
	}
	/**
	 * Get tambon
	 * @return array()
	 **/
	public function _get_tmb_dropdown( $chw, $amp ) {
		$result = $this->db->where( 'chw', $chw )
                      ->where( 'amp', $amp )
                      ->where( 'tmb <>', '00' )
                      ->where( 'moo', '00' )
                      ->order_by( 'name' )
                      ->get( 'catms' )
                      ->result();

    return $result;
	}
	/**
	 * Get mooban
	 * @return array()
	 **/
	public function _get_moo_dropdown( $chw, $amp, $tmb ) {
		$result = $this->db->where( 'chw', $chw )
                      ->where( 'amp', $amp )
                      ->where( 'tmb', $tmb )
                      ->where( 'moo <>', '00' )
                      ->order_by( 'moo' )
                      ->get( 'catms' )
                      ->result();

    return $result;
	}
	/**
	 * Get discharge status
	 * @return array()
	 **/
	public function _get_discharge_status_dropdown() {
		$query = $this->db->get( 'discharge_status' );

		foreach ($query->result_array() as $row) {
			$result[$row['id']] = $row['name'];
		}
		return $result;
	}
	/**
	 * Get type area
	 * @return array()
	 **/
	public function _get_type_area_dropdown() {
		$query = $this->db->get( 'type_areas' );

		foreach ($query->result_array() as $row) {
			$result[$row['id']] = $row['name'];
		}
		return $result;
	}
	/**
	 * Get labor type
	 * @return array()
	 **/
	public function _get_labor_type_dropdown() {
		$query = $this->db->get( 'labor_types' );

		foreach ($query->result_array() as $row) {
			$result[$row['id']] = $row['name'];
		}
		return $result;
	}

  public function _get_doctor_list_visit( $pcucode )
  {
    $result = $this->db->where('pcucode', $pcucode)->get('doctors')->result();
    return $result;
  }
  public function getepi()
  {
    $result = $this->db->order_by('eng_name')->get('epi_vaccines')->result();
    return $result;
  }
}
/* End of file basic_model.php */
/* Location: ./application/models/basic_model.php */
