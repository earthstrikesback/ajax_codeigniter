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
            $this->icon['hide']         = "hide";
            $this->icon['show']         = "show";
            $this->icon['refresh']      = "refresh";
            $this->_ajaxload            = "'loading'";
        
            if($BOOTSTRAP){
                $this->icon['hide']     = "<span class='glyphicon glyphicon-resize-small' aria-hidden='true'></span>";
                $this->icon['show']     = "<span class='glyphicon glyphicon-resize-full' aria-hidden='true'></span>";
                $this->icon['loading']  = "<span class='glyphicon glyphicon-refresh glyphicon-refresh-animate' aria-hidden='true'></span>";
                $this->icon['refresh']  = "<span class='glyphicon glyphicon-refresh' aria-hidden='true'></span>";
      
                
                $this->_ajaxload = "'<div class=\"panel panel-default\"><div class=\"panel-heading\">loading...</div>"
                        . "<div class=\"panel-body\"><center>"
                        . "<span class=\"glyphicon glyphicon-refresh glyphicon-refresh-animate\" aria-hidden=\"true\"></span>"
                        . "</center></div></div>'";
            }    
                
                $this->_head='
	<script>
		function ajaxPlace(ss, img){
                    if(img)
                        $(\'#\'+ss).html('.$this->_ajaxload.');
			
                        $.post(\''.current_url().'\', { ajax_request: ss
                            }, function(data) {
                            $(\'#\'+ss).html(data);
                             });
		}
                
                function ajaxHide(ss){
                    $.post(\''.current_url().'\', { ajax_request: ss, ajax_hide: true }, function(data) {
                       $(\'#\'+ss).html(data);
                        });
		}
                function ajaxShow(ss){
                    $.post(\''.current_url().'\', { ajax_request: ss, ajax_hide: false }, function(data) {
                       $(\'#\'+ss).html(data);
                        });
		}


	</script>
';
	}
	

        
	public function ajax_timer_event($id, $time, $s=false){
            if($s){ $s='true';}
            else{ $s='false';}
            $_h=<<<HTML
	<script>
	function Timer{$id}(){
		setTimeout("ajaxPlace('{$id}', {$s});Timer{$id}()", {$time});
	}
	Timer{$id}();
	</script>
HTML;
		$this->_head.=$_h;
	}
	
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
        
        public function check_hide_source($id){
            $match=false;
            if(($this->check_refresh_source($id)) && (isset($_POST['ajax_hide'])) ){
                if(($_POST['ajax_hide']=='true')){ 
                    $match=true;
                    $_SESSION[$id]=$id;
                }
                if(($_POST['ajax_hide']=='false')){ 
                    unset($_SESSION[$id]);
                }
            }
            if( isset($_SESSION[$id]) ){
                  if($id == $_SESSION[$id]){ 
                      $match=true;
                  }
            }
            return($match);
        }
 
	
	public function ajax_refresh_link($id, $load=false){
      
            if($load)
			return '<a href="javascript:ajaxPlace(\''.$id.'\', true)">'.$this->icon['refresh'] .'</a>';
		else
			return '<a href="javascript:ajaxPlace(\''.$id.'\', false)">'.$this->icon['refresh'] .'</a>';
	}
        
        public function ajax_hide($id,$hide){
            if(!$hide){
                return '<a href="javascript:ajaxHide(\''.$id.'\')">'.$this->icon['hide'].'</a>';
            }else{
                return '<a href="javascript:ajaxShow(\''.$id.'\')">'.$this->icon['show'].'</a>';
            }    
           
	}
        
       
}
?>