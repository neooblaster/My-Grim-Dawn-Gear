function uploadImage(src){
	var xQuery = new xhrQuery();
		xQuery.target('/XHR/Admin/upload_image.php');
		xQuery.values('tag_name='+src.getAttribute('data-image-name'));
		xQuery.inputs(src);
		xQuery.callbacks(
			function(e){
				var item_image = document.querySelector('#item_image');
					item_image.src = "/Images/Items/NotFound.png";
					item_image.src = "/Images/Items/"+src.getAttribute('data-image-name')+'.png?new='+(new Date).getTime();
			}
		);
	
		xQuery.send();
}