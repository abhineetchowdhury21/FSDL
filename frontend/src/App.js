import React, { useState } from "react";
import axios from "axios";
import "./App.css";

function App() {
  const [input, setInput] = useState("");

  const handleClick = (value) => {
    setInput(input + value);
  };

  const clear = () => {
    setInput("");
  };

  const calculate = async () => {
    try {
      const res = await axios.post("http://localhost:5000/calculate", {
        expression: input,
      });
      setInput(res.data.result);
    } catch {
      setInput("Error");
    }
  };

  return (
    <div className="calculator">
      <input value={input} readOnly />

      <div className="buttons">
        {["7","8","9","/","4","5","6","*","1","2","3","-","0",".","+","="].map((btn) => (
          <button
            key={btn}
            onClick={() => btn === "=" ? calculate() : handleClick(btn)}
          >
            {btn}
          </button>
        ))}
        <button onClick={clear}>C</button>
      </div>
    </div>
  );
}

export default App;