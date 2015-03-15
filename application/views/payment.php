<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Submit Order</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/custom.css"> <!-- Todo: minified version -->
	<script src="js/jquery-1.11.2.min.js"></script>
	<script src="js/validation.js"></script> <!-- Todo: minified version -->
</head>
<body>

<div class="container mTop20">

	<div class="row">
		<div class="col col-sm-12">					
			<div class="panel panel-default">
				<div class="panel-heading panel-heading-all">
					Make a payment			
				</div>
				<div class="panel-body">
			
  <form class="form-horizontal" role="form" id="paymentForm" method="post">
	<?php if($this->session->flashdata('error_message')): ?>
		<div class="alert alert-danger" role="alert"><?php echo $this->session->flashdata('error_message');?></div>
    <?php endif; ?>
	<?php if($this->session->flashdata('success_message')): ?>
		<div class="alert alert-success" role="success"><?php echo $this->session->flashdata('success_message');?></div>
    <?php endif; ?>
    <fieldset>
      <legend>Order</legend>
      <div class="form-group">
        <label class="col-sm-3 control-label" for="card-holder-name">Customer Full Name</label>
        <div class="col-sm-4">
          <input type="text" class="form-control" name="customerName" id="customerName" 
		  placeholder="Customer Name" value="<?php echo set_value('customerName'); ?>">
		  <div id="customerNameInfo"><?php echo form_error('customerName'); ?></div> 
        </div>
      </div>
	  
	  <div class="form-group">
        <label class="col-sm-3 control-label" for="expiry-month">Price</label>
        <div class="col-sm-4">
          <div class="row">
            <div class="col-xs-6">
			  <input type="text" class="form-control col-sm-2" name="price" id="price" 
			  placeholder="Price" value="<?php echo set_value('price'); ?>">
            </div>
            <div class="col-xs-6">
              <select class="form-control" name="currencyType">
				<?php $currencyTypes = ["USD" => "USD", "EUR" => "EUR", "THB" => "THB", "HKD" => "HKD", 
				"SGD" => "SGD", "AUD" => "AUD"]; ?>
				<?php foreach($currencyTypes as $currencyType): ?>
					<option value="<?php echo $currencyType; ?>" <?php echo set_select('currencyType', $currencyType); ?>><?php echo $currencyType; ?></option>
				<?php endforeach; ?>
              </select>
            </div>
			<div class="col-xs-12" id="priceInfo"><?php echo form_error('price'); ?></div> 
          </div>
        </div>
      </div>
	  
    </fieldset>
  
    <fieldset>
      <legend>Payment</legend>
      <div class="form-group">
        <label class="col-sm-3 control-label" for="card-holder-name">Name on Card</label>
        <div class="col-sm-4">
          <input type="text" class="form-control" name="cardHolderName" id="cardHolderName" 
		  placeholder="Card Holder's Name" value="<?php echo set_value('cardHolderName'); ?>">
		  <div id="cardHolderNameInfo"><?php echo form_error('cardHolderName'); ?></div> 
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label" for="card-number">Card Number</label>
        <div class="col-sm-4">
          <input type="text" class="form-control" name="cardNumber" id="cardNumber" 
		  placeholder="Debit/Credit Card Number" value="<?php echo set_value('cardNumber'); ?>">
		  <div id="cardNumberInfo"><?php echo form_error('cardNumber'); ?></div> 
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label" for="expiry-month">Expiration Date</label>
        <div class="col-sm-4">
          <div class="row">
            <div class="col-xs-6">
              <select class="form-control col-sm-2" name="expireMonth" id="expireMonth">
                <option value="">Month</option>
				<?php for($m=1; $m<=12; ++$m): ?>
					<?php 
						$month = date('m', mktime(0, 0, 0, $m, 1));
						$month_text = date('F', mktime(0, 0, 0, $m, 1)); 
					?>
					<option value="<?php echo $month; ?>" <?php echo set_select('expireMonth', $month); ?>><?php echo $month_text; ?></option>
				<?php endfor; ?>
              </select>
            </div>
            <div class="col-xs-6">
              <select class="form-control" name="expireYear">
				<?php for($year = date("Y"); $year <= date("Y") + 10; ++$year): ?>
					<option value="<?php echo $year; ?>" <?php echo set_select('expireYear', $year); ?>><?php echo $year; ?></option>
				<?php endfor; ?>                
              </select>
            </div>
			<div class="col-xs-12" id="expireMonthInfo"><?php echo form_error('expireMonth'); ?></div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label" for="cvv">Card CVV</label>
        <div class="col-sm-4">
          <input type="text" class="form-control" name="cvv" id="cvv" 
		  placeholder="Security Code" value="<?php echo set_value('cvv'); ?>" maxlength="4">
		  <div id="cvvInfo"><?php echo form_error('cvv'); ?></div>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
          <button type="submit" class="btn btn-success">Pay Now</button>		  
        </div>
      </div>
    </fieldset>
  </form>

  
				</div>
  </div>


</body>
</html>