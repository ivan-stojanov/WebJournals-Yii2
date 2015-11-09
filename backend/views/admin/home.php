<?php
/* @var $this yii\web\View */
\backend\assets\AppAsset::register($this);
$this->registerJsFile("@web/js/homeScript.js", [ 'depends' => ['\yii\web\JqueryAsset'], 'position' => \yii\web\View::POS_END]);
$this->title = 'Dashboard';
?>

<div class="alert alert-dismissable hidden-div" id="homepage-section-alert"> <?php /*alert-danger alert-success alert-warning */ ?>
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <strong><span id="homepage-section-alert-msg"></span></strong>
</div>

<h1>Homepage Section List</h1>
<hr>
<?php if(isset($model) && count($model)>0) {?>
<div class="table-responsive">
	<table class="table table-hover table-striped" id="sortTable">
    	<thead>
        	<tr>
        		<th>
        			Order
        		</th>
            	<th>
                	Section
                </th>
                <th>
                    Is Visible
                </th>
            </tr>
        </thead>
        <tbody>
        	<?php foreach ($model as $modelSection){
        		$id = $modelSection->homepage_section_id;
        		$section_type = $modelSection->section_type;
        		$section_name = $common_vars->admin_homepage_sections[$section_type]['section_name'];
        		$section_url = $common_vars->admin_homepage_sections[$section_type]['section_url'];
        		$is_visible = $modelSection->is_visible;
        	?>
            <tr id="<?php echo 'row-number-'.$id?>">
                <td>
                    <i class="glyphicon glyphicon-sort"></i>
                </td>
            	<td>
                	<a href="<?php echo $section_url?>"><?php echo $section_name?></a>
                </td>
                <td>
                    <label class="switch">
                        <input onclick="homeScript_changeSectionVisibility('check_<?php echo $id?>','<?php echo $id?>')" 
                        	type="checkbox" id='check_<?php echo $id?>'<?php echo (($is_visible) ? 'checked' : '')?> />
                        <span></span>
                    </label>
                </td>
            </tr> 
            <?php } ?>
        </tbody>
    </table>
</div>
<?php } else { ?>
	<p>No sections for Homepage were found</p>
<?php }?>
<hr>