$(document).ready(function(){
  'use strict';

  $('.menu-icon').click(function(e){
    $( this ).toggleClass( 'active' );
    
    $( 'nav', this.parent ).toggleClass( 'opened');
    e.preventDefault();

  });

  /*var container = $( '.grid' );
  container.imagesLoaded( function(){
    container.masonry({
      itemSelector: '.grid-item',
      columnWidth: '.grid-item'
    });
  });*/
  var container = $( '.grid' );
  container.imagesLoaded( function(){
    container.masonry({
      itemSelector: '.grid-item',
      columnWidth: '.grid-item'
    });
  });
});