<?php
class ModelExtensionPaymentModulbank extends Model {
    public function getMethod($address) {
        $this->load->language('extension/payment/modulbank');

        if ($this->config->get('modulbank_status')) {
            $config_zone_id = (int)$this->config->get('modulbank_geo_zone_id');
            $address_zone_id = (int)$address['zone_id'];
            $country_id = (int)$address['country_id'];
            $query = $this->db->query(
                "SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone " .
                "WHERE geo_zone_id = '$config_zone_id' " .
                "AND country_id = '$country_id' " .
                "AND (zone_id = '$address_zone_id' OR zone_id = '0')"
            );
            if (!$config_zone_id) {
                $status = TRUE;
            } elseif ($query->num_rows) {
                $status = TRUE;
            } else {
                $status = FALSE;
            }
        } else {
            $status = FALSE;
        }

        $method_data = array();

        if ($status) {
            $method_data = array(
                'code'       => 'modulbank',
                'title'      => $this->language->get('text_title'),
				'terms'      => '',
                'sort_order' => $this->config->get('modulbank_sort_order')
            );
        }
        return $method_data;
    }
	
	public function getOrderProducts($order_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_product` WHERE  order_id = '" . (int)$order_id . "'");
		return $query->rows;
		
	}
	public function getOrderShipping($order_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE code = 'shipping' and order_id = '" . (int)$order_id . "'");
		if ($query->num_rows) {
			return array(
			'cost'          => $query->row['value']
			);
		} else {
			return false;
		}
	}
}
