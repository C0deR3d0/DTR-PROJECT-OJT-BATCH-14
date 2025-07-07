document.getElementById("submit-button").addEventListener("click", function() {
  
  // This is your SweetAlert2 code
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!"
  }).then((result) => {
    // This part of the code runs AFTER the user clicks a button
    if (result.isConfirmed) {
      // If the user clicked "Yes, delete it!"
      Swal.fire({
        title: "Deleted!",
        text: "Your file has been deleted.",
        icon: "success"
      });
      // Here you can add the actual logic to delete the item,
      // for example, making an AJAX call to your server.
    }
  });

});