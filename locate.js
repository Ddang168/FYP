// Optional: Add event listener to perform an action when the selection changes
document.getElementById('location').addEventListener('change', function() {
    var selectedLocation = this.value;
    console.log('Selected location: ' + selectedLocation);
});
