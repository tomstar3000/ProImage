$(document).ready(function(){
  'use strict';

  $(".menu-icon").click(function(e){
    $( this ).toggleClass( 'active' );
    
    $( 'nav', this.parent ).toggleClass( 'opened');
    e.preventDefault();

  });
});