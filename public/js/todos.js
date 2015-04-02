(function () {

  var todo = $('#todo'),
      add = $('#add'),
      list = $('#todos'),
      complete = $('#complete-all'),
      clear = $('#clear-completed'),
      errorMessage = $('#error');

  /**
   * Returns an event handler that appends
   * text from input to a list element
   *
   * @param {jQuery} list
   * @param {jQuery} input
   * @return {Function}
   */
  function append(list, input) {
    return function () {
      var val = input.val(),
          index = list.children('li').length,
          check = '<input type="checkbox" name="todo_' + index + '" />',
          del = '<div class="todo-delete todo-delete-' + index + '">x</div>';
      list.append('<li>' + val + check + del + '</li>');
      return val;
    };
  }

  /**
   * Persist the todo
   *
   * @param {String} val the todo label
   */
  function create(val) {
    return $.ajax({
      method: 'POST',
      url: '/todos',
      data: JSON.stringify({label: val}),
      contentType: 'application/json',
      dataType: 'json'
    });
  }

  /**
   * Returns an event handler for a creation response
   *
   * @param {jQuery} errorContainer the element to append an error message to
   * @param {jQuery} list the list containing the invalid item
   * @return {Function}
   */
  function postCreate(errorContainer, list) {
    return function (promise) {
      var todo = list.find('li:last-child');
      promise
        .done(function (resp) {
          todo.find('input[type=checkbox]').attr('data-id', resp._id.$id);
        })
        .fail(function (xhr) {
          if (xhr.status == 422) {
            errorContainer.text("Todo already exists");
            todo.remove();
          }
        });
    };
  }

  /**
   * Request an update to a todo
   *
   * @param {HTMLElement} todo
   * @param {Boolean} done
   * @return {Promise}
   */
  function update(todo, done) {
    return [$.ajax({
      method: 'PUT',
      url: '/todos/' + todo.dataset.id,
      data: JSON.stringify({done: done}),
      contentType: 'application/json',
      dataType: 'json'
    }), todo];
  }

  /**
   * Handle a clicked todo
   *
   * @param {Event} e
   * @return {Promise}
   */
  function toggle(e) {
    var checkbox = e.target;
    return update(checkbox, checkbox.checked);
  }

  /**
   * After toggling, update the list item.
   *
   * @param {Array} result
   */
  function postToggle(result) {
    var promise = result[0],
        checkbox = result[1],
        listItem = $(checkbox.parentNode);

    return promise.done(function (todo) {
      if (todo.done) {
        listItem.addClass('todo-complete');
        checkbox.checked = true;
      } else {
        listItem.removeClass('todo-complete');
        checkbox.checked = false;
      }
    });
  }

  /**
   * Returns an event handler for completing
   * all todos
   *
   * @param {jQuery} list
   * @param {Function} success
   */
  function completeAll(list, success) {
    return function() {
      var pending = list.children('li').not('.todo-complete');
      pending.each(function (i, li) {
        var checkbox = $(li).find('input[type=checkbox]');
        _.compose(success, update).call(null, checkbox[0], true);
      });
    }
  }

  /**
   * Returns an event handler for removing completed todos
   *
   * @param {jQuery} list
   * @param {Function} success
   * @return {Function}
   */
  function clearCompleted(list, success) {
    return function() {
      var completed = list.children('.todo-complete');
      completed.each(function (i, li) {
        var e = {target: $(li).find('.todo-delete')[0]};
        _.compose(success, del).call(null, e);
      });
    }
  }

  /**
   * Delete a todo
   *
   * @param {Event} e
   * @return {Promise}
   */
  function del(e) {
    var checkbox = $(e.target).prev('input');
    return [$.ajax({
      method: 'DELETE',
      url: '/todos/' + checkbox.data('id')
    }), checkbox[0]];
  }

  /**
   * Remove a todo node
   *
   * @param {Array} result a promise at index 0 and the todo at index 1
   */
  function removeNode(result) {
    var promise = result[0],
        todo = result[1];

    promise.done(function () {
      $(todo.parentNode).remove();
    });
  }

  /**
   * Create event streams
   */
  add.click(_.compose(postCreate(errorMessage, list), create, append(list, todo)));
  list.delegate('input[type=checkbox]', 'change', _.compose(postToggle, toggle));
  list.delegate('.todo-delete', 'click', _.compose(removeNode, del));
  complete.click(completeAll(list, postToggle));
  clear.click(clearCompleted(list, removeNode));

})();
