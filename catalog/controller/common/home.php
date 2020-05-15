<?php
class ControllerCommonHome extends Controller {
	public function index() {
		$this->load->model('tool/image');
        $this->load->model('catalog/getProduct');
        $query = $this->model_catalog_getProduct->getCatProduct();
        
        
                $ret = [];

        foreach($query->rows as $prod) {
            $prod['full_image'] = $this->model_tool_image->resize($prod['image'], 300, 300);
            $prod['description'] = htmlspecialchars_decode($prod['description']);
            $prod['options'] = $this->model_catalog_getProduct->getOptions($prod['product_id']);
            
            $ret[$prod['category_name']][] = $prod;
        }

        $res = [];

        foreach($ret as $cat => $prods) {
            $res[] = [
                'name' => $cat,
                'product' => $prods
            ];
        }
        
        $data['products_all'] = $res;

        $category_id = 77;
			$results = $this->model_catalog_getProduct->getProducts($category_id);

$data['addons'] = [];
            
            
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], 100, 100);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', 100, 100);
				}

				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                    $price_num = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));
					$price = $this->currency->format($price_num, $this->session->data['currency']);

				} else {
					$price = false;
                    $price_num = 0;
				}
            

				if ((float)$result['special']) {
                   $special_num = $this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax'));
					$special = $this->currency->format($special_num, $this->session->data['currency']);
 
				} else {
					$special = false;
                    $special_num = 0;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}

if(!$special_num) {
    $base_price = $price_num;
} else {
    $base_price = $special_num;
}
                
				$data['addons'][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					 'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '800')) . '..',
					'price'       => $price,
                    'price_num' => $price_num,
					'special'     => $special,
                    'special_num'     => $special_num,
                    'base_price' => $base_price

				);
			}        
        
        
//        pr( $data['products_all']);



		$this->document->setTitle($this->config->get('config_meta_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));
		$this->document->setKeywords($this->config->get('config_meta_keyword'));

		if (isset($this->request->get['route'])) {
			$this->document->addLink($this->config->get('config_url'), 'canonical');
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

//        echo '—————-';
//        echo '<br/>';
//        var_dump($data);
//        exit();
		$this->response->setOutput($this->load->view('common/home', $data));

	}


}
