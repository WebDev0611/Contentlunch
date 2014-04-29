<?php namespace Launch;

use Illuminate\Support\Facades\Config;

class Balanced {

  protected static $initialized = false;

  protected $account = null;

  protected $balanced_info = array();
  protected $payment_info = array();

  /**
   * Create a new balanced object with account model
   * @param object $account Account model
   */
  public function __construct($account)
  {
    static::init();
    $this->account = $account;
    $this->balanced_info = unserialize($this->account->balanced_info);
    $this->payment_info = unserialize($this->account->payment_info);
  }

  /**
   * Initialize balanced libraries
   */
  protected static function init()
  {
    if ( ! static::$initialized) {
      \Httpful\Bootstrap::init();
      \RESTful\Bootstrap::init();
      \Balanced\Bootstrap::init();
      static::$initialized = true;
      \Balanced\Settings::$api_key = Config::get('app.balanced.api_key_secret');
    }
  }

  /**
   * Get the balanced customer record
   * @return object Balanced\Customer
   */
  public function getCustomer()
  {
    if ( ! isset($this->balanced_info['customer_uri'])) {
      return null;
    }
    return \Balanced\Customer::get($this->balanced_info['customer_uri']);
  }

  /**
   * Get the balanced payment record
   * @return object Balanced\Card
   */
  public function getPayment()
  {
    // Get info on the stored payment type and uri (payment_type / token)
    // Check for saved payment uri
    if ($this->account->token) {
      if ($this->account->payment_type == 'CC') {
        return \Balanced\Card::get($this->account->token);
      } else {
        // This is a bank account
        return \Balanced\BankAccount::get($this->account->token);
      }
    }
  }

  /**
   * Update balanced customer info
   */
  public function syncCustomer()
  {
    // Setup data to store/update customer in balanced
    $data = array(
      'name' => $this->account->name,
      'email' => $this->account->email,
      'phone' => $this->account->phone,
      'address' => array(
        'line1' => $this->account->address,
        'line2' => $this->account->address_2,
        'city' => $this->account->city,
        'state' => $this->account->state,
        'postal_code' => $this->account->zipcode,
        'country_code' => $this->account->country
      ),
      'meta' => array(
        'payment_type' => $this->account->payment_type,
        'auto_renew' => $this->account->auto_renew
      )
    );
    // Create a customer record in balanced if it doesn't already exist
    if ( ! isset($this->balanced_info['customer_uri'])) {
      $customer = new \Balanced\Customer($data);
      $customer->save();
      // Store customer uri on our side for future lookups
      $this->balanced_info['customer_uri'] = $customer->href;
      $this->account->balanced_info = serialize($this->balanced_info);
      $this->account->updateUniques();
    } else {
      // Update existing customer
      $customer = \Balanced\Customer::get($this->balanced_info['customer_uri']);
      foreach ($data as $key => $value) {
        $customer->$key = $value;
      }
      $customer->save();
    }
  }

  /**
   * Check if new credit card/bank account has been added
   * and if so, associate payment with customer in balanced
   */
  public function syncPayment()
  {
    // Get the current payment record based on the account token
    $payment = $this->getPayment();
    // Check if card/account is associated with the customer
    if ( ! isset($payment->links->customer)) {
      $payment->associateToCustomer($this->getCustomer());
    }
  }

  /**
   * Charge the account and set a new expiration date if:
   *  - We have valid payment info, and
   *  - This is a new account, or
   *  - This is a recurring account that is past it's expiration date
   */
  public function chargeAccount()
  {
    $payment = $this->getPayment();
    if ( ! $payment) {
      return;
    }
    $subscription = $this->account->accountSubscription;
    if ( ! $subscription) {
      return;
    }
    if ( ! $this->account->expiration_date) {
      // New account, make payment
      if ( ! $this->account->yearly_payment) {
        // Charge monthly
        $amount = $subscription->monthly_price;
      } else {
        // Charge yearly
        $amount = $subscription->monthly_price * 12;
        // Annual discount
        $amount = $amount - ($amount * floatval('.'. $subscription->annual_discount));
      }
      $amount = $amount * 100;
      $ret = $payment->debits->create(array(
        'amount' => $amount,
        // Must be <= 22 characters
        'appears_on_statement_as' => 'contentlaunch.com sub',
        'description' => 'Membership fee for account: '. $this->account->title
      ));
      if ( ! $ret) {
        return;
      }
      if ( ! $this->account->yearly_payment) {
        $date = new \Carbon\Carbon($this->account->expiration_date);
        $date->addMonth();
        $this->account->expiration_date = $date->toDateTimeString();
      } else {
        $date = new \Carbon\Carbon($this->account->expiration_date);
        $date->addYear();
        $this->account->expiration_date = $date->toDateTimeString();
      }
      $this->account->updateUniques();
    }
  }

}
