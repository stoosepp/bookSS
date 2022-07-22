<?php
/**
 * Template part for displaying voting mechanism
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 *
 */?>
 		<script>
				jQuery(document).ready(function($){
					const thisPostID = my_script_vars.postID;
					checkVoteStatus(thisPostID);
					$('.votebutton').css('cursor', 'pointer');
					$('.votebutton').click(function(){ //if your upvote has class arrow?
					var direction = 'up';
					var classList = $(this).attr('class').split(/\s+/);
					$.each(classList, function(index, item) {
						if (item === 'fa-thumbs-up') {
							//console.log('Voting Up');
						}
						else if (item === 'fa-thumbs-down'){
							//console.log('Voting Down');
							direction = 'down';
						}
				});
				var ajaxscript = { ajax_url : '<?= site_url(); ?>' + "/wp-admin/admin-ajax.php" }
				var templateUrl = '<?= site_url(); ?>' + "/wp-admin/admin-ajax.php";

				$.ajax({
				type: "POST",
					url: ajaxscript.ajax_url,
				data: {
					action: 'vote',
					id: thisPostID,
					direction: direction //remove if not needed
				},

				success: function (output) { //do something with returned data
					$('.submit-vote').addClass('hidden');
					$('.did-vote').removeClass('hidden');
					setVoteStatus(thisPostID);
					if ($('#totalCount').length){
						var percentage = parseInt($('#percentage').text());
						var total = parseInt($('#totalCount').text());
						var upVotes = Math.round((percentage/100) * total);
						console.log('Upvotes are ' + upVotes);
						total++;
						if (direction == 'up'){
							upVotes++;
						}
						const percentageVal = Math.round((upVotes/total) * 100);
						$('#percentage').text(percentageVal);
						$('#totalCount').text(total);
						$('#vote-chart').css({'background':'rgb(220,112,108)','background': 'linear-gradient(90deg, rgba(103,216,173,1) ' + percentageVal + '%,rgba(220,112,108,1)' + percentageVal + '%)'});
					}
					else{
						if (direction == 'up'){
							console.log('Voted Up');
							$("#vote-results").html('<span id="percentage">100</span>% of <span id="totalCount">1</span> voters found this helpful.');
							$('#vote-chart').css({'background':'rgb(103,216,173)','height':'10px'});//Green
						}
						else{
							console.log('Voted Down');
							$("#vote-results").html('<span id="percentage">0</span>% of <span id="totalCount">1</span> voters found this helpful.');
							$('#vote-chart').css({'background':'rgb(220,112,108)','height':'10px'});//Red
						}
					}
						console.log(output);
						jQuery('#postupvote').text(output); //write to correct id - maybe use postid in the id of the vote count on the page e.g. id="vote23" jQuery('#vote'+postid)
					},
					error : function(error){ console.log(error)
					}


				});
    });

})
</script>
<?php
/* Voting */
//deleteAllPostMeta($post->ID);//For Testing
 	$bookRoot = getRootForPage($post);
	 $root = get_post($bookRoot);
	 consolePrint('Root ID is '.$root->ID.' THis page ID is '.$post->ID);
	 if (($post->ID != $root->ID) && is_page()) {
		$feedbackOn = get_post_meta( $root->ID, 'acceptFeedback', true );
		if($feedbackOn == true)
		{
			 ?>
			 <div class="post-votes">
			<div class="submit-vote">
				<p>Did this chapter help you learn?</p>
				<i class="votebutton far fa-thumbs-up"></i>
				<i class="votebutton far fa-thumbs-down"></i>
			 </div>
			<div class="did-vote hidden">
				<p>Thank you for providing feedback.</p>
		</div><?php
		$voteData = getVoteData($post->ID);
				if ($voteData){
				consolePrint('Up: '.$voteData[0].' Down: '.$voteData[1]);
				$totalCount = $voteData[0] + $voteData[1];
				$percentage = $voteData[0]/$totalCount;
				$percentageValue =  round($percentage,2)*100; ?>
								<p id="vote-results"><span id="percentage"><?php echo $percentageValue ?></span>% of <span id="totalCount"><?php echo $totalCount ?></span> voters found this helpful.</p>
								<div id="vote-chart" style="margin-top:5px; width:100%; height:10px; border-radius:5px;
								background: rgb(220,112,108);
								background: linear-gradient(90deg, rgba(103,216,173,1) <?php echo $percentageValue ?>%,rgba(220,112,108,1)  <?php echo $percentageValue ?>%);"></div>
<?php
				}
				else{
					echo '<p id="vote-results">No one has voted on this resource yet.</p>';
					echo '<div id="vote-chart" style="margin-top:5px; width:100%; height:0px; border-radius:5px;
					background: clear;"></div>';
				}
			 	?>

			</div><?php
		}
	 }

	?>