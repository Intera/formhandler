<?php

class Tx_Formhandler_AjaxHandler_Jquery extends Tx_Formhandler_AbstractAjaxHandler {

	public function initAjax() {

	}

	public function fillAjaxMarkers(&$markers) {
		$settings = Tx_Formhandler_Session::get('settings');
		$initial = '';
		if($settings['ajax.']['config.']['initial']) {
			$initial = Tx_Formhandler_StaticFuncs::getSingle($settings['ajax.']['config.'], 'initial');
		}
		$loadingImg = t3lib_extMgm::extRelPath('formhandler') . 'Resources/Images/ajax-loader.gif';
		$loadingImg = '<img src="' . $loadingImg . ' "/>';
		if($settings['ajax.']['config.']['loading']) {
			$loadingImg = Tx_Formhandler_StaticFuncs::getSingle($settings['ajax.']['config.'], 'loading');
		}
		
		//parse validation settings
		if(is_array($settings['validators.'])) {
			foreach($settings['validators.'] as $key => $validatorSettings) {
				if(is_array($validatorSettings['config.']['fieldConf.'])) {
					foreach($validatorSettings['config.']['fieldConf.'] as $fieldname => $fieldSettings) {
						$replacedFieldname = str_replace('.', '', $fieldname);
						
						$markers['###validate_' . $replacedFieldname . '###'] = '
							<span class="loading" id="loading_' . $replacedFieldname . '" style="display:none">' . $loadingImg . '</span>
							<span id="result_' . $replacedFieldname . '">' . str_replace('###fieldname###', $replacedFieldname, $initial) . '</span>
							<script type="text/javascript">
							
								$(document).ready(function() {
									$("*[name*=' . $replacedFieldname . ']").blur(function() {
										var fieldVal = $(this).val();
										if($(this).attr("type") == "radio" || $(this).attr("type") == "checkbox") {
											if($(this).attr("checked") == "") {
												fieldVal = "";
											}
										}
										$("#loading_' . $replacedFieldname . '").show();
										$("#result_' . $replacedFieldname . '").hide();
										$("#result_' . $replacedFieldname . '").load("/index.php?id=' . $GLOBALS['TSFE']->id . '&eID=formhandler&field=' . $replacedFieldname . '&value=" + fieldVal ,
										function() {
										
											$("#loading_' . $replacedFieldname . '").hide();
											$("#result_' . $replacedFieldname . '").show();
										});
									});
								});
							
							</script>
						';
						
					}
				}
			}
		}
		
	}



}

?>