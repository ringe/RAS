// onClick function for the comment form legend.
// Update the legend and toggle the form visibility
function commentLegendClick() {
	new Effect.toggle('form_elements', 'blind', { duration: 0.2 });
	if ($('commentform_title').hasClassName("write")) {
		$('commentform_title').update('Klikk for &aring; skrive kommentar');
		$('commentform_title').removeClassName('write');
	} else {
		$('commentform_title').update('Skriv kommentar');
		$('commentform_title').addClassName('write');
	}
}

// onChange function for the activation form action
function updateKeyURL($input) {
  $form = $('activationform');
  $action = $form.readAttribute('action');
  $act = $action.sub(/(activate\/).*$/, 'activate/'+$input.getValue());
  $form.writeAttribute('action', $act);
}

// Initialize TinyMCE for Post body text area
tinyMCE.init({
    mode : "exact",
    elements : "body",
    theme : "advanced",
    skin : "o2k7",
    
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,"
		+ "justifycenter,justifyright,justifyfull,|,formatselect,fontselect,fontsizeselect",
    theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|"
    + ",outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|"
    + ",insertdate,inserttime,preview,|,forecolor,backcolor",
    theme_advanced_buttons3 : "sub,sup,|,charmap,emotions,iespell,media,advhr",
    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left",
    theme_advanced_resizing : true,
    height:"350px",
    width:"750px"
});

// Initialize TinyMCE for Post summary text area
tinyMCE.init({
    theme : "advanced",
    mode: "exact",
    elements : "summary",
    skin : "o2k7",
    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left",
    theme_advanced_buttons1 : "bold,italic,underline,strikethrough,separator,"
    + "justifyleft,justifycenter,justifyright,justifyfull,formatselect,"
    + "bullist,numlist,outdent,indent",
    theme_advanced_buttons2 : "",
    theme_advanced_buttons3 : "",
    height:"100px",
    width:"750px"
});

// Initialize TinyMCE for comment text area
tinyMCE.init({
    theme : "advanced",
    mode: "exact",
    elements : "comment",
    skin : "o2k7",
    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left",
    theme_advanced_buttons1 : "bold,italic,underline,strikethrough,separator,"
    + "justifyleft,justifycenter,justifyright,justifyfull,formatselect,"
    + "bullist,numlist,outdent,indent",
    theme_advanced_buttons2 : "",
    theme_advanced_buttons3 : "",
    height:"100px",
    width:"740px"
});
