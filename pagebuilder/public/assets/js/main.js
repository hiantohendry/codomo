//Global Var
var page_id = "", page_name ="", currItem = "", max_image = 10;

$(document).ready(function(){
	if($(".board[page-id]").length > 0){
		loadPage($(".board").attr("page-id"), false);
	}
	
	addEditor();
	
	$.ajaxSetup({
   		headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
	});

	$("#lock-button").on("click", function(){
		$(this).toggleClass("locked");
	});

	$(".upload-button").on("click", function(e){
		uploadImage();
	});

	$(".new-button").on("click", function(e){
		newPage();
	});

	$(".modal-overlay").on("click", function(){
		$(this).removeClass("isOverlay");
		$(".isOpen").removeClass("isOpen");
	});

	$("[data-toggle='modal-close']").on("click", function(){
		$(".isOpen").removeClass("isOpen");
		$(".modal-overlay").removeClass("isOverlay");
	});

	$(".modal-toggle").on("click", function(){
		$this = $(this);
		$target = $this.attr("target");
		$("#" + $target).addClass("isOpen");
		$(".modal-overlay").addClass("isOverlay");
	});

	$(".texteditor-overlay").on("click", function(){
		toggleOverlay("texteditor");
		endEditor();
	});
	
	$(".page").click(function(){
		$(".active").draggable({disabled:true});
		$(".active").removeClass("active");
		$("#item-id").html(this.id);
		currItem = "#Page";
		showProp(currItem);
	});
	
	$("#lock").change(function(){
		if($(this).prop("checked")){
			$(currItem).draggable({disabled:true});
			$(currItem).addClass("locked");
		}
		else {
			$(currItem).draggable("enable");
			$(currItem).removeClass("locked");
		}
	});
	
	$(".input-default").change(function(){
		if($(this).attr("data-css") == "box-shadow") {
			if($(this).prop("checked"))
				$(currItem).css("box-shadow", "5px 5px 10px rgba(0, 0, 0, .5)");
			else
				$(currItem).css("box-shadow", "none");
		}
		else {
			$(currItem).css($(this).attr("data-css"), $(this).val() + (($(this).attr("type") == "number")?"px":""));
		}
		
		if($(currItem).hasClass("image")) {
			if(this.id == "width") {
				$(currItem).css("height", "auto");
				$("#height").val($(currItem).height());
			}
			else if(this.id == "height") {
				$(currItem).css("width", "auto");
				$("#width").val($(currItem).width());
			}
		}
	});
});

function addItem(type, src){
	var key = "";
	switch(type) {
		case "text":
			key = "Text" + ++$(".text").length;
			$(".page").append("<div class='item text' id='" + key + "'></div>");
			$.each(defaults["text"], function(prop, value){
				$("#" + key).css(prop, value)
			});
			addEditable("#" + key);
			break;
		case "image":
			key = "Image" + ++$(".image").length;
			$(".page").append("<img class='item image' id='" + key + "' src='" + src + "' />");
			$.each(defaults["image"], function(prop, value){
				$("#" + key).css(prop, value)
			});
			break;
	}
	addDraggable("#" + key);
}

function addImage(){
	if($(".image").length >= max_image) {
		showMessage("Message", "Gambar full");
	}
	else {
		toggleModal('image-upload');
	}
}

function deleteItem(){
	$(currItem).remove();
	$('#modal-delete').removeClass('isOpen');
	$('.isOverlay').removeClass('isOverlay');
}

function addDraggable(id){
	$(id).draggable({
		stack: ".item",
		containment: "parent"
	});
	$(id).draggable("disable");
	
	$(id).click(function(){
		$(".active").draggable({disabled:true});
		$(".active").removeClass("active");
		
		currItem = "#" + this.id;
		$("#item-id").html(this.id);
		
		$(this).addClass("active");
		if(!$(id).hasClass("locked"))$(this).draggable("enable");
		
		showProp(currItem);
		return false;
	});
}

function addEditable(id){
	$(id).dblclick(function(e){
		var text = $(this).html();
		d("tes");
		toggleOverlay("texteditor");
		$("#text-editor").code(text);
		$("#text-editor").attr("for", this.id);
		$(".note-editor").css("top", $(this).css("top"));
		$(".note-editor").css("left", $(this).css("left"));
		$(".note-editor").show();
	});
}

function addEditor(){
	$("#text-editor").summernote({
		toolbar: [
			['style', ['bold', 'italic', 'underline']],
			['name', ['fontname']],
			['size', ['fontsize']],
			['para', ['ul', 'ol', 'paragraph']],
			['height', ['height']],
			['color', ['color']],
			['insert', ['link']]
		],
		height: 200,
		width: 500
	});
}

