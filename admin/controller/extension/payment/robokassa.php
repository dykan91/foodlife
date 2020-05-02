<?php
class ControllerExtensionPaymentRobokassa extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/robokassa');

		$this->document->setTitle($this->language->get('page_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('payment_robokassa', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
		}

		$data['payment_robokassa_result_url'] = HTTP_CATALOG . 'index.php?route=extension/payment/robokassa/callback';
		$data['payment_robokassa_success_url'] = HTTP_CATALOG . 'index.php?route=extension/payment/robokassa/success';
		$data['payment_robokassa_fail_url'] = HTTP_CATALOG . 'index.php?route=extension/payment/robokassa/fail';

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['login'])) {
			$data['error_login'] = $this->error['login'];
		} else {
			$data['error_login'] = '';
		}

		if (isset($this->error['password1'])) {
			$data['error_password1'] = $this->error['password1'];
		} else {
			$data['error_password1'] = '';
		}

		if (isset($this->error['password2'])) {
			$data['error_password2'] = $this->error['password2'];
		} else {
			$data['error_password2'] = '';
		}

		$data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
            'text'      => $this->language->get('text_home'),
            'separator' => FALSE
        );

        $data['breadcrumbs'][] = array(
            'href'      => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true),
            'text'      => $this->language->get('text_payment'),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'href'      => $this->url->link('extension/payment/robokassa', 'user_token=' . $this->session->data['user_token'], true),
            'text'      => $this->language->get('page_title'),
            'separator' => ' :: '
        );
		
		$data['action'] = $this->url->link('extension/payment/robokassa', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token']. '&type=payment', true);

		if (isset($this->request->post['payment_robokassa_login'])) {
			$data['payment_robokassa_login'] = $this->request->post['payment_robokassa_login'];
		} else {
			$data['payment_robokassa_login'] = $this->config->get('payment_robokassa_login');
		}

		if (isset($this->request->post['payment_robokassa_password1'])) {
			$data['payment_robokassa_password1'] = $this->request->post['payment_robokassa_password1'];
		} else {
			$data['payment_robokassa_password1'] = $this->config->get('payment_robokassa_password1');
		}

		if (isset($this->request->post['payment_robokassa_password2'])) {
			$data['payment_robokassa_password2'] = $this->request->post['payment_robokassa_password2'];
		} else {
			$data['payment_robokassa_password2'] = $this->config->get('payment_robokassa_password2');
		}
		
		if (isset($this->request->post['payment_robokassa_test'])) {
			$data['payment_robokassa_test'] = $this->request->post['payment_robokassa_test'];
		} else {
			$data['payment_robokassa_test'] = $this->config->get('payment_robokassa_test');
		}

		if (isset($this->request->post['payment_robokassa_order_status_id'])) {
			$data['payment_robokassa_order_status_id'] = $this->request->post['payment_robokassa_order_status_id'];
		} else {
			$data['payment_robokassa_order_status_id'] = $this->config->get('payment_robokassa_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payment_robokassa_geo_zone_id'])) {
			$data['payment_robokassa_geo_zone_id'] = $this->request->post['payment_robokassa_geo_zone_id'];
		} else {
			$data['payment_robokassa_geo_zone_id'] = $this->config->get('payment_robokassa_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['payment_robokassa_status'])) {
			$data['payment_robokassa_status'] = $this->request->post['payment_robokassa_status'];
		} else {
			$data['payment_robokassa_status'] = $this->config->get('payment_robokassa_status');
		}

		if (isset($this->request->post['payment_robokassa_sort_order'])) {
			$data['payment_robokassa_sort_order'] = $this->request->post['payment_robokassa_sort_order'];
		} else {
			$data['payment_robokassa_sort_order'] = $this->config->get('payment_robokassa_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/robokassa', $data));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/robokassa')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['payment_robokassa_login']) {
			$this->error['login'] = $this->language->get('error_login');
		}

		if (!$this->request->post['payment_robokassa_password1']) {
			$this->error['password1'] = $this->language->get('error_password1');
		}

		if (!$this->request->post['payment_robokassa_password2']) {
			$this->error['password2'] = $this->language->get('error_password2');
		}

		return !$this->error;
	}
}