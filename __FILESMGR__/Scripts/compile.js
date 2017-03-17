function request_compile(preview, data){
	if(data === undefined){
		var code_version = document.getElementById('code_version').value;
		var description = document.getElementById('description').value;
		
		new xhrQuery().target('compile.php').callbacks(request_compile.bind('', '')).values('preview='+preview, 'version='+code_version, 'description='+description).send();
	} else{
		document.getElementById('compile_output').innerHTML = data;
	}
}

function request_compile_less(){
	document.getElementById('php_output').innerHTML += 'Compilation des fichiers LESS en cours...\n';
	new xhrQuery().target('Fonctions/compile_less.php').callbacks(function(e){document.getElementById('php_output').innerHTML += e;}).send();
}

function request_compile_js(){
	document.getElementById('php_output').innerHTML += 'Compilation des fichiers JavaScript JS en cours...\n';
	new xhrQuery().target('Fonctions/compile_js.php').callbacks(function(e){document.getElementById('php_output').innerHTML += e;}).send();
}

function clean_jsc(){
	document.getElementById('php_output').innerHTML += 'Suppresion des fichiers JavaScript Compilé .jsc en cours...\n';
	new xhrQuery().target('Fonctions/clean_jsc.php').callbacks(function(e){document.getElementById('php_output').innerHTML += e;}).send();
}

function request_flush_md5(data){
	if(data === undefined){
		new xhrQuery().target('Fonctions/flush_md5.php').callbacks(request_flush_md5).send();
	} else{
		document.getElementById('compile_output').innerHTML = data;
	}
}

function request_delete_package(src){
	var file = src.parentNode.getAttribute('file');
	
	new xhrQuery().target('Fonctions/delete_package.php').callbacks(request_flush_md5).values('package='+file).send();
	
	src.parentNode.parentNode.removeChild(src.parentNode);
}

function show_md5_snapshot(){
	var left = (window.innerWidth / 2) - 500;
	
	open('Fonctions/md5_viewer.php', '', 'width=1000, height=800, top=100, left='+left+', location=0, menubar=0, dependent=1');
}

function show_manifest(src){
	var file = src.parentNode.getAttribute('file');
	var left = (window.innerWidth / 2) - 500;
	
	open('Fonctions/manifest_viewer.php?file='+file, '', 'width=1000, height=800, top=100, left='+left+', location=0, menubar=0, dependent=1');
}

function edit_file(data){
	if(data === undefined){
		new xhrQuery().target('Fonctions/config_viewer.php').callbacks(edit_file).send();
	} else {
		var editor = document.createElement('textarea');
			editor.setAttribute('id', 'configs.ini');
			editor.setAttribute('style', 'width: 800px; height: 200px;');
			editor.value = data;
		
		var edit_button = document.getElementById('edit_button');
		var balise = document.getElementById('configs.ini');
		
		edit_button.value = "Sauvegarder";
		edit_button.onclick = save_file;
		
		balise.parentNode.replaceChild(editor, balise);
	}
}

function save_file(){
	var editor = document.getElementById('configs.ini');
	var config = editor.value;
	var edit_button = document.getElementById('edit_button');
	var pre = document.createElement('pre');
	
	pre.setAttribute('id', 'configs.ini');
	pre.innerHTML = config;
	editor.parentNode.replaceChild(pre, editor);
	
	edit_button.value = "Editer le fichier";
	edit_button.onclick = edit_file.bind('', undefined);
	
	//new xhrQuery().target('Fonctions/update_config.php').callbacks(request_flush_md5).values('content='+config).send();
	new xhrQuery().target('Fonctions/update_config.php')/**.callbacks(function(e){console.log(e);})**/.values('content='+config).send();
}

function exec_ligne(data){
	if(data === undefined){
		var cmd = document.getElementById('php_input').value;
		
		new xhrQuery().target('Fonctions/command_ligne.php').callbacks(exec_ligne).values("cmd="+cmd).send();
	} else {
		console.log(data);
		var output = document.getElementById('php_output');
		
		output.innerHTML += data;
	}
}

function set_md5(src){
	var file = src.parentNode.getAttribute('file');
	
	new xhrQuery().target('Fonctions/set_md5.php').callbacks(request_flush_md5).values('file='+file).send();
}

function edit_md5(){
	var left = (window.innerWidth / 2) - 500;
	
	open('Fonctions/edit_md5.php', '', 'width=1000, height=800, top=100, left='+left+', location=0, menubar=0, dependent=1');
}

function set_config_ini(src){
	var file = src.parentNode.getAttribute('file');
	
	new xhrQuery().target('Fonctions/set_config.ini.php').callbacks(
		function(e){
			document.getElementById('compile_output').innerHTML = e;
			reload_config_ini();
		}
	).values('file='+file).send();
}

function reload_config_ini(data){
	if(data === undefined){
		new xhrQuery().target('Fonctions/get_config.ini.php').callbacks(reload_config_ini).send();
	} else {
		document.getElementById('configs.ini').textContent = data;
	}
}

function show_config_ini(src){
	var file = src.parentNode.getAttribute('file');
	var left = (window.innerWidth / 2) - 500;
	
	open('Fonctions/config.ini_viewer.php?file='+file, '', 'width=1000, height=800, top=100, left='+left+', location=0, menubar=0, dependent=1');
}

function contentManager(src){
	var target = src.attributes.find(/target/gi);
	var content = document.querySelector('[content="'+target+'"]');
	
	if(content.attributes.find(/hidden/gi) === undefined){
		content.setAttribute('hidden', '');
		src.textContent = '+';
	} else {
		content.removeAttribute('hidden');
		src.textContent = '-';
	}
}

function reduceBuilder(){
	if(document.readyState === 'complete'){
		var reducers = document.querySelectorAll('h1 span');
		
		for(var i = 0; i < reducers.length; i++){
			reducers[i].addEventListener('click', contentManager.bind('', reducers[i]));
		}
	}
}

document.addEventListener('readystatechange', reduceBuilder);