

<div class="vewp_view">
	<?php
	include_once('db_query.php');
	$view = $args['before_widget'].$args['before_title'];
	$data = get_recently_posts($length);
	$match;
	$img_link;

	/** title setting */
	if($title != NULL){
		$view .=$title;
	}else{
		$view .="Preview";
	}
	if($content_length==NULL)
		$content_length = 20;
	if($length==NULL)
		$length = 3;
	$view .= $args['after_title'];
	$view .="<table><tr>";
	
	for($i=0;$i<$length;$i++)
	{
		$match=NULL;
		$img_link = "src=";
		$img_check=get_first_img($data[$i]->id);
		$img_check = $img_check[0]->post_content;
		$cnt = preg_match_all('@<img\s+.*?(src\s*=\s*("[^"\\\\]*(?:[^"\\\\]*)*"|\'[^\'\\\\]*(?:[^\'\\\\]*)*\'|[^\s]+)).*?>@is', $img_check, $match);
		$img_link .= $match[2][0] .";";
		
		if($img_link=="src=;")
			$img_link = text;
		
		$view .="<td style='width: 30%; height:40px; '><img style='max-width:100%;' " .$img_link ." alt='' title='FRUIT' class='alignleft size-full wp-image-199' name='image'></img></td>";
		$view .="<td style='width:100%; height:60px; '><font style='font-size:15px;margin: 2px;'><a href='".$data[$i]->guid ."&action=edit'>" .strip_tags($data[$i]->post_title) ."</a></font><br />";

		if(strlen($data[$i]->post_content)>$content_length)
		{$view .="<font style='margin : 2px'>".mb_substr(strip_tags($data[$i]->post_content),0,$content_length-1,'UTF-8') ."... </font></td></tr>";}
		else
		{$view .="<font style='margin : 2px'>".$data[$i]->post_content ."</font></td></tr>";
		}
	}
	$view.="</table>";
	/**  */
	echo $view;

	?>
</div>
