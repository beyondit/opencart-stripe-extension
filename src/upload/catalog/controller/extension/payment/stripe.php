<?php

class ControllerExtensionPaymentStripe extends Controller {

	/**
	 *
	 * get private or public key in test/live mode
	 *
	 * @param string $type
	 *
	 */
	protected function getKey($type = 'private') {
		return $this->config->get('stripe_' . strtolower($this->config->get('stripe_mode')) . '_' . $type . '_key');
	}

	public function index() {
		$this->load->language('extension/payment/stripe');

		$data['mode'] = $this->config->get('stripe_mode');
		$data['stripe_public_key'] = $this->getKey('public');

		$data['button_confirm'] = $this->language->get('button_confirm');

		$data['text_instruction'] = $this->language->get('text_instruction');
		$data['text_description'] = $this->language->get('text_description');

		$data['entry_cc_owner'] = $this->language->get('entry_cc_owner');
		$data['entry_cc_number'] = $this->language->get('entry_cc_number');
		$data['entry_cc_expire_date'] = $this->language->get('entry_cc_expire_date');
		$data['entry_cc_cvc'] = $this->language->get('entry_cc_cvc');

		$data['error_invalid_number'] = $this->language->get('error_invalid_number');
		$data['error_incorrect_number'] = $this->language->get('error_incorrect_number');
		$data['error_invalid_expiry_month'] = $this->language->get('error_invalid_expiry_month');
		$data['error_invalid_expiry_year'] = $this->language->get('error_invalid_expiry_year');
		$data['error_invalid_cvc'] = $this->language->get('error_invalid_cvc');
		$data['error_incorrect_cvc'] = $this->language->get('error_incorrect_cvc');
		$data['error_expired_card'] = $this->language->get('error_expired_card');
		$data['error_invalid_swipe_data'] = $this->language->get('error_invalid_swipe_data');
		$data['error_incorrect_zip'] = $this->language->get('error_incorrect_zip');
		$data['error_card_declined'] = $this->language->get('error_card_declined');
		$data['error_missing'] = $this->language->get('error_missing');
		$data['error_processing_error'] = $this->language->get('error_processing_error');
		$data['error_general'] = $this->language->get('error_general');

		$data['months'] = ['01','02','03','04','05','06','07','08','09','10','11','12'];
		$year_start = date('Y');
		$year_end = $year_start + 30;
		for ($i=$year_start;$i<=$year_end;$i++) {
			$data['years'][] = $i;
		}

		$data['stripe_payment_url'] = $this->url->link('extension/payment/stripe/process', '', true);

        return $this->load->view('extension/payment/stripe', $data);
	}

	public function process() {
		try {
			$success = false;
			$this->load->model('checkout/order');
			$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
			$private_key = $this->getKey('private');

			$this->log->write('Stripe Source from browser stripe_source: ' . $this->request->post['stripe_source']);

			if (isset($this->request->post['stripe_source']) && $order_info) {
				\Stripe\Stripe::setApiKey($private_key);

				$sourceToken = $this->request->post['stripe_source'];
				$threeDSecure = $this->request->post['stripe_three_d_secure'];

				if ($threeDSecure != 'not_supported') {
					$source = \Stripe\Source::create(array(
						"amount" => (round(($order_info['total'] * ($order_info['currency_value']/1)), 2)*100),
						"currency" => $order_info['currency_code'],
						"type" => "three_d_secure",
						"three_d_secure" => array(
							"card" => $sourceToken,
						),
						"metadata" => ['order_id' => $order_info['order_id']] ,
						"redirect" => array(
							"return_url" => $this->url->link('extension/payment/stripe/redirect', '', true)
						),
					));
				}

				if (!isset($source) || $source->status == 'chargeable') {
					\Stripe\Charge::create(array(
						"amount" => (round(($order_info['total'] * ($order_info['currency_value']/1)), 2)*100),
						"currency" => $order_info['currency_code'],
						"source" => $sourceToken,
						"description" => "OpenCart Order with ID: #".$order_info['order_id'],
						"metadata" => ['order_id' => $order_info['order_id']]
					));
					$success = true;
				}				
			}
		} catch(\Exception $e) {
			$this->log->write('Stripe error log: ' . $e->getMessage());
			$success = false;
		}

		if ($success) {
			$this->response->redirect($this->url->link('checkout/success', '', true));
		} elseif ($source->status == 'pending') {
			$this->response->redirect($source->redirect->url);
		} else {
			$this->response->redirect($this->url->link('checkout/failure', '', true));
		}
	}

	public function redirect() {
		$success = false;

		if ($this->request->get && isset($this->request->get['source'])) {
			$this->load->model('checkout/order');
			\Stripe\Stripe::setApiKey($this->getKey('private'));
			
			try {
				$source = \Stripe\Source::retrieve($this->request->get['source']);
				$order_info = $this->model_checkout_order->getOrder($source->metadata->order_id);

				\Stripe\Charge::create(array(
					"amount" => (round(($order_info['total'] * ($order_info['currency_value']/1)), 2)*100),
					"currency" => $order_info['currency_code'],
					"source" => $this->request->get['source'],
					"description" => "OpenCart Order with ID: #".$order_info['order_id'],
					"metadata" => ['order_id' => $order_info['order_id']]
				));

				$success = true;
			} catch (\Exception $e) {
				$this->log->write('Stripe error after 3D-Secure redirect: ' . $e->getMessage());
			}
		}

		if ($success) {
			$this->response->redirect($this->url->link('checkout/success', '', true));
		} else {
			$this->response->redirect($this->url->link('checkout/failure', '', true));
		}
	}

	public function event() {
		try {
			$this->load->model('checkout/order');
			$private_key = $this->getKey('private');

			$input = @file_get_contents("php://input");
			$event_json = json_decode($input);

			\Stripe\Stripe::setApiKey($private_key);
			$event = \Stripe\Event::retrieve($event_json->id);

			if (isset($event->data->object) && isset($event->data->object->metadata) && isset($event->data->object->metadata->order_id)) {
				$order_id   = $event->data->object->metadata->order_id;
				$order_info = $this->model_checkout_order->getOrder($order_id);

				if ($order_info) {
					switch ($event->type) {
						case 'charge.succeeded':
						case 'charge.captured':
						case 'charge.dispute.funds_reinstated':
							$order_status_id = $this->config->get('stripe_completed_status_id');
							break;
						case 'source.canceled':
						case 'source.failed':
						case 'charge.failed':
						case 'charge.dispute.funds_withdrawn':
							$order_status_id = $this->config->get('stripe_voided_status_id');
							break;
						case 'charge.refunded':
							$order_status_id = $this->config->get('stripe_refunded_status_id');
							break;
						case 'charge.updated':
						case 'charge.dispute.created':
						case 'charge.dispute.updated':
						case 'charge.pending':
							$order_status_id = $this->config->get('stripe_pending_status_id');
							break;
					}

					if (isset($order_status_id)) {
						$this->log->write("Stripe: Order ID is updated with new state id: " . $order_status_id . " / event type: " . $event->type);
						$this->model_checkout_order->addOrderHistory($order_id, $order_status_id);
					}
				}
			}
		} catch (\Exception $e) {
			$this->log->write("Error while processing Stripe Event: " . $e->getMessage());
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode(['message' => 'handled successfully']));
	}
}