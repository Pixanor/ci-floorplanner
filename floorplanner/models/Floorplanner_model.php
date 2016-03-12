<?php

class Floorplanner_model extends CI_Model {
    function __construct()
    {
        parent::__construct();
          $this->load->database();
    }

    public function getWallDesigns(){
      $this->db->select('*')->from('rgbdb.dulux')->limit(1,100);
      $query=$this->db->get();
      $data=$query->result_array();
      return $data;
    }
    public function getImageByColor($color=""){

      
        $sql='select * from rgbdb.asianpaints where LOWER(`color`) like "%'.$this->db->escape_like_str($color).'%";';
        $query=$this->db->query($sql);
        $data=$query->result_array();
        return $data;
    }
    public function getColors($color){
      $this->db->distinct();
      $this->db->select('color');
      $query=$this->db->get('rgbdb.'.$color);
      $data=$query->result_array();
      return $data;
    }
  }
