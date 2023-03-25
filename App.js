import React, { useState } from "react";

function SearchWindow() {
  const [searchTerm, setSearchTerm] = useState("");
  const [searchResults, setSearchResults] = useState([]);

  const handleSearch = (event) => {
    event.preventDefault();

    // Define the URL of the server + the search term
    const url = "http://localhost:8000"+"?search_term="+encodeURIComponent(searchTerm);

    // Define the options for the fetch request
    const options = {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({searchTerm}),
    };

    // Call the server using fetch() and insert the 
    // response data to searchResults
    fetch(url, options)
      .then((response) => response.json())
      .then((data) => setSearchResults(data))
      .catch((error) => console.error(error));

    setSearchTerm(""); // Clear the search input field
  };

  return (
    <div>
      <form onSubmit={handleSearch}>
        <label htmlFor="searchInput">Search:</label>
        <input
          type="text"
          id="searchInput"
          value={searchTerm}
          onChange={(event) => setSearchTerm(event.target.value)}
        />
        <button type="submit">Search</button>
      </form>

      {searchResults.length > 0 && (
        <div>
          <h2>Search results:</h2>
          <ul>
            {searchResults.map((result, index) => (
              <li key={index}>{result}</li>
            ))}
          </ul>
        </div>
      )}
    </div>
  );
}

export default SearchWindow;
