function updateItem(id, property, property_value){
	var xQuery = new xhrQuery();
		xQuery.target('/XHR/Admin/update_item.php');
		xQuery.values(
			'ID='+id,
			'property='+property,
			'property_value='+property_value
		);
	
		xQuery.callbacks(
			function(e){
				console.log(e);
			}
		);
	
		xQuery.send();
}