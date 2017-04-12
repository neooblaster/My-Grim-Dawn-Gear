<style>
	#template_manual {
		font-family: "Consolas";
		font-size: 0.9rem;
	}
	
	#template_manual h1{
		font-size: 1.25rem;
		color: #336699
	}
	
	#template_manual .template_manual_method {
		font-weight: bold;
	}	
	
	#template_manual .template_manual_methode_description {
		margin: 10px 40px;
		color: #626262;
	}
	
	#template_manual .tpl_typeof{
		color: #669933
	}
	
	#template_manual .tpl_params:before {
		content: "( ";
	}
	
	#template_manual .tpl_params:after {
		content: " ) :";
	}
	
	#template_manual .tpl_method_name,
	#template_manual .tpl_param
	{
		color: #336699
	}
	
	#template_manual .tpl_button {
		cursor: pointer;
	}
</style>

<script type="text/javascript" language="javascript">
	function descManager(id){
		var desc_div = document.querySelector('#desc_'+id);
		var tp_button = document.querySelector('#tpl_button_'+id);
		var current_display = desc_div.style.display;
		
		desc_div.style.display = (current_display == 'none') ? 'block' : 'none';	
		tp_button.innerHTML = (current_display == 'none') ? '[-]' : '[+]';	
	}
</script>


<div id="template_manual">
	<h1>Below, the list of all available methods of Template class.</h1>
	<!-- BEGIN_BLOCK METHOD_DECLARE -->
	<div class="template_manual_method">
		<div class="template_manual_method_declaration">
			<span class="tpl_modifier">%METH_MODIFIER%</span>
			<span class="tpl_typeof">%METH_TYPEOF%</span>
			<span class="tpl_method_name">Template::%METH_NAME%</span>
			<span class="tpl_params">
			<!-- BEGIN_BLOCK METH_PARAMS -->
				<span class="tpl_typeof">%PARAM_TYPEOF%</span>
				<span class="tpl_param">%PARAM_NAME%</span>
			<!-- END_BLOCK METH_PARAMS -->
			</span>
			<span id="tpl_button_%METH_ID%" class="tpl_button" onclick="descManager(%METH_ID%)">[+]</span>
		</div>
		<div id="desc_%METH_ID%" class="template_manual_methode_description" style="display: none;">
		%METH_DESC%
		</div>
	</div>
	<!-- END_BLOCK METHOD_DECLARE -->
</div>