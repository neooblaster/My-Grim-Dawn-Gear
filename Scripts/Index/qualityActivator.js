function qualityActivator(src){
	/** Input hidden target **/
	var input_target = document.querySelector('#'+src.getAttribute('data-input-target'));
	
	/**  **/
	if(input_target.value === 'true'){
		input_target.value = "false";
		src.classList.remove('active');
	} else {
		input_target.value = "true";
		src.classList.add('active');
	}
}