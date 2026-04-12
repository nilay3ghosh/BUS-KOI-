alert("welcome to vraman")
// Get the search input and button elements
const searchInput = document.getElementById('search-input');
const searchButton = document.getElementById('search-button');

// Add an event listener to the search button
searchButton.addEventListener('click', () => {
    // Get the search query from the input field
    const searchQuery = searchInput.value.trim();

    // Check if the search query is not empty
    if (searchQuery !== '') {
        // Do something with the search query (e.g., send a request to a server)
        console.log(`Searching for: ${searchQuery}`);
    } else {
        console.log('Please enter a search query.');
    }
});
const menuButton = document.querySelector('.menu-button');
const menuOptions = document.querySelector('.menu-options');

menuButton.addEventListener('click', () => {
    menuOptions.style.display = menuOptions.style.display === 'block' ? 'none' : 'block';
});
