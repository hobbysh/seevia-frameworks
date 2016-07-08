<?php
	/*
		keywords:关键字
		log_time_start:日志开始时间
		log_time_end:日志结束时间
	*/
	//pr($system_log_data);
?>


<form action="/admin/system_logs/index" name="Form_system_logs"  method="get">
<ul class="am-avg-lg-3 am-avg-md-2  am-avg-sm-1">
		<li class="am-margin-top-xs">
  	<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text">日志类型</label>
  	<div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
    <select data-am-selected name="log_type" value="<?php if(isset($log_type)){ echo $log_type; } ?>">
        <option value="">请选择</option>
        <option value="debug" <?php if (@$log_type == 'debug'){echo "selected";} ?>>调试</option>
        <option value="info" <?php if (@$log_type == 'info'){echo "selected";} ?>>信息</option>
        <option value="notice" <?php if (@$log_type == 'notice'){echo "selected";} ?>>通知</option>
        <option value="warning" <?php if (@$log_type == 'warning'){echo "selected";} ?>>警告</option>
        <option value="error" <?php if (@$log_type == 'error'){echo "selected";} ?>>错误</option>
    </select>
  </div>
  </li>

	<li class="am-margin-top-xs">
		<label class="am-u-lg-3  am-u-md-3  am-u-sm-4 am-form-label-text am-margin-left-0">日志时间</label>
		<div class="am-u-lg-3  am-u-md-3 am-u-sm-3" >
                    <input style="min-height:35px;cursor:pointer" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success'}" name="log_time_start" value="<?php echo @$log_time_start; ?>" />
                </div>
                <em class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-center" style="padding: 0.35em 0px;">-</em>
                <div class=" am-u-lg-3  am-u-md-3  am-u-sm-3 am-u-end" >
                    <input style="min-height:35px;cursor:pointer" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success'}" name="log_time_end" value="<?php echo @$log_time_end; ?>" />
        </div>
	</li>

	<li class="am-margin-top-xs">
    <label class="am-u-lg-3  am-u-md-3  am-u-sm-4 am-form-label-text">关键字</label>
    <div class="am-u-lg-7 am-u-md-7 am-u-sm-6">
		<input name="keywords" value="<?php echo @$keywords ?>" class="am-form-field am-input-sm" type="text" placeholder="关键字">
    </div>
	</li>

  <li class="am-margin-top-xs" style="margin-left:10px;">
   <div class="am-u-sm-3 ">&nbsp;</div>
   <div class="am-u-sm-3 ">
	<button class="am-btn am-btn-success am-btn-sm am-radius">搜索</button>
    </div>
	</li>
</ul>
<div class="am-panel am-panel-default am-margin-top-lg">
	<div class="am-panel-hd am-cf">
  	<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
      日志类型
    </div>
    <div class="am-u-lg-9 am-u-sm-9 am-u-sm-9">日志详情</div>
  	<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">日志时间</div>
   </div>
  	<?php if (isset($system_log_data)&&sizeof($system_log_data)>0) { foreach ($system_log_data as $k => $v) { ?>
	<div class="am-panel-bd am-cf" style="border-bottom:1px solid #ddd">
	<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
      <?php if ($v['SystemLog']['type'] == 'debug') {
      	echo "调试";
      }elseif($v['SystemLog']['type'] == 'info'){
      	echo "信息";
      }elseif ($v['SystemLog']['type'] == 'notice') {
      	echo "通知";
      }elseif ($v['SystemLog']['type'] == 'warning') {
      	echo "警告";
      }elseif ($v['SystemLog']['type'] == 'error') {
      	echo "错误";
      } ?>&nbsp;
    </div>
    <div class="am-u-lg-9 am-u-md-9 am-u-sm-9 am-text-break">
      <?php echo $v['SystemLog']['log_text'] ?>&nbsp;
    </div>
    <div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-text-break">
      <?php echo $v['SystemLog']['created'] ?>&nbsp;
    </div>
	</div>
	<?php }} ?>
	<?php if (empty($system_log_data)) { ?>
	<div class="am-panel-bd am-cf am-text-center" style="border-bottom:1px solid #ddd">
	暂无数据
	</div>
	<?php } ?>
</div>
<?php echo $this->element('pagers') ?>
</form>