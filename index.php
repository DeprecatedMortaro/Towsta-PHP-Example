<?php

  define('TOWSTA_SECRET', 'SlMnEJeriN499RwlPJEPvTrr0mB71ubHGe5BWwn6Fgs7');

  if(get('/books')){
    sync_with_towsta( array( 'Book' => array( 'all' => 'true') ) );
    render('books');

  }elseif(get('/books/:id')){
    sync_with_towsta( array( 'Book' => array( 'id' => $GLOBALS['id']) ) );
    render('book');

  }

?>
