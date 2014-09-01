<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	private function load_slides( $path )
	{
		require( "./application/views/".$path."/index.php" );
		$html = "";
		$panel =  "<div class='preso-panel' id='slide-list'>";
		$panel .= "<ol class='preso-panel-list ui-mini ui-listview'>";
		$panel .= "<li class='ui-li-divider ui-bar-inherit ui-first-child'>Table of Contents</li>";
		$filesSorted = array();

		foreach( $slides as $slide ){
			$CI =& get_instance();
			$data = array( "id"=>preg_replace( "/\.php/", "", $slide ), "ci"=>$CI );
			$html .= "<div data-role='page' class='ui-page ui-page-theme-a ui-page-header-fixed ui-page-footer-fixed' id='".$data[ "id" ]."' data-defaults='true'>";
			$html .= "<div class='ui-content' role='main'>";
			$html .= preg_replace( "/<--Notes-->(.)*/s","",$this->load->view( $path."/".$slide.".html", $data, true ));
			$html .= "</div>";
			$html .= "</div>";
			$panel .= "<li>";
			$panel .= "<a class='ui-btn ui-btn-icon-right ui-icon-carat-r' href='#".$data[ "id" ]."'>".$this->format_title( $slide )."</a>";
			$panel .= "</li>";
		}
		$panel .= "</ol>";
		$panel .= "</div>";
		return $panel.$html;
	}
	private function load_notes( $path )
	{
		require( "./application/views/".$path."/index.php" );
		$html = "";
		$filesSorted = array();
		foreach( $slides as $slide ){
			$CI =& get_instance();
			$data = array( "id"=>preg_replace( "/\.php/", "", $slide ), "ci"=>$CI );
			$html .= "<div data-role='page' class='ui-page ui-page-theme-a ui-page-header-fixed ui-page-footer-fixed preso-notes-page' id='".$data[ "id" ]."' data-defaults='true'>";
			$html .= "<div class='ui-content' role='main'>";
			$html .= "<div class=\"ui-grid-a\"><div class=\"ui-block-a\">placeholder</div><div class=\"ui-block-b preso-notes-block ui-content\">";
			$slide = $this->load->view( $path."/".$slide, $data, true );
			if( preg_match( "/(.)*<--Notes-->/s", $slide ) ){
				$html .= preg_replace( "/(.)*<--Notes-->/s","", $slide );
			} else {
				$html .= "<span class='note-slide'>".preg_replace( "/<--Notes-->(.)*/s","", $slide )."</span>";
			}
			$html .= "</div>";
			$html .= "</div>";
			$html .= "</div>";
			$html .= "</div>";

		}
		return $html;
	}
	private function format_title( $title )
	{
		return ucwords( preg_replace( "/^([0-9])*/", "", preg_replace( "`^(.)*/`", " ", preg_replace("`/(.)*/`", "", preg_replace( "/-/", " ", preg_replace('/\.html|^([0-9])*/', '', $title ))))));
	}
	public function index( $preso = "default", $mode = "" )
	{
		$html = "";
		if( $preso === "default" ){
			$title = "Uglymongrel.com Presents: Various presentations by Alexander Schmitz";
		} else {
			$title = $preso;
		}
		$follow = false;
		$master = false;
		if( $mode === "master" ){
			$master = true;
		} else if( $mode === "presenter") {
			$master = true;
			$this->presenter( $preso );
			return;
		} else if ( $mode === "follow" ) {
			$follow = true;
		}

		$data = array( "title"=>$this->format_title( $title ), "master"=>$master, "follow"=>$follow );
		$htmlData = array( "html"=> $html, "options"=>array() );
		$this->load->library( "Minify_html", $htmlData );
		$min = new Minify_html( $htmlData );
		$this->load->helper( "directory", $data );
		$this->load->helper( "presentation_tools" );
		$html .= $this->load->view('head', $data, true);
		$html .= "<body>";
		$html .= $this->load->view('header', $data, true);
		$html .= $this->load->view('popup', $data, true);
		$html .= $this->load_slides( $preso );
		$html .= $this->load->view('footer', $data, true);
		$html .= "</body></html>";
		$htmlData = array( "html"=> $html, "options"=>array() );
		echo $min->Minify( $htmlData );

	}
	private function presenter( $preso ){
		$html = "";
		$data = array( "title"=>$this->format_title( $preso ), "master"=>true, "preso"=>$preso );

		$this->load->helper( "directory", $data );
		$this->load->helper( "presentation_tools" );
		$html .= $this->load->view('head', $data, true);
		$html .= "<body>";
		$html .= "<div class='preso-presenter-wrapper'><iframe src='/".$preso."/follow'' class='preso-presenter-frame'></iframe></div>";
		$html .= $this->load->view('popup', $data, true);
		$html .= $this->load_notes( $preso );
		$html .= "</body></html>";
		echo $html;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */