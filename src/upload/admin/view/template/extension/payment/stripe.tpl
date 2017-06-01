<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-sofort" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if (isset($error['error_warning'])) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error['error_warning']; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-sofort" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <li><a href="#tab-status" data-toggle="tab"><?php echo $tab_order_status; ?></a></li>
          </ul>
          <div class="tab-content">
          
            <div class="tab-pane active" id="tab-general">

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-stripe-mode"><?php echo $entry_mode; ?></label>
                <div class="col-sm-10">
                  <select name="stripe_mode" id="input-stripe-mode" class="form-control">
                    <option <?php if ($stripe_mode == 'TEST') : ?>selected="selected" <?php endif; ?>value="TEST">TEST</option>
                    <option <?php if ($stripe_mode == 'LIVE') : ?>selected="selected" <?php endif; ?>value="LIVE">LIVE</option>
                  </select>
                </div>
              </div>
            
              <div class="form-group">
                <label class="col-sm-2 control-label" for="entry-stripe-live-public-key"><?php echo $entry_stripe_live_public_key; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="stripe_live_public_key" value="<?php echo $stripe_live_public_key; ?>" placeholder="<?php echo $entry_stripe_live_public_key; ?>" id="entry-stripe-live-public-key" class="form-control"/>
                  <?php if ($error_config_keys && $stripe_mode == 'LIVE') { ?>
                  <div class="text-danger"><?php echo $error_config_keys; ?></div>
                  <?php } ?>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="entry-stripe-live-private-key"><?php echo $entry_stripe_live_private_key; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="stripe_live_private_key" value="<?php echo $stripe_live_private_key; ?>" placeholder="<?php echo $entry_stripe_live_private_key; ?>" id="entry-stripe-live-private-key" class="form-control"/>
                  <?php if ($error_config_keys && $stripe_mode == 'LIVE') { ?>
                  <div class="text-danger"><?php echo $error_config_keys; ?></div>
                  <?php } ?>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="entry-stripe-test-public-key"><?php echo $entry_stripe_test_public_key; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="stripe_test_public_key" value="<?php echo $stripe_test_public_key; ?>" placeholder="<?php echo $entry_stripe_test_public_key; ?>" id="entry-stripe-test-public-key" class="form-control"/>
                  <?php if ($error_config_keys && $stripe_mode == 'TEST') { ?>
                  <div class="text-danger"><?php echo $error_config_keys; ?></div>
                  <?php } ?>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="entry-stripe-test-private-key"><?php echo $entry_stripe_test_private_key; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="stripe_test_private_key" value="<?php echo $stripe_test_private_key; ?>" placeholder="<?php echo $entry_stripe_test_private_key; ?>" id="entry-stripe-test-private-key" class="form-control"/>
                  <?php if ($error_config_keys && $stripe_mode == 'TEST') { ?>
                  <div class="text-danger"><?php echo $error_config_keys; ?></div>
                  <?php } ?>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-total"><span data-toggle="tooltip" title="<?php echo $help_total; ?>"><?php echo $entry_total; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="stripe_total" value="<?php echo $stripe_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control"/>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
                <div class="col-sm-10">
                  <select name="stripe_geo_zone_id" id="input-geo-zone" class="form-control">
                    <option value="0"><?php echo $text_all_zones; ?></option>
                    <?php foreach ($geo_zones as $geo_zone) { ?>
                    <?php if ($geo_zone['geo_zone_id'] == $stripe_geo_zone_id) { ?>
                    <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                <div class="col-sm-10">
                  <select name="stripe_status" id="input-status" class="form-control">
                    <?php if ($stripe_status) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="stripe_sort_order" value="<?php echo $stripe_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control"/>
                </div>
              </div>
              
            </div>
            
            <div class="tab-pane" id="tab-status">
              
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-completed-status"><?php echo $entry_completed_status; ?></label>
                <div class="col-sm-10">
                  <select name="stripe_completed_status_id" id="input-completed-status" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $stripe_completed_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-processed-status"><?php echo $entry_processed_status; ?></label>
                <div class="col-sm-10">
                    <select name="stripe_processed_status_id" id="input-processed-status" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $stripe_processed_status_id) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                </div>
                </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-pending-status"><?php echo $entry_pending_status; ?></label>
                <div class="col-sm-10">
                  <select name="stripe_pending_status_id" id="input-pending-status" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $stripe_pending_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-refunded-status"><?php echo $entry_refunded_status; ?></label>
                <div class="col-sm-10">
                  <select name="stripe_refunded_status_id" id="input-refunded-status" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $stripe_refunded_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
                           
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-void-status"><?php echo $entry_voided_status; ?></label>
                <div class="col-sm-10">
                  <select name="stripe_voided_status_id" id="input-void-status" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $stripe_voided_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>

          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>