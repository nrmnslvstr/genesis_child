<?php
/*
*
* All Customization for FOOTER
*
*/

//* --- Custom code for Search Toggle Button for Responsive Mobile Layout
add_action('genesis_after_footer','modal_search'); 
function modal_search(){?>
	<div class="modal fade" id="search-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">Search the Website</h4>
	      </div>
	      <div class="modal-body">
	        <?php get_search_form();?>
	      </div>
	    </div>
	  </div>
	</div>
<?php }
