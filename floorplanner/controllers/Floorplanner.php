<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Floorplanner extends MX_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->config('auth/ion_auth');
		$this->load->library('auth/ion_auth');
		$this->load->helper('form');
		if(!$this->ion_auth->in_group('floorplanner') && !$this->ion_auth->is_admin())
			redirect('/auth/login/','refresh');
	}

  public function index()
	{
		$this->load->model('Floorplanner_model');
		$data['title']="3D floorplanner Home";
    $data['page']='floorplanner';
    $data['floorScripts']=array('akp_floorplanner.min.js','jquery.min.js','bootstrap.min.js','items.min.js','init_planner.min.js');
	$data['adjustWall']=$this->Floorplanner_model->getWallDesigns();
	$data['colors']=$this->Floorplanner_model->getColors("asianpaints");
    $this->_render_page('floorplanner/index',$data);
	}

	public function getImageByColor(){
		$color=$this->input->post('color');
		$this->load->model('Floorplanner_model');
		$imgList=$this->Floorplanner_model->getImageByColor($color);
		$this->output
		->set_content_type('application/json')
		->set_status_header(200)
		->set_output(json_encode(array(
			'data' => $imgList,
			'type' => 'success',
			'color' => $color
		)));
		
	}


  //Donot edit code below this comment
  function _render_page($view, $data=null, $returnhtml=false)//I think this makes more sense
	{

		$this->viewdata = (empty($data)) ? $this->data: $data;

		$view_html = array(
			$this->load->view("menu/header", $data, $returnhtml),
			$this->load->view($view, $this->viewdata, $returnhtml),
			// $this->load->view("menu/footer", $data, $returnhtml)
			);

		if ($returnhtml) return $view_html;//This will return html on 3rd argument being true
	}
}
