<?php

class ControllerExtensionPaymentStripe extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/stripe');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('stripe', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'], true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');

		$data['entry_stripe_live_public_key'] = $this->language->get('entry_stripe_live_public_key');
		$data['entry_stripe_live_private_key'] = $this->language->get('entry_stripe_live_private_key');
		$data['entry_stripe_test_public_key'] = $this->language->get('entry_stripe_test_public_key');
		$data['entry_stripe_test_private_key'] = $this->language->get('entry_stripe_test_private_key');
		$data['entry_mode'] = $this->language->get('entry_mode');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_completed_status'] = $this->language->get('entry_completed_status');
		$data['entry_pending_status'] = $this->language->get('entry_pending_status');
		$data['entry_refunded_status'] = $this->language->get('entry_refunded_status');
		$data['entry_voided_status'] = $this->language->get('entry_voided_status');
        $data['entry_processed_status'] = $this->language->get('entry_processed_status');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['help_total'] = $this->language->get('help_total');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_order_status'] = $this->language->get('tab_order_status');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['config_keys'])) {
			$data['error_config_keys'] = $this->error['config_keys'];
		} else {
			$data['error_config_keys'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_payment'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/stripe', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/stripe', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'], true);

		if (isset($this->request->post['stripe_live_public_key'])) {
			$data['stripe_live_public_key'] = $this->request->post['stripe_live_public_key'];
		} else {
			$data['stripe_live_public_key'] = $this->config->get('stripe_live_public_key');
		}

		if (isset($this->request->post['stripe_live_private_key'])) {
			$data['stripe_live_private_key'] = $this->request->post['stripe_live_private_key'];
		} else {
			$data['stripe_live_private_key'] = $this->config->get('stripe_live_private_key');
		}

		if (isset($this->request->post['stripe_test_public_key'])) {
			$data['stripe_test_public_key'] = $this->request->post['stripe_test_public_key'];
		} else {
			$data['stripe_test_public_key'] = $this->config->get('stripe_test_public_key');
		}

		if (isset($this->request->post['stripe_test_private_key'])) {
			$data['stripe_test_private_key'] = $this->request->post['stripe_test_private_key'];
		} else {
			$data['stripe_test_private_key'] = $this->config->get('stripe_test_private_key');
		}

		if (isset($this->request->post['stripe_mode'])) {
			$data['stripe_mode'] = $this->request->post['stripe_mode'];
		} else {
			$data['stripe_mode'] = $this->config->get('stripe_mode');
		}

		if (isset($this->request->post['stripe_total'])) {
			$data['stripe_total'] = $this->request->post['stripe_total'];
		} else {
			$data['stripe_total'] = $this->config->get('stripe_total');
		}

		if (isset($this->request->post['stripe_completed_status_id'])) {
			$data['stripe_completed_status_id'] = $this->request->post['stripe_completed_status_id'];
		} else {
			$data['stripe_completed_status_id'] = $this->config->get('stripe_completed_status_id');
		}

		if (isset($this->request->post['stripe_pending_status_id'])) {
			$data['stripe_pending_status_id'] = $this->request->post['stripe_pending_status_id'];
		} else {
			$data['stripe_pending_status_id'] = $this->config->get('stripe_pending_status_id');
		}

		if (isset($this->request->post['stripe_refunded_status_id'])) {
			$data['stripe_refunded_status_id'] = $this->request->post['stripe_refunded_status_id'];
		} else {
			$data['stripe_refunded_status_id'] = $this->config->get('stripe_refunded_status_id');
		}

		if (isset($this->request->post['stripe_voided_status_id'])) {
			$data['stripe_voided_status_id'] = $this->request->post['stripe_voided_status_id'];
		} else {
			$data['stripe_voided_status_id'] = $this->config->get('stripe_voided_status_id');
		}

		if (isset($this->request->post['stripe_processed_status_id'])) {
			$data['stripe_processed_status_id'] = $this->request->post['stripe_processed_status_id'];
		} else {
			$data['stripe_processed_status_id'] = $this->config->get('stripe_processed_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['stripe_geo_zone_id'])) {
			$data['stripe_geo_zone_id'] = $this->request->post['stripe_geo_zone_id'];
		} else {
			$data['stripe_geo_zone_id'] = $this->config->get('stripe_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['stripe_status'])) {
			$data['stripe_status'] = $this->request->post['stripe_status'];
		} else {
			$data['stripe_status'] = $this->config->get('stripe_status');
		}

		if (isset($this->request->post['stripe_sort_order'])) {
			$data['stripe_sort_order'] = $this->request->post['stripe_sort_order'];
		} else {
			$data['stripe_sort_order'] = $this->config->get('stripe_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/stripe', $data));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/stripe')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ($this->request->post['stripe_mode'] == 'LIVE') {
			if (!$this->request->post['stripe_live_public_key'] || (0 == preg_match('/^pk_live_[0-9a-zA-Z]{24}$/',$this->request->post['stripe_live_public_key'])) ) {
				$this->error['config_keys'] = $this->language->get('error_config_keys');
			}
			if (!$this->request->post['stripe_live_private_key'] || (0 == preg_match('/^sk_live_[0-9a-zA-Z]{24}$/',$this->request->post['stripe_live_private_key'])) ) {
				$this->error['config_keys'] = $this->language->get('error_config_keys');
			}
		} else {
			if (!$this->request->post['stripe_test_public_key'] || (0 == preg_match('/^pk_test_[0-9a-zA-Z]{24}$/',$this->request->post['stripe_test_public_key'])) ) {
				$this->error['config_keys'] = $this->language->get('error_config_keys');
			}
			if (!$this->request->post['stripe_test_private_key'] || (0 == preg_match('/^sk_test_[0-9a-zA-Z]{24}$/',$this->request->post['stripe_test_private_key'])) ) {
				$this->error['config_keys'] = $this->language->get('error_config_keys');
			}
		}

		return !$this->error;
	}
}