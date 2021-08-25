<?php
class ControllerExtensionTmdHeader extends Controller {
	public function index() {
		$this->load->language('extension/tmdheader');

		$data['text_all'] = $this->language->get('text_all');

		// Menu
		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$this->load->model('catalog/information');
		$this->load->model('tool/image');
		$this->load->language('common/menu');

		$data['informations'] = array();

		foreach ($this->model_catalog_information->getInformations() as $result) {
			if ($result['bottom']) {
				$data['informations'][] = array(
					'title' => $result['title'],
					'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id'])
				);
			}
		}
		$data['new'] = $this->url->link('information/information', array('information_id'=>15));
		$data['sale'] = $this->url->link('information/information', array('information_id'=>16));
		$data['about_us_toko'] = $this->url->link('information/information', array('information_id'=>4));
		$data['delivery_toko'] = $this->url->link('information/information', array('information_id'=>6));
		$data['reviews_toko'] = $this->url->link('information/information', array('information_id'=>17));
		$data['contact_toko'] = $this->url->link('information/information', array('information_id'=>14));
		$data['choice_buyers'] = $this->url->link('information/information', array('information_id'=>19));
		$data['sale_day'] = $this->url->link('information/information', array('information_id'=>27));
		$data['contact'] = $this->url->link('information/contact');

		$data['categories'] = array();

		$data['all_product'] = $this->url->link('product/category&path=59');
		$data['blog_toko'] = $this->url->link('product/category&path=65');
		$data['big_toko'] = $this->url->link('product/category&path=94');

		$categories = $this->model_catalog_category->getCategories(0);

		foreach ($categories as $category) {
			if ($category['top']) {
				// Level 2
				$children_data = array();

				$children = $this->model_catalog_category->getCategories($category['category_id']);

				foreach ($children as $child) {
					$filter_data = array(
						'filter_category_id'  => $child['category_id'],
						'filter_sub_category' => true
					);

					$children_data[] = array(
						'name'  => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
						'thumb'     => $this->model_tool_image->resize($child['image'], 62, 90),
						'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
					);
				}

				// Level 1
				$data['categories'][] = array(
					'name'     => $category['name'],
					'children' => $children_data,
					'column'   => $category['column'] ? $category['column'] : 1,
					'thumb'     => $this->model_tool_image->resize($category['image'], 62, 90),
					'href'     => $this->url->link('product/category', 'path=' . $category['category_id'])
				);
			}
		}

		$data['headermenus'] = array();
		$this->load->model('extension/headermenu');
		$data['headermenu'] =$this->model_extension_headermenu->getHeadermenu();

		$this->load->model('setting/extension');

    $data['telephone'] = $this->config->get('config_telephone');
    $data['telephone2'] = $this->config->get('config_telephone2');
    $data['telephone3'] = $this->config->get('config_telephone3');
    $data['telephone_href'] = $this->config->get('config_telephone_href');
    $data['telephone_href2'] = $this->config->get('config_telephone_href2');
    $data['telephone_href3'] = $this->config->get('config_telephone_href3');
    $data['viber'] = $this->config->get('config_viber');
    $data['telegram'] = $this->config->get('config_telegram');
    $data['instagram'] = $this->config->get('config_instagram');
    $data['facebook'] = $this->config->get('config_facebook');
    $data['email'] = $this->config->get('config_email');
    $data['contact'] = $this->url->link('information/contact');

		return $this->load->view('extension/tmdheader', $data);
	}
}
