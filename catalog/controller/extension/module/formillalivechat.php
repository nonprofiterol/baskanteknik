<?php
class ControllerExtensionModuleFormillalivechat extends Controller {
	public function index() {
		$this->load->language('extension/module/formillalivechat');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['code'] = html_entity_decode($this->config->get('formilla_chat_id'));

		return $this->load->view('extension/module/formillalivechat.tpl', $data);  // updated since oc2.2 is smarter, doesn't need template path predefined checks
	}
}