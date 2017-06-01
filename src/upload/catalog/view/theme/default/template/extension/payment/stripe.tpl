<h2><?php echo $text_instruction; ?></h2>
<p><b><?php echo $text_description; ?></b></p>
<br>

<style>
  @media (max-width: 768px) {
      #card-wrapper-container {
        min-height:210px;
      }
      #card-wrapper {
        position:absolute;
        left:-50%;
        right:-50%;
        transform:scale(0.7);
      }
  }
</style>

<div id="stripe-cc-alert" class="alert alert-danger hidden" role="alert"></div>

<form action="<?php echo $stripe_payment_url; ?>" class="form-horizontal" id="stripe-cc-form" method="post">

  <div class="col-sm-6 col-sm-push-6">
    <div id="card-wrapper-container">
      <div id="card-wrapper"></div>
    </div>
  </div>

  <div class="col-sm-6 col-sm-pull-6">

    <div class="row">

      <div class="col-sm-12">
        <div class="form-group required">
          <label class="control-label" for="input-cc-number"><?php echo $entry_cc_number; ?></label>
          <input name="number" type="tel" placeholder="•••• •••• •••• ••••" id="input-cc-number" class="form-control" data-stripe="number" />
        </div>
      </div>

      <div class="col-sm-12">
        <div class="form-group required">
          <label class="control-label" for="input-cc-owner"><?php echo $entry_cc_owner; ?></label>
          <input name="name" type="text" placeholder="<?php echo $entry_cc_owner; ?>" id="input-cc-owner" class="form-control" data-stripe="name" />
        </div>
      </div>

      <div class="form-group required">
        <div class="col-xs-7">
          <label class="control-label" for="input-cc-expire-date"><?php echo $entry_cc_expire_date; ?></label>
          <input class="form-control" placeholder="MM/YY" type="tel" id="input-cc-expire-date" name="expiry" data-stripe="exp">
        </div>
        <div class="col-xs-5">
          <label class="control-label" for="input-cc-cvc"><?php echo $entry_cc_cvc; ?></label>
          <input type="number" name="cvc" placeholder="•••" id="input-cc-cvc" class="form-control" data-stripe="cvc" />
        </div>
      </div>
    </div>
  </div>

  <div class="buttons">
    <div class="pull-right">
      <button id="button-confirm" class="btn btn-primary"><?php echo $button_confirm; ?></button>
    </div>
  </div>

</form>

<script>
  var error_messages = {
    'invalid_number' : "<?php echo $error_invalid_number; ?>",
    'incorrect_number' : "<?php echo $error_incorrect_number; ?>",
    'invalid_expiry_month' : "<?php echo $error_invalid_expiry_month; ?>",
    'invalid_expiry_year' : "<?php echo $error_invalid_expiry_year; ?>",
    'invalid_cvc' : "<?php echo $error_invalid_cvc; ?>",
    'incorrect_cvc' : "<?php echo $error_incorrect_cvc; ?>",
    'expired_card' : "<?php echo $error_expired_card; ?>",
    'invalid_swipe_data' : "<?php echo $error_invalid_swipe_data; ?>",
    'incorrect_zip' : "<?php echo $error_incorrect_zip; ?>",
    'card_declined' : "<?php echo $error_card_declined; ?>",
    'missing' : "<?php echo $error_missing; ?>",
    'processing_error' : "<?php echo $error_processing_error; ?>",
    'general' : "<?php echo $error_general; ?>"
  };

  Stripe.setPublishableKey('<?php echo $stripe_public_key; ?>');

  $(function() {
    var $form = $('#stripe-cc-form');

    $form.submit(function(event) {
      $form.find('#button-confirm').prop('disabled', true);

      Stripe.source.create({
        type : 'card' ,
        card : {
          number : $('#input-cc-number').val() ,
          cvc : $('#input-cc-cvc').val() ,
          exp_month : $('#input-cc-expire-date').val().substr(0,2) ,
          exp_year : $('#input-cc-expire-date').val().substr(-2)
        },
        owner : {
          name : $('#input-cc-owner').val()
        }
      }, function (status, response) {

        if (response.error) {
          var $alert = $('#stripe-cc-alert');
          $alert.removeClass('hidden');

          if (response.error.code && error_messages[response.error.code]) {
            $alert.text(error_messages[response.error.code]);
          } else {
            $alert.text(error_messages['general']);
          }

          $form.find('#button-confirm').prop('disabled', false);
        } else {
          $form.append($('<input type="hidden" name="stripe_source">').val(response.id));
          $form.append($('<input type="hidden" name="stripe_three_d_secure">').val(response.card.three_d_secure));
          $form.get(0).submit();
        }
        
      });

      return false;
    });

    $form.card({
      container: '#card-wrapper'
    });

  });
</script>