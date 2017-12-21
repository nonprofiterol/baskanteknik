<?php
class ControllerExtensionModuleEbayListing extends Controller {
	public function index() {
		if ($this->config->get('ebay_status') == 1) {
			$this->load->language('extension/module/ebay');
			
			$this->load->model('tool/image');
			$this->load->model('extension/openbay/ebay_product');

			$data['heading_title'] = $this->language->get('heading_title');

			$data['products'] = array();

			$products = $this->cache->get('ebay_listing.' . md5(serialize($products)));

			if (!$products) {
				$products = $this->model_extension_openbay_ebay_product->getDisplayProducts();
				
				$this->cache->set('ebay_listing.' . md5(serialize($products)), $products);
			}

			foreach($products['products'] as $product) {
				if (isset($product['pictures'][0])) {
					$image = $this->model_extension_openbay_ebay_product->resize($product['pictures'][0], $this->config->get('ebay_listing_width'), $this->config->get('ebay_listing_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('ebay_listing_width'), $this->config->get('ebay_listing_height'));
				}

				$mkenqpro_btntext = false; $mkenqpro_btntype = false; $mkenqpro_flag = false;
			if(isset($result) && isset($result['mkenqpro_btntext']) && $result['mkenqpro_flag']) {
				$mkenqpro_btntext = $result['mkenqpro_btntext'];
				$mkenqpro_btntype = $result['mkenqpro_btntype'];
				$mkenqpro_flag = ($result['mkenqpro_btnhideprodbox']) ? $result['mkenqpro_flag'] : false;
			} else if(isset($product_info) && isset($product_info['mkenqpro_btntext']) && $product_info['mkenqpro_flag']) {
				$mkenqpro_btntext = $product_info['mkenqpro_btntext'];
				$mkenqpro_btntype = $product_info['mkenqpro_btntype'];
				$mkenqpro_flag = ($product_info['mkenqpro_btnhideprodbox']) ? $product_info['mkenqpro_flag'] : false;
			}
			$data['products'][] = array(
				'mkenqpro_btntext'  => $mkenqpro_btntext,
				'mkenqpro_btntype'  => $mkenqpro_btntype,
				'mkenqpro_flag'  => $mkenqpro_flag, 
			
					'thumb' => $image, 
					'name'  => base64_decode($product['Title']), 
					'price' => $this->currency->format($product['priceGross'], $this->session->data['currency']), 
					'href' => (string)$product['link']
				);
			}

			$data['tracking_pixel'] = $products['tracking_pixel'];

			return $this->load->view('extension/module/ebay', $data);
		}
	}
}