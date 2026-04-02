const express = require("express");
const bodyParser = require("body-parser");
const cors = require("cors");

const app = express();

app.use(bodyParser.json());
app.use(cors());

let books = [];

// Home route
app.get("/", (req, res) => {
    res.send("Book API Running");
});

app.get("/books", (req, res) => {
    res.json(books);
});

// Add book
app.post("/books", (req, res) => {
    const { title, author } = req.body;

    const newBook = {
        id: books.length + 1,
        title,
        author
    };

    books.push(newBook);

    res.status(201).json({
        message: "Book added successfully",
        book: newBook
    });
});

// Get all books
app.get("/books", (req, res) => {
    res.json(books);
});

app.listen(5000, () => {
    console.log("Server running on port 5000");
});