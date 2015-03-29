(function () {

  var todo = $('#todo'),
      add = $('#add'),
      list = $('#todos'),
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
        if (xhr.status == 400) {
          errorContainer.text("Todo already exists");
          list.find('li:last-child').remove();
        }
      });
    };
  }

  /**
   * Handle a clicked todo
   *
   * @param {Event} e
   * @return {Promise}
   */
  function toggleTodo(e) {
    var checkbox = e.target;
    return [$.ajax({
      method: 'PUT',
      url: '/todos/' + checkbox.dataset.id,
      data: JSON.stringify({done: checkbox.checked}),
      contentType: 'application/json',
      dataType: 'json'
    }), checkbox];
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

    promise.done(function () {
      if (checkbox.checked) {
        listItem.addClass('todo-complete');
      } else {
        listItem.removeClass('todo-complete');
      }
    });
  }

  /**
   * Create event streams
   */
  add.click(_.compose(postCreate(errorMessage, list), create, append(list, todo)));
  list.delegate('input[type=checkbox]', 'click', _.compose(postToggle, toggleTodo))

})();
