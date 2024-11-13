const express = require('express');
const app = express();
const path = require('path');

// Serve static files (your HTML page)
app.use(express.static(path.join(__dirname, '')));

// Start the server on port 8000
app.listen(8000, () => {
  console.log('Server running on http://localhost:8000');
});
