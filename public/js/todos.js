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
      var val = input.val();
      list.append('<li>' + val + '</li>');
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
   * Create add button event stream
   */
  add.click(_.compose(postCreate(errorMessage, list), create, append(list, todo)));

})();
