<?php
class ControllerExtensionModuleOptionsCategory extends Controller {
	public function index() {
		$json = array();

		// Display prices
		if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
			$json['price'] = $this->currency->format((float)$this->request->post['price'], $this->session->data['currency']);
			
			if ((float)$this->request->post['special']) {
				$json['special'] = $this->currency->format((float)$this->request->post['special'], $this->session->data['currency']);
			} else {
				$json['special'] = false;
			}
			
			if ((float)$this->request->post['tax']) {
				$json['tax'] = $this->currency->format((float)$this->request->post['tax'], $this->session->data['currency']);
			} else {
				$json['tax'] = false;
			}
		}	
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}