function endEditor(){
	var text = $("#text-editor").code();
	var item = "#" + $("#text-editor").attr("for");
	$(item).html(text);
	$("#text-editor").attr("for", "none");
	$(".note-editor").hide();
}

function showProp(id){
	$("#width").val($(id).css("width").replace(/[^-\d\.]/g, ''));
	$("#height").val($(id).css("height").replace(/[^-\d\.]/g, ''));
	$("#pad-top").val($(id).css("padding-top").replace(/[^-\d\.]/g, ''));
	$("#pad-left").val($(id).css("padding-left").replace(/[^-\d\.]/g, ''));
	$("#pad-right").val($(id).css("padding-right").replace(/[^-\d\.]/g, ''));
	$("#pad-bottom").val($(id).css("padding-bottom").replace(/[^-\d\.]/g, ''));
	$("#border-top").val($(id).css("border-top-width").replace(/[^-\d\.]/g, ''));
	$("#border-left").val($(id).css("border-left-width").replace(/[^-\d\.]/g, ''));
	$("#border-right").val($(id).css("border-right-width").replace(/[^-\d\.]/g, ''));
	$("#border-bottom").val($(id).css("border-bottom-width").replace(/[^-\d\.]/g, ''));
	$("#border-color").val(rgb2hex($(id).css("border-color")));
	
	if($(id).css("box-shadow") == "none") $("#box-shadow").prop("checked", false);
	else $("#box-shadow").prop("checked", true);
	
	if($(id).hasClass("locked")) $("#lock").prop("checked", true);
	else $("#lock").prop("checked", false);
}

function toggleLoading(){
	$(".loading-overlay").toggleClass("isOverlay");
}

function setIdInHidden(){
	$("input[name=id]").val(page_id);
}

function setNameInModal(){
	$("input[name=page_name]").val(page_name);
}

function newPage(){
	var options = {
		beforeSubmit: toggleLoading,
    	success:  newPageResponse,
    	dataType: 'json'
    };

	$('#form-new').ajaxForm(options).submit();
}

function newPageResponse(response, statusText, xhr, $form)  { 
    if(response.success == false)
    {
        var arr = response.errors;
        $.each(arr, function(index, value)
        {
            if (value.length != 0)
            {
            	
            }
        });
    } else {
    	toggleLoading();
	    toggleModal("new");
    	if(response.status == 200){
    		page_id = response.data.id;
    		page_name = $("#new-name").val();
    		setIdInHidden();
    		setNameInModal();
    		$("#Page").css({
    			width: $("#new-width").val() +"px",
    			height: $("#new-height").val() +"px"
    		});
    		$(".item").detach();
    		$("#form-new")[0].reset();
	    	showMessage("Message", "Page successfully created");
    	}
    	else{
	    	showMessage("Message", "Something went wrong");
    	}
    }
}

function uploadImage(){
	$("#image-name").val($(".image").length);
	$("#image-id").val(page_id);
	
	var options = {
		beforeSubmit: toggleLoading,
		success:  uploadImageResponse,
		dataType: 'json'
	};

	$('#form-upload').ajaxForm(options).submit();
}

function uploadImageResponse(response, statusText, xhr, $form)  { 
    if(response.success == false)
    {
        var arr = response.errors;
        $.each(arr, function(index, value)
        {
            if (value.length != 0)
            {
            	alert(value);
            }
        });
		toggleLoading();
    } else {
        addItem('image', '../pages/' + page_id + '/' + response.data.filename);
		toggleLoading();
	    toggleModal("upload");
    	if(response.status == 200){
	    	showMessage("Message", "Upload Success");
    	}
    	else{
	    	showMessage("Message", "Something went wrong");
    	}
    }
}

function savePage(){
	toggleLoading();
	
	var items = [];
	
	$(".item").each(function(){
		var attr = [];
		var id = this.id;
		var type = "";
		var value = "";
		
		switch($(this).prop("tagName")){
			case "DIV":
				type = "text";
				value = $(this).html();
				break;
			case "IMG":
				type = "image";
				value = $(this).attr("src");
				break;
		}
		
		items.push({"id":id, "type":type, "value":value, "style":$(this).attr("style")});
	});
	
	var page_setup = {"width":$("#Page").css("width"), "height":$("#Page").css("height")};
	var new_name = $("#save-name").val();
	
	$.ajax({
		type: "POST",
		url: "savepage",
		data: {
			content_json: JSON.stringify(items),
			page_setup: JSON.stringify(page_setup),
			id: page_id, 
			page_name: new_name
		},
		success: function(response){
			toggleLoading();
			toggleModal("save");
			response = JSON.parse(response);
			if(response.status == 200){
				page_name = $("#save-name").val();
	    		$("#form-save")[0].reset();
				setNameInModal();
		    	showMessage("Message", "Page successfully saved");
	    	}
	    	else{
		    	showMessage("Message", "Something went wrong");
	    	}
		}
	});
}

