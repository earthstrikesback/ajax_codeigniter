<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter Serverinfo
 *
 * Gets php serverinfo
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Ajax library
 * @author		phh
 * @author		EARTHSTRIKESBACK (Filip Van Dyck)
 * @link		https://github.com/earthstrikesback/ajax_codeigniter
 * @docs		https://github.com/earthstrikesback/ajax_codeigniter/blob/master/README.md
 */
 

class Ajax
{
	private $_body;
	private $_ajaxload;
	private $_head;

	public function __construct(){
            $BOOTSTRAP = false;
            $this->DEBUG                = FALSE;
            
            $this->icon['hide']         = "hide";
            $this->icon['show']         = "show";
            $this->icon['refresh']      = "refresh";
            $this->icon['start']        = "start";
            $this->icon['stop']         = "stop";
            $this->icon['clear']        = "clear";
            $this->_ajaxload            = "'loading'";
        
            if($BOOTSTRAP){
                $this->icon['hide']     = "<span class='glyphicon glyphicon-resize-small' aria-hidden='true'></span>";
                $this->icon['show']     = "<span class='glyphicon glyphicon-resize-full' aria-hidden='true'></span>";
                $this->icon['loading']  = "<span class='glyphicon glyphicon-refresh glyphicon-refresh-animate' aria-hidden='true'></span>";
                $this->icon['refresh']  = "<span class='glyphicon glyphicon-refresh' aria-hidden='true'></span>";
                $this->icon['start']    = "<span class='glyphicon glyphicon-play' aria-hidden='true'></span>";
                $this->icon['stop']     = "<span class='glyphicon glyphicon-pause' aria-hidden='true'></span>";
                $this->icon['clear']    = "<span class='glyphicon glyphicon-erase' aria-hidden='true'></span>";
                
      
                
                $this->_ajaxload = "'<div class=\"panel panel-default\"><div class=\"panel-heading\">loading...</div>"
                        . "<div class=\"panel-body\"><center>"
                        . "<span class=\"glyphicon glyphicon-refresh glyphicon-refresh-animate\" aria-hidden=\"true\"></span>"
                        . "</center></div></div>'";
            }    
                
                $this->_head='
	<script>
                                              
                function ajaxSendRequest(source,requestname,showimage){
                    if(showimage)
                        $(\'#\'+source).html('.$this->_ajaxload.');

                    $.post(\''.current_url().'\', { ajax_request: source,  request_name : requestname }, function(data) {
                       $(\'#\'+source).html(data);
                        });
		}
                
                function ajaxPlace(source, showimage){
                    return(ajaxSendRequest(source,false,showimage));
		}
                
	</script>
';
	}
	
         public function ajax_run_onload($id, $s=false){
            if($s){ $s='true';}
            else{ $s='false';}
             $_h=<<<HTML
        <script>
        function load(){ ajaxPlace('{$id}',{$s});}
                window[ addEventListener ? 'addEventListener' : 'attachEvent' ]( addEventListener ? 'load' : 'onload', load )
        </script>
HTML;
          $this->_head.=$_h;   

                     
        }
        
         /**********************************************************************
        *  ajax_timer_event
        */  
        
	public function ajax_timer_event($id, $time, $s=false){
            if($s){ $s='true';}
            else{ $s='false';}
            $_h=<<<HTML
        <script>
	function Timer{$id}(){
		timeout = setTimeout("ajaxPlace('{$id}', {$s});Timer{$id}()", {$time});
                if({$time}==0){ clearTimeout(timeout); };
                
	}
	Timer{$id}();
	</script>
HTML;
            $this->_head.=$_h;
            return($_h);
	}
        
        public function ajax_timer_event_stop($id){
            return($this->ajax_timer_event($id, 0, false));
	}
        
         /*
        /*  ajax_timer_event
        /**********************************************************************/
        
	public function ajax_get_panel($id){
		return	'<ajax id="'.$id.'">'.$this->_body[$id].'</ajax>';
	}
	
	public function answer(){
            if(isset($_POST['ajax_request'])){
			echo $this->_body[$_POST['ajax_request']];
			die();
            }
	}
	
	public function ajax_head(){
		return $this->_head;
	}
	
	public function ajax_set_panel($id, $body){
             return $this->_body[$id]=$body;
    	}
        
        public function check_request(){
            return(isset($_POST['ajax_request']));
        }
        
        public function check_request_name($name){
            $match=false;
            if( isset($_POST['request_name']) ){
                if( ($_POST['request_name']) == $name ){
                    $match=true;
                }
            }
            return($match);
         }
 
        public function check_refresh_source($id){
            $match=false;
            if($this->check_request()){
                if($id === $_POST['ajax_request']){ $match=true;}
            }
            return($match);
        }
        
        public function check_load_panel($id){
            return($this->check_request() == false)||($this->check_refresh_source($id));
        }
        
        /**********************************************************************
        *  ajax_hide
        */  
        
        public function ajax_hide($id,$hide){
            if(!$hide){
               return $this->ajax_link_sendrequest($id,'ajax_hide',$this->icon['hide'],'false');
            }else{
                return $this->ajax_link_sendrequest($id,'ajax_show',$this->icon['show'],'true');
            }    
           
	}
        
        public function check_hide_source($id){
            $match = false;
            $this->set_hide_source($id);
            if( isset($_SESSION[$id]) ){
                $match=true;
            }
            return($match);
        }
        
        public function set_hide_source($id){
            $set=false;
            if(($this->check_refresh_source($id)) && (isset($_POST['request_name'])) ){
                if(($_POST['request_name']=='ajax_hide')){ 
                    $_SESSION[$id]=$id;
                    $set=true;
                }
                if(($_POST['request_name']=='ajax_show')){ 
                    unset($_SESSION[$id]);
                }
            }
            return($set);
        }
        
        /*
        /*  ajax_hide
        /**********************************************************************/
        
        /**********************************************************************
        *  ajax_start_stop
        */  
        
        public function ajax_start_stop($id,$start){
            if($start){
                return $this->ajax_link_sendrequest($id,'ajax_start',$this->icon['start'],'false');
            }else{
                return $this->ajax_link_sendrequest($id,'ajax_stop',$this->icon['stop'],'false');
            }         
	}
        
        public function check_request_start(){
            return($this->check_request_name('ajax_start'));
        }
        
        public function check_request_stop(){
            return($this->check_request_name('ajax_stop'));
        }
        
        public function check_start($id){
            $match=false;
            if($this->check_request_start() ){
                $_SESSION['start_stop'.$id]='start';
            }
            if($this->check_request_stop() ){
                unset($_SESSION['start_stop'.$id]);
            }
           
            if( isset($_SESSION['start_stop'.$id]) ){
                $match=true;
            }
            return($match);
        }
        /*
        /*  ajax_start_stop
        /**********************************************************************/
        
        public function check_show_panel_data($id){
            return( (!$this->check_hide_source($id)) && ($this->check_refresh_source($id)) );
        }    
	
	public function ajax_refresh_link($id, $show_image=false){
                return( $this->ajax_link_place($id,$this->icon['refresh'],$show_image) );
	}
        
        public function ajax_clear($id){
            return $this->ajax_link_sendrequest($id,'clear',$this->icon['clear'],'false');
	}
        
        public function check_clear(){
            return($this->check_request_name('clear'));
        }
        
        public function ajax_link_sendrequest($id,$request,$linktext,$show_image){
               return '<a href="javascript:ajaxSendRequest(\''.$id.'\',\''.$request.'\', '. $show_image. ')">'.$linktext.'</a>';
	}
        
        public function ajax_link_place($id,$linktext,$show_image){
               return '<a href="javascript:ajaxPlace(\''.$id.'\', '. $show_image. ')">'.$linktext.'</a>';
	}
        
        /**
        *  DEBUG FUNCTION
        */  
        
        public function print_request(){
              if( isset($_POST) && ($this->DEBUG)){
                    print_r($_POST);
              }
         }
        
       
}
?>