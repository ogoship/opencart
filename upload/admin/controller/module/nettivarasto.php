<?php
require_once 'lib/API.php';
class ControllerModuleNettivarasto extends Controller {
	private $error = array();
	private $merchantID = '';
	private $secretToken = '';
	private $api = '';
	private $order_id = '';
	
	public function __construct($registry){
		parent::__construct( $registry );
		$this->error  = array();
		$this->logger = new Log('MySite.log');
		$this->merchantID = $this->config->get('nettivarasto_merchentid');
		$this->secretToken = $this->config->get('nettivarasto_secret_token');
		$this->api = new NettivarastoAPI($this->merchantID, $this->secretToken);
  	}
	
	public function set_order_id($order_id){
		$this->order_id = $order_id;
	}
	
	public function index() {
		$this->load->language('module/nettivarasto');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('nettivarasto', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
		}
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_admin'] = $this->language->get('entry_admin');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_merchentid'] = $this->language->get('entry_merchentid');
		$data['entry_secret_token'] = $this->language->get('entry_secret_token');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/nettivarasto', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('module/nettivarasto', 'token=' . $this->session->data['token'], true);
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);
		
		$this->load->model('extension/extension');

		$shipping_methods = $this->model_extension_extension->getInstalled('shipping');

		foreach($shipping_methods as $key=>$value){
			$strCodeKey	= "nettivarasto_code_".$value;
			$strStatusKey	= "nettivarasto_status_".$value;

			$data['shipping_methods'][$key]['code'] = $value;
			if (isset($this->request->post[$strCodeKey])) {
				$data['shipping_methods'][$key][$strCodeKey] = $this->request->post[$strCodeKey];
			} else {
				$data['shipping_methods'][$key][$strCodeKey] = $this->config->get($strCodeKey);
			}
			if (isset($this->request->post[$strStatusKey])) {
				$data['shipping_methods'][$key][$strStatusKey] = $this->request->post[$strStatusKey];
			} else {
				$data['shipping_methods'][$key][$strStatusKey] = $this->config->get($strStatusKey);
			}
		}
		if (isset($this->request->post['nettivarasto_admin'])) {
			$data['nettivarasto_admin'] = $this->request->post['nettivarasto_admin'];
		} else {
			$data['nettivarasto_admin'] = $this->config->get('nettivarasto_admin');
		}

		if (isset($this->request->post['nettivarasto_status'])) {
			$data['nettivarasto_status'] = $this->request->post['nettivarasto_status'];
		} else {
			$data['nettivarasto_status'] = $this->config->get('nettivarasto_status');
		}
		
		if (isset($this->request->post['nettivarasto_merchentid'])) {
			$data['nettivarasto_merchentid'] = $this->request->post['nettivarasto_merchentid'];
		} else {
			$data['nettivarasto_merchentid'] = $this->config->get('nettivarasto_merchentid');
		}		
		
		if (isset($this->request->post['nettivarasto_secret_token'])) {
			$data['nettivarasto_secret_token'] = $this->request->post['nettivarasto_secret_token'];
		} else {
			$data['nettivarasto_secret_token'] = $this->config->get('nettivarasto_secret_token');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$data['product_export_url'] = $this->url->link('module/nettivarasto/export_product', 'token=' . $this->session->data['token'], true);
		$data['latest_update_url'] = $this->url->link('module/nettivarasto/get_latest_changes', 'token=' . $this->session->data['token'], true);
		$this->response->setOutput($this->load->view('module/nettivarasto.tpl', $data));
	}
	
	public function export_product(){
		$this->load->language('module/nettivarasto');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('nettivarasto', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
		}
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_admin'] = $this->language->get('entry_admin');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_merchentid'] = $this->language->get('entry_merchentid');
		$data['entry_secret_token'] = $this->language->get('entry_secret_token');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		
		$this->load->model('extension/extension');

		$shipping_methods = $this->model_extension_extension->getInstalled('shipping');

		foreach($shipping_methods as $key=>$value){
			$strCodeKey	= "nettivarasto_code_".$value;
			$strStatusKey = "nettivarasto_status_".$value;

			$data['shipping_methods'][$key]['code'] = $value;
			if (isset($this->request->post[$strCodeKey])) {
				$data['shipping_methods'][$key][$strCodeKey] = $this->request->post[$strCodeKey];
			} else {
				$data['shipping_methods'][$key][$strCodeKey] = $this->config->get($strCodeKey);
			}
			if (isset($this->request->post[$strStatusKey])) {
				$data['shipping_methods'][$key][$strStatusKey] = $this->request->post[$strStatusKey];
			} else {
				$data['shipping_methods'][$key][$strStatusKey] = $this->config->get($strStatusKey);
			}
		}
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/nettivarasto', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('module/nettivarasto', 'token=' . $this->session->data['token'], true);
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);

		if (isset($this->request->post['nettivarasto_admin'])) {
			$data['nettivarasto_admin'] = $this->request->post['nettivarasto_admin'];
		} else {
			$data['nettivarasto_admin'] = $this->config->get('nettivarasto_admin');
		}

		if (isset($this->request->post['nettivarasto_status'])) {
			$data['nettivarasto_status'] = $this->request->post['nettivarasto_status'];
		} else {
			$data['nettivarasto_status'] = $this->config->get('nettivarasto_status');
		}
		
		if (isset($this->request->post['nettivarasto_merchentid'])) {
			$data['nettivarasto_merchentid'] = $this->request->post['nettivarasto_merchentid'];
		} else {
			$data['nettivarasto_merchentid'] = $this->config->get('nettivarasto_merchentid');
		}
		
		if (isset($this->request->post['nettivarasto_merchentid'])) {
			$data['nettivarasto_merchentid'] = $this->request->post['nettivarasto_merchentid'];
		} else {
			$data['nettivarasto_merchentid'] = $this->config->get('nettivarasto_merchentid');
		}
		
		if (isset($this->request->post['nettivarasto_secret_token'])) {
			$data['nettivarasto_secret_token'] = $this->request->post['nettivarasto_secret_token'];
		} else {
			$data['nettivarasto_secret_token'] = $this->config->get('nettivarasto_secret_token');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->load->model('catalog/product');
		$results = $this->model_catalog_product->getProducts();

		$this->load->model('tool/image');		

		// export for options
		$this->load->model('extension/nettivarasto');

		foreach($results as $products){
			// export for options
			foreach ($this->model_extension_nettivarasto->getProductOptionsWithValues($products['product_id']) as $option) {
				$product_option_value_data = array();				
				
				foreach ($option['product_option_value'] as $option_value) {
					if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
						if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
							//$price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax') ? 'P' : false));

							$pricePrefix = $option_value['price_prefix'];
							$price = $pricePrefix.$option_value['price'];
							$optionPrice = ($products['price']+$price);
						} else {
							$price = $products['price'];
						}

						if($option_value['image']){
							$pictureImage = $this->model_tool_image->resize($option_value['image'], '100','100');
						} else {
							$pictureImage = $this->model_tool_image->resize($products['image'],'100','100');
						}
						
						$sku = $products['sku'].'-'.$option_value['name'];
						$prodName = htmlspecialchars_decode($products['name']).'-'.$option_value['name'];

						$product_option_value_data = array(
							'Code'        => $sku,
							'Name'        => $prodName,							
							'Description' => $products['description'],
							'EANCode'     => $products['ean'],
							'InfoUrl'     => $this->url->link('product/product', 'product_id=' . $products['product_id']),
							'PictureUrl'  => $pictureImage,
							'SalesPrice'  => $optionPrice,
							'Currency'    => $this->config->get('config_currency'),
							'OptionName'  => $option['name'],
							'Prefix'      => $option_value['price_prefix']
						);
						$NV_productsOptions['Products']['Product'][] = $product_option_value_data;						
					}
				}				
			}
			
			$product_array = array(
				'Code' => $products['sku'],
				'Name' => htmlspecialchars_decode($products['name']),
				'Description' => $products['description'],
				'EANCode' => $products['ean'],
				'InfoUrl' => $this->url->link('product/product', 'product_id=' . $products['product_id']),
				'PictureUrl'=> $this->model_tool_image->resize($products['image'],'100','100'),
				'SalesPrice' => $products['price'],
				'Currency' => $this->config->get('config_currency')
			);
			$NV_products['Products']['Product'][] = $product_array;
		}
		//print '<pre>';
		//print_r($NV_products);
		//print_r($NV_productsOptions);
		//exit;
		
		$response = $this->api->updateAllProducts($NV_products);
		// export for options
		$responseNew = $this->api->updateAllProducts($NV_productsOptions);
		
		if ( $response ) {			
			if ( ! ( (string)$response['Response']['Info']['@Success'] == 'true' ) ) {
				$data['error_warning'] = $response['Response']['Info']['@Error'];
			} else {
				$data['success'] = 'Product export completed.';
			}
		}
		$data['product_export_url'] = $this->url->link('module/nettivarasto/export_product', 'token=' . $this->session->data['token'], true);	
		$data['latest_update_url'] = $this->url->link('module/nettivarasto/get_latest_changes', 'token=' . $this->session->data['token'], true);
		$this->response->setOutput($this->load->view('module/nettivarasto.tpl', $data));
	}
	
	function get_latest_changes() {
		$this->load->language('module/nettivarasto');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('nettivarasto', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
		}
		
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_admin'] = $this->language->get('entry_admin');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_merchentid'] = $this->language->get('entry_merchentid');
		$data['entry_secret_token'] = $this->language->get('entry_secret_token');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/nettivarasto', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('module/nettivarasto', 'token=' . $this->session->data['token'], true);
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);

		if (isset($this->request->post['nettivarasto_admin'])) {
			$data['nettivarasto_admin'] = $this->request->post['nettivarasto_admin'];
		} else {
			$data['nettivarasto_admin'] = $this->config->get('nettivarasto_admin');
		}

		if (isset($this->request->post['nettivarasto_status'])) {
			$data['nettivarasto_status'] = $this->request->post['nettivarasto_status'];
		} else {
			$data['nettivarasto_status'] = $this->config->get('nettivarasto_status');
		}
		
		if (isset($this->request->post['nettivarasto_merchentid'])) {
			$data['nettivarasto_merchentid'] = $this->request->post['nettivarasto_merchentid'];
		} else {
			$data['nettivarasto_merchentid'] = $this->config->get('nettivarasto_merchentid');
		}
		
		if (isset($this->request->post['nettivarasto_merchentid'])) {
			$data['nettivarasto_merchentid'] = $this->request->post['nettivarasto_merchentid'];
		} else {
			$data['nettivarasto_merchentid'] = $this->config->get('nettivarasto_merchentid');
		}
		
		if (isset($this->request->post['nettivarasto_secret_token'])) {
			$data['nettivarasto_secret_token'] = $this->request->post['nettivarasto_secret_token'];
		} else {
			$data['nettivarasto_secret_token'] = $this->config->get('nettivarasto_secret_token');
		}
		$this->load->model('extension/extension');
		$shipping_methods = $this->model_extension_extension->getInstalled('shipping');

		foreach($shipping_methods as $key=>$value){
			$strCodeKey	= "nettivarasto_code_".$value;
			$strStatusKey	= "nettivarasto_status_".$value;

			$data['shipping_methods'][$key]['code'] = $value;
			if (isset($this->request->post[$strCodeKey])) {
				$data['shipping_methods'][$key][$strCodeKey] = $this->request->post[$strCodeKey];
			} else {
				$data['shipping_methods'][$key][$strCodeKey] = $this->config->get($strCodeKey);
			}
			if (isset($this->request->post[$strStatusKey])) {
				$data['shipping_methods'][$key][$strStatusKey] = $this->request->post[$strStatusKey];
			} else {
				$data['shipping_methods'][$key][$strStatusKey] = $this->config->get($strStatusKey);
			}
		}
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$latest = $this->api->latestChanges($latestProducts, $latestOrders);
		$this->load->model('sale/order');
		$this->load->model('extension/nettivarasto');
		if($latestOrders) {
			foreach($latestOrders as $latestOrder) {
				$strOrderDetails = array();
				$strOrderDetails = $this->model_sale_order->getOrder($latestOrder->getReference());
				switch ( $latestOrder->getStatus() ) {	
					 case  'SHIPPED': 
						$this->model_extension_nettivarasto->addHistory($strOrderDetails['order_id'],'5','Nettivarasto change of status to SHIPPED. ');
						$this->model_extension_nettivarasto->addHistory($strOrderDetails['order_id'],'5','Tracking Number '.$latestOrder->getTrackingNumber());
						$this->model_extension_nettivarasto->updateOrderStatus($strOrderDetails['order_id'],'5');
                        break;
                    case  'CANCELLED':
						$this->model_extension_nettivarasto->addHistory($strOrderDetails['order_id'],'7','Nettivarasto change of status to CANCELLED. ');
                        break;
                    case  'COLLECTING':
						$this->model_extension_nettivarasto->addHistory($strOrderDetails['order_id'],'2','Nettivarasto change of status to COLLECTING. ');
                        break;
                    case  'PENDING':
						$this->model_extension_nettivarasto->addHistory($strOrderDetails['order_id'],'1','Nettivarasto change of status to PENDING.');
                        break;
                    case  'RESERVED':
						$this->model_extension_nettivarasto->addHistory($strOrderDetails['order_id'],'12','Nettivarasto change of status to RESERVED.');
                        break;
				}
			}
		}
		
		/*print '<pre>';
		print_r($latestProducts);*/
		if($latestProducts) {
			 foreach($latestProducts as $latestProduct) {
				$strProductDetails = $this->model_extension_nettivarasto->getProductBySKU($latestProduct->getCode());	
				if($strProductDetails){
						$this->model_extension_nettivarasto->updateProductQuantity($strProductDetails['product_id'],$latestProduct->getStock());	
					if ( $latestProduct->getStock() ) {
						$this->model_extension_nettivarasto->updateProductStockStatus($strProductDetails['product_id'],'7');	
					}
				}
			 }
		}
		$data['success']="Product and order data updated from Nettivarasto.";	
		$data['product_export_url'] = $this->url->link('module/nettivarasto/export_product', 'token=' . $this->session->data['token'], true);
		$data['latest_update_url'] = $this->url->link('module/nettivarasto/get_latest_changes', 'token=' . $this->session->data['token'], true);
		$this->response->setOutput($this->load->view('module/nettivarasto.tpl', $data));
	}
	
	function save_order_to_nettivarasto($order_id = '') {
		if(isset($this->session->data['order_id'])){
			$order_id = $this->session->data['order_id'];
		} elseif(isset($this->request->get['order_id'])){
			$order_id = $this->request->get['order_id'];
		}
		$this->load->model('sale/order');
		$this->load->model('extension/nettivarasto');
		$order = new NettivarastoAPI_Order($this->api,$order_id);
		$strOrderDetails = $this->model_sale_order->getOrder($order_id);

		$strShippingCode = trim(strstr($strOrderDetails['shipping_code'],"."),".");

		if($this->config->get("nettivarasto_status_".$strShippingCode)==1){
			$nettivarasto_shipping_method=$this->config->get("nettivarasto_code_".$strShippingCode);
			$products =	$this->model_sale_order->getOrderProducts($order_id);
			$index = 0;
			foreach($products as $item) {				
				//order for options			
				$sku = $this->model_extension_nettivarasto->getSKUByProductId($item['product_id']);

				//$order_product_id = $this->model_extension_nettivarasto->getOrderProductIdByOrderId($strOrderDetails['order_id']);
				$order_product_id = $item['order_product_id'];
				$orderOptions = $this->model_extension_nettivarasto->getOrderOptionsByOrderId($strOrderDetails['order_id'], $order_product_id);
				
				if(isset($orderOptions) && isset($orderOptions[0]) && isset($orderOptions[0]['value'])) {
					$optionSku = $sku.'-'.$orderOptions[0]['value'];

					$order->setOrderLineCode( $index, $optionSku);
				} else {
					$order->setOrderLineCode( $index, $sku );
				}			
				$order->setOrderLineQuantity( $index, ($item['quantity']));
				$order->setOrderLinePrice( $index, $item['price']);
				$index++;
			}
			  
			$order->setPriceTotal($strOrderDetails['total']);
			$order->setCustomerName($strOrderDetails['shipping_firstname'].' '.$strOrderDetails['shipping_lastname']);
			$order->setCustomerAddress1($strOrderDetails['shipping_address_1']);
			$order->setCustomerAddress2($strOrderDetails['shipping_address_2']);
			$order->setCustomerCity($strOrderDetails['shipping_city']);
			$order->setCustomerCountry($strOrderDetails['shipping_country']);
			$order->setCustomerEmail($strOrderDetails['email']);
			$order->setCustomerPhone($strOrderDetails['telephone']);
			$order->setCustomerZip($strOrderDetails['shipping_postcode']);
			$order->setShipping($nettivarasto_shipping_method);

			if ( $order->save() ) {
				$data['success'] = 'Order successfully transferred to Nettivarasto.';
			} else {
				$data['error_warning'] = 'Error - Nettivarasto API'. $this->api->getLastError();
			}   	
		} else {
			$data['error_warning'] = "Order Shipping method not enabled in settings";
		}
		return $data;
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/nettivarasto')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}