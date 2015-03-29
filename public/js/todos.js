(function () {

  var todo = $('#todo'),
      add = $('#add'),
      list = $('#todos'),
      complete = $('#complete-all'),
      errorMessage = $('#error');

  /**
   * Returns an event handler that appends
   * text from input to a list element.
   *
   * @param {jQuery} list
   * @param {jQuery} input
   * @return {Function}
   */
  function append(list, input) {
    return function () {
      var val = input.val(),
          count = list.children('li').length;
      list.append('<li>' + val + ' <input type="checkbox" name="todo_' + count + '" /></li>');
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
   * Returns an event handler for an error response.
   *
   * @param {jQuery} errorContainer the element to append an error message to
   * @param {jQuery} list the list containing the invalid item
   * @return {Function}
   */
  function postCreate(errorContainer, list) {
    return function (promise) {
      promise.fail(function (xhr) {
        if (xhr.status == 422) {
          errorContainer.text("Todo already exists");
          list.find('li:last-child').remove();
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
  function updateTodo(todo, done) {
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
  function toggleTodo(e) {
    var checkbox = e.target;
    return updateTodo(checkbox, checkbox.checked);
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
   * all todos.
   *
   * @param {jQuery} list
   * @param {Function} success
   */
  function completeAll(list, success) {
    var pending = list.children('li').not('.todo-complete');
    return function() {
      pending.each(function (i, li) {
        var checkbox = $(li).find('input[type=checkbox]');
        _.compose(success, updateTodo).call(null, checkbox[0], true);
      });
    }
  }

  /**
   * Create event streams
   */
  add.click(_.compose(postCreate(errorMessage, list), create, append(list, todo)));
  list.delegate('input[type=checkbox]', 'click', _.compose(postToggle, toggleTodo));
  complete.click(completeAll(list, postToggle));

})();
