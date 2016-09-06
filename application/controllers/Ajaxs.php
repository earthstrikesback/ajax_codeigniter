<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajaxs extends CI_Controller {

	/**
        * Constructor
        */  
	public function __construct(){
            parent::__construct();
        
            $this->load->helper('url');
            $this->load->library('ajax');
            $this->load->library('parser');
            $this->load->library('session');
            
           
           
        }
	
	public function index(){
  
            $this->data['title']        = 'ajaxtest';
            
            $this->data['throw-dice']   = $this->throw_dice();
            $this->data['sleep']        = $this->goto_sleep();
            $this->data['date-time']    = $this->date_time();
           
            $this->ajax->answer();
            
            $this->data['head']  = $this->ajax->ajax_head();
            $this->parser->parse('ajaxtest', $this->data);
        }
        
        /**
        * 
        */  
        public function throw_dice(){
           
            $ajax_panel_id = 'throw_dice';
            // checks the ajax_request source 
            if ($this->ajax->check_load_panel($ajax_panel_id)){
                if ($this->ajax->check_hide_source($ajax_panel_id) == false){
                    $data['panel-content']  = rand(1, 6);
                    $data['link-hide']= $this->ajax->ajax_hide($ajax_panel_id,false);
                }else{
                    $data['panel-content'] = '<center>' .$this->ajax->ajax_hide($ajax_panel_id,true) . '</center>';
                    $data['link-hide']= $this->ajax->ajax_hide($ajax_panel_id,true);
                }
                $data['link-refresh']=$this->ajax->ajax_refresh_link($ajax_panel_id, true);
                $panel = $this->parser->parse('ajaxtest_panel', $data, true);
                $this->ajax->ajax_set_panel($ajax_panel_id, $panel);

                return($this->ajax->ajax_get_panel($ajax_panel_id));
            }

        }
        
        /**
        *  ajax_run_onload
        */  
        
         public function goto_sleep(){
           
            $ajax_panel_id = 'getsleep';
            if ($this->ajax->check_load_panel($ajax_panel_id)){
                $message = '';
                if ($this->ajax->check_show_panel_data($ajax_panel_id)){
                        sleep(5);
                        $message = 'awake!!';
                }
                
               $this->ajax->ajax_run_onload($ajax_panel_id,true);
               
               $this->ajax->ajax_set_panel($ajax_panel_id, $message);
               return($this->ajax->ajax_get_panel($ajax_panel_id));
            }
        }
        
	/**
        * ajax_timer_event
        */      
        public function date_time(){
           
            $ajax_panel_id = "date_time";
            if ($this->ajax->check_load_panel($ajax_panel_id)){
                $this->ajax->ajax_timer_event($ajax_panel_id, 1000, false);
                
                if ($this->ajax->check_hide_source($ajax_panel_id) == false){
                    $data['panel-content']  = date('d-m-Y H:i:s',time());
                    $data['link-hide']= $this->ajax->ajax_hide($ajax_panel_id,false);
                }else{
                    $data['panel-content'] = '';
                    $data['link-hide']= $this->ajax->ajax_hide($ajax_panel_id,true);
                }
                $data['link-refresh']='';
                $panel = $this->parser->parse('ajaxtest_panel', $data, true);
                $this->ajax->ajax_set_panel($ajax_panel_id, $panel);
                
                return($this->ajax->ajax_get_panel($ajax_panel_id));
            }    
        }
        
        
        
        
        
        
  
        
}