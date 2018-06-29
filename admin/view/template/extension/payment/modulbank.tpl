<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-fut" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
	  </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo "Оплата через Modulbank.com"; ?></h3>
      </div>
      <div class="panel-body">
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-fut" class="form-horizontal">
		   <div class="form-group">
				<label class="col-sm-2 control-label" for="input-total"><span class="required" data-toggle="tooltip" title="<?php echo "В разработке"; ?>">*</span> <?php echo $entry_merchant_id; ?></label>
				<div class="col-sm-10">
					<input type="text" name="modulbank_merchant_id" value="<?php echo $modulbank_merchant_id; ?>" /><br />
						<?php if ($error_merchant_id) { ?>
							<span class="error"><?php echo $error_merchant_id; ?></span>
						<?php } ?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-total"><span class="required" data-toggle="tooltip" title="<?php echo "В разработке"; ?>">*</span> <?php echo $entry_secret_key; ?></label>
				<div class="col-sm-10">
					<input type="text" name="modulbank_secret_key" value="<?php echo $modulbank_secret_key; ?>" /><br />
						<?php if ($error_secret_key) { ?>
							<span class="error"><?php echo $error_secret_key; ?></span>
						<?php } ?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-total"><?php echo $entry_callback_url; ?></label>
				<div class="col-sm-10">
					<?php echo $callback_url; ?>
				</div>
			</div>        
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-total"><?php echo $entry_mode; ?></label>
				<div class="col-sm-10">
					<select name="modulbank_mode">
						<option value="test"<?php if ($modulbank_mode == 'test') { ?> selected="selected"<?php } ?>><?php echo $entry_mode_test; ?></option>
						<option value="real"<?php if ($modulbank_mode == 'real') { ?> selected="selected"<?php } ?>><?php echo $entry_mode_real; ?></option>
					</select>
				</div>
			</div> 		
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-total"><?php echo $entry_order_status; ?></label>
				<div class="col-sm-10">
					<select name="modulbank_order_status_id">
					  <?php foreach ($order_statuses as $order_status) { ?>
						  <option 
							  value="<?php echo $order_status['order_status_id']; ?>" 
							  <?php if ($order_status['order_status_id'] == $modulbank_order_status_id) { ?>selected="selected"<?php } ?>
						  ><?php echo $order_status['name']; ?></option>
					  <?php } ?>
				  </select>
				</div>
			</div> 	     
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-total"><?php echo $entry_geo_zone; ?></label>
				<div class="col-sm-10">
					<select name="modulbank_geo_zone_id">
						<option value="0"><?php echo $text_all_zones; ?></option>
						<?php foreach ($geo_zones as $geo_zone) { ?>
							<option 
								value="<?php echo $geo_zone['geo_zone_id']; ?>" 
								<?php if ($geo_zone['geo_zone_id'] == $modulbank_geo_zone_id) { ?>selected="selected"<?php } ?>
							><?php echo $geo_zone['name']; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>         
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-total"><?php echo $entry_status; ?></label>
				<div class="col-sm-10">
					<select name="modulbank_status">
						<option value="1"<?php if ($modulbank_status)  { ?> selected="selected"<?php } ?>><?php echo $text_enabled; ?></option>
						<option value="0"<?php if (!$modulbank_status) { ?> selected="selected"<?php } ?>><?php echo $text_disabled; ?></option>
					</select>
				</div>
			</div>        
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-total"><?php echo $entry_sort_order; ?></label>
				<div class="col-sm-10">
					<input type="text" name="modulbank_sort_order" value="<?php echo $modulbank_sort_order; ?>" size="1" />
				</div>
			</div>          
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-total"><?php echo $entry_sort_order; ?></label>
				<div class="col-sm-10">
					 <a href="https://github.com/fpayments/modulbank-opencart23"><?php echo $modulbank_downloads; ?></a>
				</div>
			</div>        
		</form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?> 
