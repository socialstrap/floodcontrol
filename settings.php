<?php 
// double-check if admin
if (! defined('PUBLIC_PATH') || ! Zend_Auth::getInstance()->hasIdentity() || Zend_Auth::getInstance()->getIdentity()->role != 'admin') die('not allowed');

$fn = realpath(dirname(__FILE__)) . "/data.php";

// save?
if (isset($_POST['submitbtn'])) {

	$post_limit = isset($_POST['post-limit']) ? (int)$_POST['post-limit'] : 0;
	$post_period = isset($_POST['post-period']) ? $_POST['post-period'] : 'HOUR';
	$comment_limit = isset($_POST['comment-limit']) ? (int)$_POST['comment-limit'] : 0;
	$comment_period = isset($_POST['comment-period']) ? $_POST['comment-period'] : 'HOUR';
	$fcontent ="<?php\n
	define('PLUGIN_FLOODCONTROL_POST_LIMIT', ".$post_limit.");
	define('PLUGIN_FLOODCONTROL_POST_PERIOD', '".$post_period."');
	define('PLUGIN_FLOODCONTROL_COMMENT_LIMIT', ".$comment_limit.");
	define('PLUGIN_FLOODCONTROL_COMMENT_PERIOD', '".$comment_period."');
		";
	@file_put_contents($fn, $fcontent);
}

require_once $fn;
?>


<div class="well">

<?php if (! is_writable($fn)) echo '<p>Error: file not writtable: <br />' .$fn. '<hr /></p>';?>

<form action="" method="post">

<div class="form-group">
	<label for="post-limit">Maximum number of posts allowed:</label><br/>
	<input value="<?php echo PLUGIN_FLOODCONTROL_POST_LIMIT;?>" class="form-control" name="post-limit" id="post-limit" style="float:left; width: 48%">
	&nbsp;&nbsp;
	<select name="post-period" id="post-period" class="form-control" style="float:right; width: 48%">
		<option value="MINUTE" <?php if (PLUGIN_FLOODCONTROL_POST_PERIOD == 'MINUTE') echo 'selected="selected"'?>>Per Minute</option>
		<option value="HOUR" <?php if (PLUGIN_FLOODCONTROL_POST_PERIOD == 'HOUR') echo 'selected="selected"'?>>Per Hour</option>
		<option value="DAY" <?php if (PLUGIN_FLOODCONTROL_POST_PERIOD == 'DAY') echo 'selected="selected"'?>>Per Day</option>
		<option value="WEEK" <?php if (PLUGIN_FLOODCONTROL_POST_PERIOD == 'WEEK') echo 'selected="selected"'?>>Per Week</option>
		<option value="MONTH" <?php if (PLUGIN_FLOODCONTROL_POST_PERIOD == 'MONTH') echo 'selected="selected"'?>>Per Month</option>
	</select> 
	<br class="clearfix"><br>
</div>

<div class="form-group">
	<label for="comment-limit">Maximum number of comments allowed:</label><br/>
	<input value="<?php echo PLUGIN_FLOODCONTROL_COMMENT_LIMIT;?>" class="form-control" name="comment-limit" id="comment-limit" style="float:left; width: 48%">
	&nbsp;&nbsp;
	<select name="comment-period" id="comment-period" class="form-control" style="float:right; width: 48%">
		<option value="MINUTE" <?php if (PLUGIN_FLOODCONTROL_COMMENT_PERIOD == 'MINUTE') echo 'selected="selected"'?>>Per Minute</option>
		<option value="HOUR" <?php if (PLUGIN_FLOODCONTROL_COMMENT_PERIOD == 'HOUR') echo 'selected="selected"'?>>Per Hour</option>
		<option value="DAY" <?php if (PLUGIN_FLOODCONTROL_COMMENT_PERIOD == 'DAY') echo 'selected="selected"'?>>Per Day</option>
		<option value="WEEK" <?php if (PLUGIN_FLOODCONTROL_COMMENT_PERIOD == 'WEEK') echo 'selected="selected"'?>>Per Week</option>
		<option value="MONTH" <?php if (PLUGIN_FLOODCONTROL_COMMENT_PERIOD == 'MONTH') echo 'selected="selected"'?>>Per Month</option>
	</select> 
	<br class="clearfix"><br>
</div>

<span>Note: if set to zero, no limit is imposed.</span>

<hr/>

<div class="pull-right">
	<input type="submit" name="submitbtn" id="submitbtn" value="Update" class="submit btn btn-default">
</div>

</form>

</div>


