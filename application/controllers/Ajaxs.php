<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajaxs extends CI_Controller {

	/**
	 * Index Page for this controller.
	 */
	 
        public function __construct(){
            parent::__construct();
        
            $this->load->helper('url');
            $this->load->library('ajax');
            $this->load->library('parser');
            $this->load->library('session');
            
            $this->data['head']  =$this->ajax->ajax_head();
           
        }
	
	public function index(){

            $this->data['title']        = 'ajaxtest';
            $this->data['date-time']    = $this->date_time();
            $this->data['throw-dice']    = $this->throw_dice();
            $this->ajax->answer();
            
            $this->parser->parse('ajaxtest', $this->data);
            
             
	}
	       
        public function date_time(){
           
            $ajax_panel_id = "date_time";
            if ($this->ajax->check_load_panel($ajax_panel_id)){
                if ($this->ajax->check_hide_source($ajax_panel_id) == false){
                    $data['panel-content']  = date('d-m-Y H:i:s',time());
                    $data['link-hide']= $this->ajax->ajax_hide($ajax_panel_id,false);
                   
                 
                }else{
                    $data['panel-content'] = '';
                    $data['link-hide']= $this->ajax->ajax_hide($ajax_panel_id,true);
                    $data['head']= '';
                }
             
                $this->ajax->ajax_timer_event($ajax_panel_id, 1000, false);
                $data['head']=$this->ajax->ajax_head();
                $data['link-refresh']='';
                $this->ajax->ajax_set_panel($ajax_panel_id, $this->parser->parse('ajaxtest_panel_body', $data, true));
                $data['panel-body'] =  $this->ajax->ajax_get_panel($ajax_panel_id);   
                $panel = $this->parser->parse('ajaxtest_panel', $data, true);
                
                return($panel);
            }    
        }
        
        public function throw_dice(){
           
            $ajax_panel_id = 'throw_dice';
            if ($this->ajax->check_load_panel($ajax_panel_id)){
                
                if ($this->ajax->check_hide_source($ajax_panel_id) == false){
                    $data['panel-content']  = rand(1, 6);
                    $data['link-hide']= $this->ajax->ajax_hide($ajax_panel_id,false);
                }else{
                    $data['panel-content'] = '<center>' .$this->ajax->ajax_hide($ajax_panel_id,true) . '</center>';
                    $data['link-hide']= $this->ajax->ajax_hide($ajax_panel_id,true);
                }
                $data['head']='';
                $data['link-refresh']=$this->ajax->ajax_refresh_link($ajax_panel_id, true);
                $this->ajax->ajax_set_panel($ajax_panel_id, $this->parser->parse('ajaxtest_panel_body', $data, true));
                $data['panel-body'] =  $this->ajax->ajax_get_panel($ajax_panel_id);   
                $panel = $this->parser->parse('ajaxtest_panel', $data, true);
                
                return($panel);
            }    
        }
        
}