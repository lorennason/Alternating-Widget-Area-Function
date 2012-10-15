jQuery(document).ready(function($) { 
	
	var $container = $('#content');
	
	$container.masonry({
		// options
		itemSelector : '.post',
	});
	
	$container.infinitescroll({
      navSelector  : '.navigation',    // selector for the paged navigation 
      nextSelector : '.navigation a',  // selector for the NEXT link (to page 2)
      itemSelector : '.post',     // selector for all items you'll retrieve
	  bufferPX : 90,
      loading: {
          finishedMsg: 'Congratulations, you have reached the end of the internet.',
          img: 'http://i.imgur.com/6RMhx.gif'
        }
	  },
      // trigger Masonry as a callback
      function( newElements ) {
        // hide new items while they are loading
        var $newElems = $( newElements ).css({ opacity: 0 });
        // ensure that images load before adding to masonry layout
        $newElems.imagesLoaded(function(){
          // show elems now they're ready
          $newElems.animate({ opacity: 1 });
          $container.masonry( 'appended', $newElems, true ); 
        });
      }
    );
    	
});