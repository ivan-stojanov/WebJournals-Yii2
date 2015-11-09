<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
\backend\assets\AppAsset::register($this);
$this->registerJsFile("@web/js/announcementScript.js", [ 'depends' => ['\yii\web\JqueryAsset'], 'position' => \yii\web\View::POS_END]);

$this->title = 'Announcements';
?>

<div class="alert alert-dismissable hidden-div" id="announcement-alert"> <?php /*alert-danger alert-success alert-warning */ ?>
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <strong><span id="announcement-alert-msg"></span></strong>
</div>

<h1>Announcement List</h1>
<hr>
<?php echo Html::a('Create', ['announcement/create'], ['class' => 'btn btn-success']) ?>
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
        			Title
        		</th>
            	<th>
                	Description
                </th>
                <th>
                    Created On
                </th>
                <th>
                    Is Visible
                </th>
                <th>
                    Actions
                </th>
            </tr>
        </thead>
        <tbody>
        	<?php foreach ($model as $modelAnnouncement){
        		$id = $modelAnnouncement->announcement_id;
        		$title = (strlen($modelAnnouncement->title) > 40) ? 
        					(substr($modelAnnouncement->title, 0, 40)."...") : ($modelAnnouncement->title);
        		$description = (strlen($modelAnnouncement->description) > 40) ? 
        					(substr($modelAnnouncement->description, 0, 40)."...") : ($modelAnnouncement->description);
        		$created_on = $modelAnnouncement->created_on;
        		$is_visible = $modelAnnouncement->is_visible;
        	?>
            <tr id="<?php echo 'row-number-'.$id?>">
                <td>
                    <i class="glyphicon glyphicon-sort"></i>
                </td>
            	<td>
                	<?php echo $title?><?php /* <a href="update/<?php echo $id?>"></a> */ ?>
                </td>
            	<td>
                	<?php echo $description?>
                </td>
                <td>
                	<?php echo $created_on?>
                </td>
                <td>
                    <label class="switch">
                        <input onclick="announcementScript_changeAnnouncementVisibility('check_<?php echo $id?>','<?php echo $id?>')" 
                        	type="checkbox" id='check_<?php echo $id?>'<?php echo (($is_visible) ? 'checked' : '')?> />
                        <span></span>
                    </label>
                </td>                
                <td>
                	<a href="view/<?php echo $id?>" title="View" data-toggle="tooltip" data-placement="top"><i class="glyphicon glyphicon-eye-open"></i></a>
                	<a href="update/<?php echo $id?>" title="Edit" data-toggle="tooltip" data-placement="top"><i class="glyphicon glyphicon-edit"></i></a>
                	<a href="delete/<?php echo $id?>" title="Delete" data-toggle="tooltip" data-placement="top" class="deleteAnnouncementBtn"><i class="glyphicon glyphicon-trash"></i></a>
                </td>
            </tr> 
            <?php } ?>
        </tbody>
    </table>
</div>
<?php } else { ?>
	<p>No announcements were found</p>
<?php }?>
<hr>
