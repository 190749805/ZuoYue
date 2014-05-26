<?php 
class TitleModel extends RelationModel{
	  protected $_link = array(
        'Comment'=>array(
		'mapping_type'    =>HAS_MANY,
        'class_name'    =>'comment',
		'foreign_key'=>'tid',
		'mapping_name' => 'comment',
		'mapping_fields'=>'ccontent,ctime',
         ),
	);

}