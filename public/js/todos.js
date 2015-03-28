(function () {

  var todo = $('#todo'),
      add = $('#add'),
      list = $('#todos');

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
    $.ajax({
      method: 'POST',
      url: '/todos',
      data: JSON.stringify({label: val}),
      contentType: 'application/json',
      dataType: 'json'
    });
  }

  add.click(_.compose(create, append(list, todo)));

})();
