<?php
if(defined("Balitbang") or defined("CMSBalitbang") ) {

}
else {
	echo ("<h1>Permission Denied</h1>You don't have permission to access the this page.");
    exit;
}
/**
 * @author alanrm82
 * @copyright 2011
 */

function editor_full() {
    $cetak .= '<script language="javascript" type="text/javascript" src="../tiny_mce/tiny_mce.js"></script>
	<script language="javascript" type="text/javascript">
		tinyMCE.init({
			mode : "textareas",
			elements : "textareas",
			theme : "advanced",
			plugins : "pagebreak,style,fmath_formula,table,save,advimage,advlink,insertdatetime,preview,media, searchreplace,print,paste,fullscreen,noneditable,visualchars,xhtmlxtras,autosave",

		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,code,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,fmath_formula,charmap,media,|,print,|,fullscreen,help",
        theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,
			file_browser_callback : "ajaxfilemanager",
			paste_use_dialog : false,
			theme_advanced_resizing : true,
			theme_advanced_resize_horizontal : true,
			apply_source_formatting : true,
			force_br_newlines : true,
			force_p_newlines : false,	
			relative_urls : true
		});';

$cetak .= '
		function ajaxfilemanager(field_name, url, type, win) {
			var ajaxfilemanagerurl = "../../../tiny_mce/plugins/ajaxfilemanager/ajaxfilemanager.php";
			var view = "detail";
			switch (type) {
				case "image":
				view = "thumbnail";
					break;
				case "media":
					break;
				case "flash": 
					break;
				case "file":
					break;
				default:
					return false;
			}
            tinyMCE.activeEditor.windowManager.open({
                url: "../../../tiny_mce/plugins/ajaxfilemanager/ajaxfilemanager.php?view=" + view,
                width: 782,
                height: 440,
                inline : "yes",
                close_previous : "no"
            },{
                window : win,
                input : field_name
            });
            
		}
	</script>';
    return $cetak;
}

function editor_standar() {
    $cetak .= '<script type="text/javascript" src="../tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "textareas",
		elements : "textareas",
		theme : "advanced",
		plugins : "paste",

		// Theme options
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,bullist,numlist,|,undo,redo,|,link,unlink",
        theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

	});
</script>
<!-- /TinyMCE -->';	
    return $cetak;
}

?>