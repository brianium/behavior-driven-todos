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
   * Handle a todo creation response
   *
   * @param {Promise} promise
   */
  function postCreate(promise) {
    promise.fail(function (xhr) {
      if (xhr.status == 400) {
        errorMessage.text("Todo already exists");
      }
    });
  }

  /**
   * Create add button event stream
   */
  add.click(_.compose(postCreate, create, append(list, todo)));

})();
