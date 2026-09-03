function search {
		var book = document.getElementById("book").value; // Get the input value from the textbox
		var xhr = new XMLHttpRequest(); // Create a new XMLHttpRequest object for server communication
		xhr.open("POST", "book-suggestion.php", true); // Initialize a POST request to the server
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); // Set the request header for form data
		xhr.onreadystatechange = function() { // Define the function to handle server response
			if (xhr.readyState == 4 && xhr.status == 200) { // Check if the request is complete and successful
				document.getElementById("suggestion").innerHTML = xhr.responseText; // Update the suggestion box with the server response
			} 
			else if (xhr.readyState == 4) { // Check if the request is complete but not successful
				alert('There was a problem with the request.'); // Display an error message if something went wrong
			}
		};
		xhr.send("book_name=" + encodeURIComponent(book)); // Send the input value to the server, encoded for safety
	}