<?php
function listview( $list, $options = array() )
{
	$listviewClasses = "ui-listview";
	if( array_key_exists("theme", $options) ){
		$listviewClasses .= " ui-body-".$options[ 'theme' ];
	}
	if( ( array_key_exists("inset", $options) && $options['inset'] ) || !array_key_exists( "inset", $options ) ){
		$listviewClasses .= " ui-listview-inset";
	}
	if( ( array_key_exists("corners", $options) && $options['corners'] ) || !array_key_exists("corners", $options )){
		$listviewClasses .= " ui-corner-all";
	}
	if( count( $list ) > 8 ){
		$listviewClasses .= " ui-mini";
	}
	if( array_key_exists( "element", $options ) ){
		$element = $options['element'];
	} else {
		$element = "ul";
	}
	$listHtml = "<".$element." class='".$listviewClasses."'>";
	$length = count( $list );
	$current = $length;
	foreach( $list as $item ){
		if( gettype( $item ) === "string" ){
			$listHtml .= "<li class='ui-li-static ui-body-inherit";
			if( $current === $length ){
				$listHtml .= " ui-first-child";
			}
			$current--;
			if( $current === 0 ){
				$listHtml .= " ui-last-child";
			}
			$listHtml .= "'><h1>".$item."</h1></li>";
		} else {
			$itemClass = "";
			if( !array_key_exists( "href", $item ) ){
				$itemClass .= " ui-li-static ui-body-inherit";
			}
			if( array_key_exists( "img", $item ) ){
				$itemClass .= " ui-li-has-thumb";
			}
			if( $current === $length ){
				$itemClass .= " ui-first-child";
			}
			$current--;
			if( $current === 0 ){
				$itemClass .= " ui-last-child";
			}
			$itemHtml = "<li class='".$itemClass."'>";
			if( array_key_exists( "href", $item ) ){
				$itemHtml .= "<a href='".$item[ 'href' ]."' class='ui-btn ui-btn-icon-right ui-icon-carat-r'";
				if( array_key_exists( "ajax", $item ) && $item['ajax'] === false ){
					$itemHtml .= " data-ajax='false'";
				}
				$itemHtml .= ">";
			}
			if( array_key_exists( "img", $item ) ){
				$itemHtml .= "<img src='".$item['img']."'>";
			}
			if( array_key_exists( "h1", $item ) ){
				$itemHtml .= "<h1>".$item[ 'h1' ]."</h1>";
			}
			if( array_key_exists( "p", $item ) ){
				$itemHtml .= "<p>".$item[ 'p' ]."</p>";
			}
			if( array_key_exists( "text", $item ) ){
				$itemHtml .= $item[ 'text' ];
			}
			if( array_key_exists( "href", $item ) ){
				$itemHtml .= "</a>";
			}
			$itemHtml .= "</li>";
			$listHtml .= $itemHtml;
		}
	}
	$listHtml .= "</".$element.">";
	return $listHtml;

}
function code_example( $file, &$ci )
{
	if( $ci !== false ){
		$code = preg_replace('/</', "&lt;", $ci->load->view( "code-examples/".$file,"",true ) );
	} else {
		$code = preg_replace('/</', "&lt;", $file);
	}
	return "<pre class='brush: php; html-script: true'>\n".$code."</pre>";
}
?>