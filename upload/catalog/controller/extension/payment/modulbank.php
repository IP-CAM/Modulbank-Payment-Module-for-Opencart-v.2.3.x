<?php

/**
 *
 *  Modulbank OpenCart payment plugin v1.0
 *
 */ 

define('MODULBANK_VERSION', '1.0');

include('fpayments.php');


class ControllerExtensionPaymentModulbank extends Controller {

    public function index() {

        $this->id = 'payment';

        try {
            $form = $this->get_form();
        } catch (FPaymentsError $e) {
            return "Ошибка: " . $e->getMessage();
        }

        $data = array(
            'button_confirm'  => $this->language->get('button_confirm'),
            'button_back'     => $this->language->get('button_back'),
            'modulbank_url'    => $this->get_form_object()->get_url(),
            'modulbank_fields' => FPaymentsForm::array_to_hidden_fields($form),
        );
        return $this->load->view('extension/payment/modulbank', $data);
    }

    public function callback() {
        $ff = $this->get_form_object();
        if (!$this->request->post) {
            echo "ERROR: empty request\n";
        } else if (!$ff->is_signature_correct($this->request->post)) {
            echo "ERROR: wrong signature\n";
        } else {
            $this->load->model('checkout/order');
            $order_id = $this->request->post['order_id'];

            if ($ff->is_order_completed($this->request->post)) {
                $order_info = $this->model_checkout_order->getOrder($order_id);
                $new_order_status_id = $this->config->get('modulbank_order_status_id');
                $current_order_status_id = $order_info['order_status_id'];

                if ($current_order_status_id != $new_order_status_id) {
                    $this->model_checkout_order->addOrderHistory(
                        $order_id,
                        $new_order_status_id,
                        "Оплата через Модульбанк",
                        TRUE
                    );
                }
            }

            echo "OK $order_id\n";
        }
    }

    private function get_form_object() { 
        $version = defined('VERSION')?VERSION:'Unknown';
        $plugin_version = defined('MODULBANK_VERSION')?MODULBANK_VERSION:'Unknown';
        $cms_info = 'OpenCart v. ' . $version;
        return new FPaymentsForm(
            $this->config->get('modulbank_merchant_id'),
            $this->config->get('modulbank_secret_key'),
            $this->config->get('modulbank_mode') == 'test',
            $plugin_version,
            $cms_info
        );
    }

    private function guessTaxRate($tax) {
        if ($tax == 10 || $tax == 18 || $tax == 20) {
            return "vat_" . intval($tax);
        } else {
            return 'no_vat';
        }
    }

    /**
     * @return array
     * @throws FPaymentsError
     */
    private function get_form() {
        $this->load->model('checkout/order');
        $this->load->model('extension/payment/modulbank');

        $order_id = $this->session->data['order_id'];

        $order_info = $this->model_checkout_order->getOrder($order_id);

        $amount = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);

        $products = $this->model_extension_payment_modulbank->getOrderProducts($order_id);
        $receipt_items = array();
        foreach( $products as $productOrd ) {
            $receipt_items[] = new FPaymentsRecieptItem(
                $productOrd['name'],
                $productOrd['price'],
                $productOrd['quantity'],
                0,
                $this->guessTaxRate($productOrd['tax'])
            );
        }
        $shipping = $this->model_extension_payment_modulbank->getOrderShipping($order_id);
        if ($shipping['cost']) {
            $receipt_items[] = new FPaymentsRecieptItem(
                'Доставка',
                $shipping['cost'],
                1,
                0,
                $this->config->get('modulbank_delivery_vat_rate')
            );
        }		
        $receipt_contact = $order_info['email'] ?: $order_info['telephone'] ?: '';

        return $this->get_form_object()->compose(
            $amount,
            $order_info['currency_code'],
            $order_id,
            $order_info['email'],
            $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'],
            $order_info['telephone'],
            HTTPS_SERVER . 'index.php?route=checkout/success',
            HTTPS_SERVER . 'index.php?route=checkout/failure',
            $this->get_back_url(),
            HTTPS_SERVER . 'index.php?route=extension/payment/modulbank/callback',
            '',
            '',
            $receipt_contact,
            $receipt_items
        );
    }

    private function get_back_url() {
        if ($this->request->get['route'] != 'checkout/guest_step_3') {
            return HTTPS_SERVER . 'index.php?route=checkout/checkout';
        } else {
            return HTTPS_SERVER . 'index.php?route=checkout/guest_step_2';
        }
    }
}
