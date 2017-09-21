<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-nettivarasto" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	 <?php if (isset($success)) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-nettivarasto" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_admin; ?></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($nettivarasto_admin) { ?>
                <input type="radio" name="nettivarasto_admin" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="nettivarasto_admin" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$nettivarasto_admin) { ?>
                <input type="radio" name="nettivarasto_admin" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="nettivarasto_admin" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="nettivarasto_status" id="input-status" class="form-control">
                <?php if ($nettivarasto_status) { ?>
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
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_merchentid; ?></label>
            <div class="col-sm-10">
              <input type="text" name="nettivarasto_merchentid" id="nettivarasto_merchent_id" value="<?php echo $nettivarasto_merchentid;?>" size="40"/>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_secret_token; ?></label>
            <div class="col-sm-10">
              <input type="text" name="nettivarasto_secret_token" id="nettivarasto_secret_token" value="<?php echo $nettivarasto_secret_token;?>" size="40"/>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status">Export</label>
            <div class="col-sm-6">
              Click <a href="<?php echo $product_export_url;?>">here</a> to export all products to Nettivarasto.
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status">Get Latest Changes</label>
            <div class="col-sm-6">
              Click <a href="<?php echo $latest_update_url;?>">here</a> to update product and order info from Nettivarasto..
            </div>
          </div>
		  <?php 
		 /* 	print '<pre>';
			print_r($shipping_methods);*/
		  ?>
		  <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i>Nettivarasto shipping details</h3>
      </div>
		  <?php 
		  	foreach($shipping_methods as $shipping){
			$strCode = $shipping['code'];
			$strCodeKey	= "nettivarasto_code_".$strCode;
			$strStatusKey	= "nettivarasto_status_".$strCode;
		  ?>
		  <div class="form-group">
            <label class="col-sm-3 control-label" for="input-status"><?php echo ucfirst($strCode); ?> Shipping Nettivarasto Code</label>
            <div class="col-sm-3">
              <input type="text" name="nettivarasto_code_<?php echo $strCode;?>" id="nettivarasto_code_<?php echo $strCode;?>" value="<?php echo $shipping[$strCodeKey];?>" size="40"/>
            </div>
			<div style="clear:both"></div>
			<label class="col-sm-3 control-label" for="input-status"><?php echo ucfirst($strCode); ?> Shipping Nettivarasto Status</label>
			<div class="col-sm-3">
			<select name="nettivarasto_status_<?php echo $strCode?>" id="nettivarasto_status_<?php echo $strCode?>">
				<option value="0" <?php if($shipping[$strStatusKey]=="0"){?> selected <?php } ?>>Disable</option>
				<option value="1" <?php if($shipping[$strStatusKey]=="1"){?> selected <?php } ?>>Enable</option>
			</select>
            </div>
          </div>
		  <?php } ?>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>