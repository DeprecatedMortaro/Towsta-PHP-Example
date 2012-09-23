<h1>Books List</h1>
<ul>
  <?php foreach(Book::all() as $book) { ?>
    <li><a href="/books/<?php echo $book->get('id') ?>"><?php echo $book->get('title') ?></li>
  <?php } ?>
</ul>
