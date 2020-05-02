<?php
class ModelExtensionModuleOptionsCategory extends Model {
	public function getOptions() {
		$sql = "SELECT * FROM `" . DB_PREFIX . "option` o LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE od.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY od.name ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function createColumns() {
		// Product Option Show
		$query = $this->db->query("SHOW COLUMNS FROM " . DB_PREFIX . "product_option");
		$option_status = false;

		if ($query->rows) {
			foreach ($query->rows as $row) {
				if ($row['Field'] == 'option_status') {
					$option_status = true;
				}
			}
			
			if (!$option_status) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_option`  ADD `option_status` tinyint(1) NOT NULL DEFAULT '2';");
			}
		}
		
		// Option Value SKU
		$query = $this->db->query("SHOW COLUMNS FROM " . DB_PREFIX . "product_option_value");
		$sku = false;

		if ($query->rows) {
			foreach ($query->rows as $row) {
				if ($row['Field'] == 'sku') {
					$sku = true;
				}
			}
			
			if (!$sku) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_option_value`  ADD `sku` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;");
			}
		}
		
		// Option Value Show
		$query = $this->db->query("SHOW COLUMNS FROM " . DB_PREFIX . "product_option_value");
		$option_status = false;

		if ($query->rows) {
			foreach ($query->rows as $row) {
				if ($row['Field'] == 'option_status') {
					$option_status = true;
				}
			}
			
			if (!$option_status) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_option_value`  ADD `option_status` tinyint(1) NOT NULL DEFAULT '1';");
			}
		}
	}
}