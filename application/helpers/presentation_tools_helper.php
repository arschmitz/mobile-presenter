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
function timelinePanel( $events )
{
	$length = count( $events );
	$years = ceil( ( strtotime( $events[ $length - 1 ][ "date" ] ) - strtotime( $events[ 0 ][ "date" ] ) )/ 31536000 ) ;
	$year = 0;
	$panel = "";
	$count = $length;
	foreach( $events as $event ) {
		$currentYear = date( "Y", strtotime( $event[ "date" ] ) );
		if ( $year !== $currentYear ) {
			$year = $currentYear;
			$panel .= "<li>";
			$panel .= "<a class='ui-btn ui-btn-icon-right ui-icon-carat-r' href='#timeline-$currentYear'>Timeline $currentYear</a>";
			$panel .= "</li>";
		}
	}
	echo $panel;
}
function timelineNotes( $events )
{
	$length = count( $events );
	$years = ceil( ( strtotime( $events[ $length - 1 ][ "date" ] ) - strtotime( $events[ 0 ][ "date" ] ) )/ 31536000 ) ;
	$year = 0;
	$html = "";
	$count = $length;
	foreach( $events as $event ) {
		$currentYear = date( "Y", strtotime( $event[ "date" ] ) );
		if ( $year !== $currentYear && $year !== 0 ) {
			$html .= "</div>";
			$html .= "</div>";
			$html .= "</div>";
			$html .= "</div>";
		}
		if ( $year !== $currentYear ) {
				$html .= "<div data-role='page' class='ui-page ui-page-theme-a ui-page-header-fixed ui-page-footer-fixed preso-notes-page' id='timeline-".$currentYear."' data-defaults='true'>";
				$html .= "<div class='ui-content' role='main'>";
				$html .= "<div class=\"ui-grid-a\">";
				$html .= "<div class=\"ui-block-a\">placeholder</div>";
				$html .= "<div class=\"ui-block-b preso-notes-block ui-content\">";
				$year = $currentYear;
		}
		$html .= "<span class='note-slide'><p>".$event[ "notes" ]."</p></span>";
	}
	$html .= "</div>";
	$html .= "</div>";
	$html .= "</div>";
	$html .= "</div>";
	return $html;
}
function timeline( $events, $title, $type )
{
	if( $type === "panel" ) {
		return timelinePanel( $events );
	} else if( $type === "notes" ) {
		return timelineNotes( $events );
	}
	$length = count( $events );
	$years = ceil( ( strtotime( $events[ $length - 1 ][ "date" ] ) - strtotime( $events[ 0 ][ "date" ] ) )/ 31536000 ) ;
	$year = 0;
	$html = "";
	$count = $length;
	foreach( $events as $event ) {
		$pointer = "";
		$currentYear = date( "Y", strtotime( $event[ "date" ] ) );
		$day = date( "z", strtotime( $event[ "date" ] ) );
		$left = ( $day / 365 ) * 100;
		$right = "auto";
		if ( $left >= 85 ) {
			$right = 100 - $left;
			$right .= "%";
			$left = "auto";
			$pointer = "timeline-entry-pointer-right";
		} else {
			$left .= "%";
		}
		if ( $year !== $currentYear ) {
			$year = $currentYear;
			if( $html !== "" ) {
				$html .= "</div>";
			}
			$html .= "<div data-role='page' class='timeline ui-page ui-page-theme-a ui-page-header-fixed ui-page-footer-fixed' id='timeline-".$year."' data-defaults='true'>";
			$html .= "<div class='timeline-title'>".$year."</div>";
		}
		$html .= "<div style='right: ".$right."; left: ".$left."; z-index: ".$count--."' class='timeline-entry timeline-".$event[ "position" ]."'>";
		$html .= "<img class='timeline-image' src='/images/".$event[ "project" ].".png'>";
		$html .= $event[ "entry" ];
		$html .= "<div class='timeline-entry-date'>".date( "F j", strtotime( $event[ "date" ] ) )."</div>";
		$html .= "<a class='timeline-entry-link' href='".$event[ "link" ]."'>Link</a>";
		$html .= "<div class='timeline-entry-pointer $pointer'></div>";
		$html .= "</div>";
	}
	$html .= "</div>";
	$html .= "<div class='timeline-bar'></div>";
	$html .= "<div data-role='popup' id='event-popup'></div>";
	echo $html;
}
?>