<html>
<head>
  <script src="//cdn.jsdelivr.net/pouchdb/6.1.2/pouchdb.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.1.1.js" integrity="sha256-16cdPddA6VdVInumRGo6IbivbERE8p7CQR3HzTBuELA=" crossorigin="anonymous"></script>
</head>
<body>
<script>
var db = new PouchDB('test');
var remoteCouch = 'http://localhost:5984/test';

db.changes({
  since: 'now',
  live: true
}).on('change', showTodos);

function showTodos() {
  db.allDocs({include_docs: true, descending: true}, function(err, doc) {
    redrawTodosUI(doc.rows);
  });
}

function generateUUID() {
    var d = new Date().getTime();
    var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = (d + Math.random()*16)%16 | 0;
        d = Math.floor(d/16);
        return (c=='x' ? r : (r&0x3|0x8)).toString(16);
    });
    return uuid;
};

function redrawTodosUI(rows) {
  var container = $('.container');
  container.html('');
  console.log(rows);
  for(row_id in rows) {
    var row = rows[row_id].doc;
    console.log(row)
    var entry = $('<div class="entry"></div>');
    var field = $('<input type="text" name="field" class="field">').prop('value', row.field).appendTo(entry);
    var _id = $('<input type="text" name="_id" class="id">').prop('value', row._id).appendTo(entry);
    var _rev = $('<input type="text" name="_rev" class="rev">').prop('value', row._rev).appendTo(entry);
    var update = $('<button class="update">').text('update').appendTo(entry);
    entry.appendTo(container);
  }

  var entry = $('<div class="entry"></div>');
  var field = $('<input type="text" name="field" class="field">').appendTo(entry);
  var _id = $('<input type="text" name="_id" class="id">').prop('value', generateUUID()).appendTo(entry);
  var _rev = $('<input type="text" name="_rev" class="rev">').appendTo(entry);
  var update = $('<button class="update">').text('update').appendTo(entry);
  entry.appendTo(container);
}

function todoBlurred(todo, event) {
  //var trimmedText = event.target.value.trim();
  if (!todo.field) {
    db.remove(todo);
  } else {
    //todo.field = trimmedText;
    db.put(todo);
  }
}

jQuery(function($){
  $('body')
  //.on('change', '.field', function(event) {
  .on('click', '.update', function(event) {
    var entry = $(this).closest('.entry');
    entry = {
      field: $('.field', entry).val(),
      _id: $('.id', entry).val(),
      _rev: $('.rev', entry).val()
    };

    console.log(entry);
    todoBlurred(entry, event);
  })
});

//syncDom.setAttribute('data-sync-state', 'syncing');
var syncError;
var opts = {live: true};
db.replicate.to(remoteCouch, opts, syncError);
db.replicate.from(remoteCouch, opts, syncError);

showTodos();
</script>

TODO!
<div class="container">
</div>

</body>
</html>