function savePageResponse(response, statusText, xhr, $form)  { 
    if(response.success == false)
    {
        var arr = response.errors;
        $.each(arr, function(index, value)
        {
            if (value.length != 0)
            {
            	
            }
        });
    } else {
    	toggleLoading();
	    toggleModal("new");
    	if(response.status == 200){
    		page_name = $("#new-name").val();
    		setIdInHidden();
    		setNameInModal();
    		$("#Page").css({
    			width: $("#new-width").val() +"px",
    			height: $("#new-height").val() +"px"
    		});
    		$(".item").detach();
    		$("#form-new")[0].reset();
	    	showMessage("Message", "Page successfully created");
    	}
    	else{
	    	showMessage("Message", "Something went wrong");
    	}
    }
}


function loadPage(_page_id, flag){
	$.ajax({
		type: "GET",
		url: "loadpage",
		data: {
			page_id: _page_id
		},
		success: function(response){
			response = JSON.parse(response);

			if(response.status == 200){
				page_id = response.data.id;
				page_name = response.data.name;
				page_setup = JSON.parse(response.data.page_setup);
				items = JSON.parse(response.data.content_json);
				
				$(".item").detach();
				
				$("#Page").css({
					width: page_setup.width,
					height: page_setup.height
				});
				
				loadItems(items, flag);
				showMessage("Message", "Page successfully created");
			}
			else{
				showMessage("Message", "Something went wrong");
			}
		}
	});
}

function loadItems(items, flag){
	$.each(items, function(key, val){	
		switch(val.type){
			case "text":
				$(".page").append("<div class='item text' id='" + val.id + "' style='" + val.style + "'>" + val.value + "</div>");
				if(flag) addEditable("#" + val.id);
				break;
			case "image":
				$(".page").append("<img class='item image' id='" + val.id + "' style='" + val.style + "' src='" + val.value + "' />");
				break;
		}
		
		if(flag) addDraggable("#" + val.id);
	})
}

function d(string){console.log(string)}

function listPage(){
	toggleLoading();
	
	$.ajax({
		type: "POST",
		url: "list",
		data: {
			userid: "2"
		},
		success: function(response){
			toggleLoading();
			response = JSON.parse(response);
			if(response.status == 200){
				toggleModal("open");
				var $list = $("#list-page");
				$list.empty();
				var data = response.data;
				var $list_page ="";

				$.each(data, function(i, item){
				    $list_page += "<li><a onclick='loadPage("+data[i].id+", true)' href=\"javascript:void(0)\">"+data[i].name+"<div class=\"timestamp\">"+data[i].updated_at+"</div></a></li>";
				})

				$list.html($list_page);
	    	}
	    	else{
		    	showMessage("Message", "Something went wrong");
	    	}
		}
	});
}

function savePageResponse(response, statusText, xhr, $form)  { 
    if(response.success == false)
    {
        var arr = response.errors;
        $.each(arr, function(index, value)
        {
            if (value.length != 0)
            {
            	
            }
        });
    } else {
    	toggleLoading();
	    toggleModal("new");
    	if(response.status == 200){
    		page_id = response.data.id;
    		page_name = $("#new-name").val();
    		setIdInHidden();
    		setNameInModal();
    		$("#Page").css({
    			width: $("#new-width").val() +"px",
    			height: $("#new-height").val() +"px"
    		});
    		$(".item").detach();
    		$("#form-new")[0].reset();
	    	showMessage("Message", "Page successfully created");
    	}
    	else{
	    	showMessage("Message", "Something went wrong");
    	}
    }
}

function toggleOverlay(target){
	$("."+target+"-overlay").toggleClass("isOverlay");
}

function toggleModal(target){
	toggleOverlay("modal");
	$("#modal-"+target).toggleClass("isOpen");
}

function showMessage(title, content){
	$("#info-header").text(title);
	$("#info-content").text(content);
	toggleModal("info");
}

var hexDigits = new Array("0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f"); 

function rgb2hex(rgb) {
	rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
	return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
}

function hex(x) {
	return isNaN(x) ? "00" : hexDigits[(x - x % 16) / 16] + hexDigits[x % 16];
}