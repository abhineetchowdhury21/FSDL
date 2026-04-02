const express = require("express");
const mongoose = require("mongoose");
const cors = require("cors");

const app = express();
app.use(cors());
app.use(express.json());

// MongoDB connect
mongoose.connect("mongodb://127.0.0.1:27017/calculator");

// Schema
const CalcSchema = new mongoose.Schema({
  expression: String,
  result: String,
});

const Calc = mongoose.model("Calc", CalcSchema);

// Route: save calculation
app.post("/calculate", async (req, res) => {
  const { expression } = req.body;

  let result;
  try {
    result = eval(expression).toString(); // simple eval
  } catch {
    result = "Error";
  }

  const data = new Calc({ expression, result });
  await data.save();

  res.json({ result });
});

app.listen(5000, () => console.log("Server running on port 5000"));