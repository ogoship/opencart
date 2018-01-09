<?php
class ModelExtensionNettivarasto extends Model {
	public function addHistory($order_id, $order_status_id, $comment) {
		$his_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_history` WHERE order_id = '" . (int)$order_id . "' AND order_status_id = '" . (int)$order_status_id . "'");
		if ($his_query->num_rows==0) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$order_status_id . "', notify = '0', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");
		}
	}
	
	public function updateOrderStatus($order_id, $order_status_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "order SET order_status_id = '" . (int)$order_status_id . "' WHERE order_id='" . (int)$order_id . "'");
	}
	
	public function getProductBySKU($sku) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE sku='" . $this->db->escape($sku) . "'");
		return $query->row;
	}
	
	public function getSKUByProductId($product_id) {
		$query = $this->db->query("SELECT sku FROM " . DB_PREFIX . "product WHERE product_id='" . (int)$product_id . "'");
		return $query->row['sku'];
	}
	
	public function updateProductQuantity($product_id, $quantity) {
		$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = '" . (int)$quantity . "' WHERE product_id='" . (int)$product_id . "'");
	}
	
	public function updateProductStockStatus($product_id, $stock) {
		$this->db->query("UPDATE " . DB_PREFIX . "product SET stock_status_id = '" . (int)$stock . "' WHERE product_id='" . (int)$product_id . "'");
	}
	
	// get product options with values
	public function getProductOptionsWithValues($product_id) {
		
		$product_option_data = array();
		
		$sql = "SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.sort_order";
		$product_option_query = $this->db->query($sql);

		foreach ($product_option_query->rows as $product_option) {
			$product_option_value_data = array();
			
			$optionSql = "SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '" . (int)$product_id . "' AND pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ov.sort_order";
			$product_option_value_query = $this->db->query($optionSql);

			foreach ($product_option_value_query->rows as $product_option_value) {
				$product_option_value_data[] = array(
					'product_option_value_id' => $product_option_value['product_option_value_id'],
					'option_value_id'         => $product_option_value['option_value_id'],
					'name'                    => $product_option_value['name'],
					'image'                   => $product_option_value['image'],
					'quantity'                => $product_option_value['quantity'],
					'subtract'                => $product_option_value['subtract'],
					'price'                   => $product_option_value['price'],
					'price_prefix'            => $product_option_value['price_prefix'],
					'weight'                  => $product_option_value['weight'],
					'weight_prefix'           => $product_option_value['weight_prefix']
				);
			}

			$product_option_data[] = array(
				'product_option_id'    => $product_option['product_option_id'],
				'product_option_value' => $product_option_value_data,
				'option_id'            => $product_option['option_id'],
				'name'                 => $product_option['name'],
				'type'                 => $product_option['type'],
				'value'                => $product_option['value'],
				'required'             => $product_option['required']
			);
		}

		return $product_option_data;
	}
	
	public function getOrderProductIdByOrderId($order_id) {
		$sql = "SELECT order_product_id FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'";
		$query = $this->db->query($sql);

		return $query->rows;
		
	}
	
	public function getOrderOptionsByOrderId($order_id, $order_product_id) {
		$sql = "SELECT value FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "'";
		$query = $this->db->query($sql);

		return $query->rows;
	}
}