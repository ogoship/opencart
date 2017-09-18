<?php
class ModelExtensionNettivarasto extends Model {
	public function addHistory($order_id, $order_status_id, $comment) {
		$his_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_history` WHERE order_id = '" . (int)$order_id . "' AND order_status_id = '" . (int)$order_status_id . "'");
		if ($his_query->num_rows==0) {	
		$this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$order_status_id . "', notify = '1', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");
		}
	}
	public function updateOrderStatus($order_id, $order_status_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "order SET order_status_id = '" . (int)$order_status_id . "' WHERE order_id='" . (int)$order_id . "'");
	}
	public function getProductBySKU($sku) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE sku='".$sku."'");
		return $query->row;
	}
	public function getSKUByProductId($sku) {
		$query = $this->db->query("SELECT sku FROM " . DB_PREFIX . "product WHERE product_id='".$sku."'");
		return $query->row['sku'];
	}
	public function updateProductQuantity($product_id, $quantity) {
		$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = '" . (int)$quantity . "' WHERE product_id='" . (int)$product_id . "'");
	}
	public function updateProductStockStatus($product_id, $stock) {
		$this->db->query("UPDATE " . DB_PREFIX . "product SET stock_status_id = '" . (int)$stock . "' WHERE product_id='" . (int)$product_id . "'");
	}
}
