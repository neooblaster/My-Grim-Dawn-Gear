function _drag(ev){
	ev.dataTransfer.setData("text", ev.target.id);
   alert("next");
}

function _allowDrop(ev) {
    ev.preventDefault();
}

function _drop(ev) {
    ev.preventDefault();
    //var data = ev.dataTransfer.getData("text");
    //ev.target.appendChild(document.getElementById(data));
}