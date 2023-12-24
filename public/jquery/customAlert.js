function customAlert(title, message) {
    $('#dialogBox').modal('hide');
    // Set the alert title and message
    document.getElementById('customAlertTitle').textContent = title;
    document.getElementById('customAlertMessage').textContent = message;

    // Show the custom alert modal
    var alertModal = new bootstrap.Modal(document.getElementById('customAlertModal'));

    alertModal.show();
  }

    // Replace the default alert function with the customAlert function
    window.alert = customAlert;

    // $(document).ready(function() {
    //   setTimeout(function() {
    //       $(".alert").fadeOut();
    //   }, 5000);
    // });
