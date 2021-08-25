<?php
class ControllerExtensionModuleCallbackFooter extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/callbackfooter');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_callbackfooter', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/callbackfooter', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/callbackfooter', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->post['module_callbackfooter_status'])) {
			$data['module_callbackfooter_status'] = $this->request->post['module_callbackfooter_status'];
		} else {
			$data['module_callbackfooter_status'] = $this->config->get('module_callbackfooter_status');
		}

		if (isset($this->request->post['module_callbackfooter_footer'])) {
			$data['module_callbackfooter_footer'] = $this->request->post['module_callbackfooter_footer'];
		} else {
			$data['module_callbackfooter_footer'] = $this->config->get('module_callbackfooter_footer');
		}

		if (isset($this->request->post['module_callbackfooter_email'])) {
			$data['module_callbackfooter_email'] = $this->request->post['module_callbackfooter_email'];
		} else {
			$data['module_callbackfooter_email'] = $this->config->get('module_callbackfooter_email');
		}

		if (isset($this->request->post['module_callbackfooter_title'])) {
			$data['module_callbackfooter_title'] = $this->request->post['module_callbackfooter_title'];
		} else {
			$data['module_callbackfooter_title'] = $this->config->get('module_callbackfooter_title');
		}

		if (isset($this->request->post['module_callbackfooter_main'])) {
			$data['module_callbackfooter_main'] = $this->request->post['module_callbackfooter_main'];
		} else {
			$data['module_callbackfooter_main'] = $this->config->get('module_callbackfooter_main');
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/callbackfooter', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/callbackfooter')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}