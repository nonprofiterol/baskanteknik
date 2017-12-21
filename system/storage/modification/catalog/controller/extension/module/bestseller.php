<?php
class ControllerExtensionModuleBestSeller extends Controller {
	public function index($setting) {

		// pavo version 2.2
		$this->load->language('extension/module/themecontrol');
		$data['objlang'] = $this->registry->get('language');
		$data['ourl'] = $this->registry->get('url');

		$config = $this->registry->get("config");
		$data['sconfig'] = $config;
		$data['themename'] = $config->get("theme_default_directory");
		// end edit

		$this->load->language('extension/module/bestseller');

		$data['heading_title'] = $this->language->get('heading_title');	

		$data['text_tax'] = $this->language->get('text_tax');

		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		$data['products'] = array();

		$results = $this->model_catalog_product->getBestSellerProducts($setting['limit']);

		if ($results) {
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
				}

				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = $result['rating'];
				} else {
					$rating = false;
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
			
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'rating'      => $rating,
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
			}

			return $this->load->view('extension/module/bestseller', $data);
		}
	}
}
