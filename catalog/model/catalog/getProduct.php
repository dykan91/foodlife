<?php
class ModelCataloggetProduct extends Model {
	public function getCatProduct($cat_id=null) {
$sql = '';
if(isset($cat_id)){
    $sql = "AND oc_product_to_category.category_id = {$cat_id}";
}
        $query = $this->db->query("
SELECT oc_product.*, oc_product_description.*, oc_category.category_id, oc_category_description.name as category_name FROM oc_product
 INNER JOIN oc_product_to_category ON oc_product.product_id = oc_product_to_category.product_id 
 INNER JOIN oc_product_description ON oc_product.product_id = oc_product_description.product_id 
 INNER JOIN oc_category ON oc_category.category_id = oc_product_to_category.category_id 
 INNER JOIN oc_category_description ON oc_category.category_id = oc_category_description.category_id 
 WHERE oc_category.status = 1 $sql ORDER BY oc_category.sort_order ASC  ");
        $ret = [];

        foreach($query->rows as $prod) {
            $ret[$prod['category_name']][] = $prod;
        }

        $res = [];

        foreach($ret as $cat => $prods) {
            $res[] = [
                'name' => $cat,
                'product' => $prods
            ];
        }

        return $res;

//        $this->db->query("UPDATE " . DB_PREFIX . "product SET viewed = (viewed + 1) WHERE product_id = '" . (int)$product_id . "'");
//		$this->db->query("UPDATE " . DB_PREFIX . "product SET viewed = (viewed + 1) WHERE product_id = '" . (int)$product_id . "'");
	}

//    public function getCategory($cat_id=null) {

}
