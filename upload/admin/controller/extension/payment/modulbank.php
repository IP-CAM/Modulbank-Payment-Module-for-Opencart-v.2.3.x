<?php

class ControllerExtensionPaymentModulbank extends Controller {

    private $error = array();

    private $vat_rates = [
        'no_vat' => 'Без НДС',
        'vat_0' => '0%',
        'vat_10' => '10%',
        'vat_18' => '18%',
        'vat_110' => '10/110',
        'vat_118' => '18/118',
    ];

    public function index() {
        $this->load->language('extension/payment/modulbank');
        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');
        $this->load->model('localisation/order_status');
        $this->load->model('localisation/geo_zone');

        if (
            ($this->request->server['REQUEST_METHOD'] == 'POST') &&
            $this->validate()
        ) {
            $this->model_setting_setting->editSetting('modulbank', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true));
        }

        $data = array(
            # == Form == 

            # merchant_id
            'entry_merchant_id'     => $this->language->get('entry_merchant_id'),           
            'error_merchant_id'     => isset($this->error['merchant_id']) ? $this->error['merchant_id'] : '',

            # secret_key
            'entry_secret_key'      => $this->language->get('entry_secret_key'),
            'error_secret_key'      => isset($this->error['secret_key']) ? $this->error['secret_key'] : '',
            'error_warning'         => isset($this->error['warning']) ? $this->error['warning'] : '',

            # urls
            'entry_callback_url'    => $this->language->get('entry_callback_url'),
            'callback_url'          => HTTP_CATALOG . 'index.php?route=extension/payment/modulbank/callback',

            # mode
            'entry_mode'            => $this->language->get('entry_mode'),
            'entry_mode_test'       => $this->language->get('entry_mode_test'),
            'entry_mode_real'       => $this->language->get('entry_mode_real'),

            # order status
            'entry_order_status'    => $this->language->get('entry_order_status'),
            'order_statuses'        => $this->model_localisation_order_status->getOrderStatuses(),

            # geo zone
            'entry_geo_zone'        => $this->language->get('entry_geo_zone'),
            'text_all_zones'        => $this->language->get('text_all_zones'),
            'geo_zones'             => $this->model_localisation_geo_zone->getGeoZones(),

            # status
            'entry_status'          => $this->language->get('entry_status'),
            'text_enabled'          => $this->language->get('text_enabled'),
            'text_disabled'         => $this->language->get('text_disabled'),

            # delivery VAT rate
            'entry_delivery_vat_rate' => $this->language->get('entry_delivery_vat_rate'),
            'delivery_vat_rates'    => $this->vat_rates,

            # sort order
            'entry_sort_order'      => $this->language->get('entry_sort_order'),

            # == Etc ==
            'heading_title'         => $this->language->get('heading_title'),

            'button_save'           => $this->language->get('button_save'),
            'button_cancel'         => $this->language->get('button_cancel'),
            'modulbank_downloads'    => $this->language->get('modulbank_downloads'),

            'action'                => $this->url->link('extension/payment/modulbank', 'token=' . $this->session->data['token'], 'SSL'),
            'cancel'                => $this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL'),

            'breadcrumbs'           => array(
                array(
                    'text'      => $this->language->get('text_home'),
                    'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
                    'separator' => false,
                ),
                array(
                    'text'      => $this->language->get('text_payment'),
                    'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
                    'separator' => ' :: ',
                ),
                array(
                    'text'      => $this->language->get('heading_title'),
                    'href'      => $this->url->link('payment/modulbank', 'token=' . $this->session->data['token'], 'SSL'),
                    'separator' => ' :: ',
                ),
            ),
        );


        $data['modulbank_merchant_id'] = $this->initial('modulbank_merchant_id');
        $data['modulbank_secret_key'] =  $this->initial('modulbank_secret_key');
        $data['modulbank_mode'] = $this->initial('modulbank_mode', 'test');
        $data['modulbank_order_status_id'] = $this->initial('modulbank_order_status_id', 5); // COMPLETE by default
        $data['modulbank_geo_zone_id'] = $this->initial('modulbank_geo_zone_id', 0);
        $data['modulbank_status'] = $this->initial('modulbank_status', 1);
        $data['modulbank_sort_order'] = $this->initial('modulbank_sort_order', 1);
        $data['modulbank_delivery_vat_rate'] = $this->initial('modulbank_delivery_vat_rate', 'vat_18');


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/payment/modulbank', $data));
    }

    protected function initial($k, $default=null) {
        if (isset($this->request->post[$k])) {
            $v = $this->request->post[$k];
        } else {
            $v = $this->config->get($k);
        }
        return $v ? $v : $default;
    }

    private function validate() {
        if (!$this->user->hasPermission('modify', 'extension/payment/modulbank')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        if (!$this->request->post['modulbank_secret_key']) {
            $this->error['secret_key'] = $this->language->get('error_secret_key');
        }
        if (!$this->request->post['modulbank_merchant_id']) {
            $this->error['merchant_id'] = $this->language->get('error_merchant_id');
        }

        $delivery_vat_rate = $this->request->post['modulbank_delivery_vat_rate'];
        if (! array_key_exists($delivery_vat_rate, $this->vat_rates)) {
            error_log("Incorrect VAT value provided: $delivery_vat_rate");
            return false;
        }
        return $this->error ? false : true;
    }
}
